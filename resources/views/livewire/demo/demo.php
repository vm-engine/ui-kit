<?php

use Carbon\CarbonImmutable;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use VmEngine\FileManager\Http\Controllers\AssetController;
use VmEngine\UiKit\Demo\DemoNotification;

/**
 * Opt-in demo of every vm-engine/ui-kit component (ui-kit.demo.enabled), used for
 * light/dark browser checks. Served at /ui-kit-demo inside the host's layout.
 */
new class extends Component
{
    use WithFileUploads;
    use WithPagination;

    public int $perPage = 15;

    #[Url]
    public string $tab = 'security';

    public int $step = 2;

    /**
     * Null default + value set in mount(): Livewire never hydrates null into a typed
     * property, so a non-null default would come back after resetAmount().
     */
    public ?string $amount = null;

    public string $formattedAmount = '';

    public string $color = '#3b82f6';

    public ?string $expiresAt = null;

    public ?string $period = null;

    public ?string $status = null;

    /** @var list<string> */
    public array $tags = [];

    public ?int $personId = null;

    public ?int $pageId = null;

    /** @var TemporaryUploadedFile|null */
    public $avatar = null;

    /** @var array<int, TemporaryUploadedFile> */
    public array $docs = [];

    /** @var array<string, list<array{id: string, name: string}>> */
    public array $board = [
        'todo' => [['id' => 'a', 'name' => 'Write spec'], ['id' => 'b', 'name' => 'Design tabs']],
        'doing' => [['id' => 'c', 'name' => 'Port adv-select']],
        'done' => [],
    ];

    public ?string $month = null;

    public string $lastMove = '';

    public string $body = '<p>Hello <strong>world</strong></p>';

    public string $docBody = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        $this->amount = '1250000.5';
    }

    /**
     * 120 fake rows, paginated by the per-page selector.
     *
     * @return LengthAwarePaginator<int, string>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        $all = collect(range(1, 120))->map(fn (int $i): string => "Row {$i}");
        $page = $this->getPage();

        return new LengthAwarePaginator($all->forPage($page, $this->perPage)->values(), $all->count(), $this->perPage, $page);
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Fake rows carrying delete-token-like strings (never raw ids).
     *
     * @return list<array{name: string, delete_token: string}>
     */
    public function tokenRows(): array
    {
        return [
            ['name' => 'Alpha', 'delete_token' => 'tok_1'],
            ['name' => 'Bravo', 'delete_token' => 'tok_2'],
            ['name' => 'Charlie', 'delete_token' => 'tok_3'],
        ];
    }

    public function delete(string $token): void
    {
        Flux::toast("Deleted {$token}");
    }

    public function archive(string $token): void
    {
        Flux::toast("Archived {$token}");
    }

    public function goToStep(int $number): void
    {
        $this->step = $number;
    }

    public function nextStep(): void
    {
        $this->step = min(3, $this->step + 1);
    }

    public function resetAmount(): void
    {
        $this->amount = null;
    }

    public function clearDate(): void
    {
        $this->expiresAt = null;
    }

    /**
     * Remote search for the adv-select demo (200 fake people).
     *
     * @return list<array{value: int, label: string}>
     */
    public function searchPeople(string $term): array
    {
        return collect(range(1, 200))
            ->map(fn (int $i): array => ['value' => $i, 'label' => "Person {$i}"])
            ->filter(fn (array $row): bool => str_contains(strtolower($row['label']), strtolower($term)))
            ->take(20)->values()->all();
    }

    /**
     * Labels for preset ids (adv-select load-method).
     *
     * @return list<array{value: int, label: string}>|array{value: int, label: string}
     */
    public function loadPeople(int|array $ids): array
    {
        $rows = array_map(fn (int $i): array => ['value' => $i, 'label' => "Person {$i}"], (array) $ids);

        return is_array($ids) ? $rows : $rows[0];
    }

    public function setPerson(): void
    {
        $this->personId = 42;
    }

    public function clearTags(): void
    {
        $this->tags = [];
    }

    public function saveAvatar(): void
    {
        $this->validate(['avatar' => 'required|image|max:2048']);
        Flux::toast('Avatar uploaded: '.$this->avatar->getClientOriginalName());
    }

    public function saveDocs(): void
    {
        $this->validate(['docs' => 'required|array|max:3', 'docs.*' => 'file|mimes:pdf,jpg,png|max:5120']);
        Flux::toast(count($this->docs).' file(s) uploaded');
    }

    public function moveItem(string $id, int $position, string $columnId): void
    {
        $item = null;
        foreach ($this->board as $column => $items) {
            foreach ($items as $index => $row) {
                if ($row['id'] === $id) {
                    $item = $row;
                    array_splice($this->board[$column], $index, 1);
                    break 2;
                }
            }
        }

        if ($item !== null && array_key_exists($columnId, $this->board)) {
            array_splice($this->board[$columnId], $position, 0, [$item]);
            $this->lastMove = "{$id} → {$columnId} @ {$position}";
        }
    }

    public function setMonth(string $month): void
    {
        $this->month = $month;
    }

    /**
     * @return list<array{id: int, date: string, title: string, color: string}>
     */
    public function calendarEvents(): array
    {
        $base = $this->month !== null ? CarbonImmutable::createFromFormat('!Y-m', $this->month) : now()->toImmutable()->startOfMonth();

        return [
            ['id' => 1, 'date' => $base->addDays(2)->toDateString(), 'title' => 'Release 0.1.0', 'color' => 'green'],
            ['id' => 2, 'date' => $base->addDays(9)->toDateString(), 'title' => 'Design review', 'color' => 'blue'],
            ['id' => 3, 'date' => $base->addDays(9)->toDateString(), 'title' => 'Standup', 'color' => 'zinc'],
            ['id' => 4, 'date' => $base->addDays(9)->toDateString(), 'title' => 'Retro', 'color' => 'purple'],
            ['id' => 5, 'date' => $base->addDays(9)->toDateString(), 'title' => 'Overflow item', 'color' => 'amber'],
            ['id' => 6, 'date' => $base->addDays(20)->toDateString(), 'title' => 'Deadline', 'color' => 'red'],
        ];
    }

    public function openEvent(string $id): void
    {
        Flux::toast("Open event {$id}");
    }

    public function createOn(string $date): void
    {
        Flux::toast("Create on {$date}");
    }

    public function clearBody(): void
    {
        $this->body = '';
    }

    public function sendNotification(): void
    {
        auth()->user()?->notify(new DemoNotification);
    }

    public function save(): void
    {
        Flux::toast('Saved (demo).');
    }

    public function hasFileManager(): bool
    {
        return class_exists(AssetController::class);
    }

    public function sampleImageUrl(): string
    {
        return VmEngine\UiKit\Http\Controllers\AssetController::url('demo/sample.svg');
    }

    public function render(): View
    {
        $view = $this->view()->title('UI kit demo');
        $layout = config('ui-kit.demo.layout');

        // null: Livewire's livewire.component_layout (the host's app layout).
        return is_string($layout) && $layout !== '' ? $view->layout($layout) : $view;
    }
};
