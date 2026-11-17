<?php

declare(strict_types=1);

use Illuminate\View\ViewException;
use VmEngine\UiKit\View\Components\Calendar;

it('renders month navigation and day/event actions', function (): void {
    $html = decoded('<x-ui::calendar month="2026-11" :events="$events" nav-method="setMonth" event-method="openEvent" day-method="createOn" />', [
        'events' => [['id' => 12, 'date' => '2026-11-17', 'title' => 'Release', 'color' => 'green']],
    ]);

    expect($html)->toContain("setMonth('2026-10')")
        ->toContain("setMonth('2026-12')")
        ->toContain("openEvent('12')")
        ->toContain("createOn('2026-11-17')")
        ->toContain('Release')
        ->toContain('bg-green-100');
});

it('starts the week on the configured day', function (): void {
    expect((new Calendar(month: '2026-11'))->weekdayLabels()[0])->toBe('Mon')
        ->and((new Calendar(month: '2026-11', weekStart: 'sunday'))->weekdayLabels()[0])->toBe('Sun');
});

it('drops events with an unsafe id or an invalid date', function (): void {
    $html = render('<x-ui::calendar month="2026-11" event-method="openEvent" :events="$events" />', [
        'events' => [
            ['id' => "1');x()//", 'date' => '2026-11-03', 'title' => 'Odd'],
            ['id' => 2, 'date' => '2026-02-30', 'title' => 'Bad date'],
        ],
    ]);

    expect($html)->not->toContain('Odd')->not->toContain('Bad date');
});

it('throws on an unsafe nav method', function (): void {
    render('<x-ui::calendar nav-method="go(); x()" />');
})->throws(ViewException::class);
