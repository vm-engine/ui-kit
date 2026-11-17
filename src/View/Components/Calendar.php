<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Concerns\SanitizesWireArguments;

/**
 * Month event calendar: a 7-column grid (md+) and an agenda list (small screens).
 * nav-method('Y-m'), event-method('id') and day-method('Y-m-d') call the host.
 * Events: [['id' => …, 'date' => 'Y-m-d', 'title' => …, 'color' => 'blue'], …];
 * events with an unsafe id or an invalid date are dropped.
 */
final class Calendar extends Component
{
    use SanitizesWireArguments;

    private const MAX_EVENTS_PER_DAY = 3;

    /** @var array<string, string> colour => event chip classes (Synapse names are aliases) */
    private const CHIPS = [
        'zinc' => 'bg-zinc-100 text-zinc-700 dark:bg-white/10 dark:text-zinc-200',
        'accent' => 'bg-accent text-accent-foreground',
        'blue' => 'bg-blue-100 text-blue-700 dark:bg-blue-400/15 dark:text-blue-300',
        'green' => 'bg-green-100 text-green-700 dark:bg-green-400/15 dark:text-green-300',
        'amber' => 'bg-amber-100 text-amber-800 dark:bg-amber-400/15 dark:text-amber-300',
        'red' => 'bg-red-100 text-red-700 dark:bg-red-400/15 dark:text-red-300',
        'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-400/15 dark:text-purple-300',
    ];

    private const ALIASES = ['gray' => 'zinc', 'brand' => 'accent', 'success' => 'green', 'warning' => 'amber', 'danger' => 'red', 'info' => 'blue'];

    public CarbonImmutable $monthStart;

    public string $weekStart;

    public ?string $navMethod;

    public ?string $eventMethod;

    public ?string $dayMethod;

    /** @var array<string, list<array{id: string, title: string, chip: string}>> */
    private array $eventsByDate;

    /**
     * @param  array<int|string, mixed>  $events
     */
    public function __construct(
        ?string $month = null,
        array $events = [],
        ?string $navMethod = null,
        ?string $eventMethod = null,
        ?string $dayMethod = null,
        string $weekStart = 'monday',
    ) {
        $this->monthStart = $this->parseMonth($month);
        $this->weekStart = $weekStart === 'sunday' ? 'sunday' : 'monday';
        $this->navMethod = $this->wireMethod($navMethod);
        $this->eventMethod = $this->wireMethod($eventMethod);
        $this->dayMethod = $this->wireMethod($dayMethod);
        $this->eventsByDate = $this->groupEvents($events);
    }

    /**
     * @return list<list<array{date: string, day: int, inMonth: bool, isToday: bool, events: list<array{id: string, title: string, chip: string}>, more: int}>>
     */
    public function weeks(): array
    {
        // Carbon day-of-week numbers: 0 = Sunday, 1 = Monday, 6 = Saturday.
        $firstDay = $this->weekStart === 'sunday' ? 0 : 1;
        $lastDay = $this->weekStart === 'sunday' ? 6 : 0;
        $cursor = $this->monthStart->startOfWeek($firstDay);
        $last = $this->monthStart->endOfMonth()->endOfWeek($lastDay)->startOfDay();
        $today = CarbonImmutable::today()->toDateString();

        $weeks = [];
        $week = [];

        while ($cursor->lte($last)) {
            $date = $cursor->toDateString();
            $events = $this->eventsByDate[$date] ?? [];

            $week[] = [
                'date' => $date,
                'day' => $cursor->day,
                'inMonth' => $cursor->month === $this->monthStart->month,
                'isToday' => $date === $today,
                'events' => array_slice($events, 0, self::MAX_EVENTS_PER_DAY),
                'more' => max(0, count($events) - self::MAX_EVENTS_PER_DAY),
            ];

            if (count($week) === 7) {
                $weeks[] = $week;
                $week = [];
            }

            $cursor = $cursor->addDay();
        }

        return $weeks;
    }

    /**
     * @return list<array{date: string, label: string, events: list<array{id: string, title: string, chip: string}>}>
     */
    public function agenda(): array
    {
        $prefix = $this->monthStart->format('Y-m-');
        $dates = array_values(array_filter(array_keys($this->eventsByDate), fn (string $date): bool => str_starts_with($date, $prefix)));
        sort($dates);

        return array_map(fn (string $date): array => [
            'date' => $date,
            'label' => $this->localized(CarbonImmutable::parse($date))->isoFormat('ddd, D MMM'),
            'events' => $this->eventsByDate[$date],
        ], $dates);
    }

    /**
     * @return list<string>
     */
    public function weekdayLabels(): array
    {
        return array_map(
            fn (array $cell): string => $this->localized(CarbonImmutable::parse($cell['date']))->isoFormat('ddd'),
            $this->weeks()[0],
        );
    }

    public function monthLabel(): string
    {
        return $this->localized($this->monthStart)->isoFormat('MMMM YYYY');
    }

    public function previousMonth(): string
    {
        return $this->monthStart->subMonthNoOverflow()->format('Y-m');
    }

    public function nextMonth(): string
    {
        return $this->monthStart->addMonthNoOverflow()->format('Y-m');
    }

    public function currentMonth(): string
    {
        return CarbonImmutable::now()->format('Y-m');
    }

    public function render(): View
    {
        return view('ui-kit::components.calendar');
    }

    /**
     * The date in the app locale (Carbon's locale() is a getter/setter, hence the check).
     */
    private function localized(CarbonImmutable $date): CarbonImmutable
    {
        $localized = $date->locale(app()->getLocale());

        return $localized instanceof CarbonImmutable ? $localized : $date;
    }

    private function parseMonth(?string $month): CarbonImmutable
    {
        if ($month !== null && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month) === 1) {
            $parsed = CarbonImmutable::createFromFormat('!Y-m', $month);

            if ($parsed instanceof CarbonImmutable) {
                return $parsed->startOfMonth();
            }
        }

        return CarbonImmutable::now()->startOfMonth();
    }

    /**
     * @param  array<int|string, mixed>  $events
     * @return array<string, list<array{id: string, title: string, chip: string}>>
     */
    private function groupEvents(array $events): array
    {
        $grouped = [];

        foreach ($events as $event) {
            if (! is_array($event)) {
                continue;
            }

            $id = isset($event['id']) && (is_int($event['id']) || is_string($event['id'])) ? $this->safeKey($event['id']) : null;
            $title = $event['title'] ?? null;
            $date = $event['date'] ?? null;

            if ($id === null || ! is_string($title) || $title === '' || ! is_string($date) || ! $this->isValidDate($date)) {
                continue;
            }

            $color = is_string($event['color'] ?? null) ? (self::ALIASES[$event['color']] ?? $event['color']) : 'accent';

            $grouped[$date][] = [
                'id' => $id,
                'title' => $title,
                'chip' => self::CHIPS[$color] ?? self::CHIPS['accent'],
            ];
        }

        return $grouped;
    }

    private function isValidDate(string $date): bool
    {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $parts) !== 1) {
            return false;
        }

        return checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1]);
    }
}
