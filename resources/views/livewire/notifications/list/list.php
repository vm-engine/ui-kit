<?php

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use VmEngine\UiKit\Notifications\InteractsWithNotifications;
use VmEngine\UiKit\Support\NotificationGate;

/**
 * <livewire:ui::notifications.list lazy />: the paginated list inside
 * <x-ui::notifications.panel />. Rendering a page marks that page read, so it is
 * loaded lazily, only once the panel is opened.
 */
new class extends Component
{
    use InteractsWithNotifications;
    use WithPagination;

    #[Locked]
    public bool $enabled = false;

    public function mount(): void
    {
        $this->enabled = auth()->check() && NotificationGate::enabled();
    }

    #[Computed]
    public function items(): ?LengthAwarePaginator
    {
        if (! $this->enabled) {
            return null;
        }

        $items = auth()->user()->notifications()->latest()
            ->paginate((int) config('ui-kit.notifications.per_page', 15), pageName: 'notifications');

        $items->getCollection()->whereNull('read_at')->each->markAsRead();

        return $items;
    }

    public function markOne(string $id): void
    {
        $this->markNotificationRead($id);
        unset($this->items);
    }

    public function markAll(): void
    {
        $this->markAllNotificationsRead();
        unset($this->items);
    }

    public function placeholder(): string
    {
        return Blade::render('<div class="flex justify-center py-8"><flux:icon.loading /></div>');
    }
};
