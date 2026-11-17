<?php

use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use VmEngine\UiKit\Notifications\InteractsWithNotifications;
use VmEngine\UiKit\Support\NotificationGate;

/**
 * <livewire:ui::notifications.bell />: unread badge, latest-N dropdown, polling
 * for new notifications (toasting those flagged `toast`). Pair it with
 * <x-ui::notifications.panel /> for the full list.
 */
new class extends Component
{
    use InteractsWithNotifications;

    #[Locked]
    public bool $enabled = false;

    /** @var list<string> */
    #[Locked]
    public array $visuallyUnreadIds = [];

    #[Locked]
    public string $lastCheckedAt = '';

    public function mount(): void
    {
        $this->enabled = auth()->check() && NotificationGate::enabled();
        $this->lastCheckedAt = now()->toDateTimeString();
    }

    #[Computed]
    public function unreadCount(): int
    {
        return $this->enabled ? auth()->user()->unreadNotifications()->count() : 0;
    }

    #[Computed]
    public function items(): Collection
    {
        if (! $this->enabled) {
            return collect();
        }

        return auth()->user()->notifications()->latest()
            ->take((int) config('ui-kit.notifications.dropdown_limit', 5))->get();
    }

    public function pollSeconds(): ?int
    {
        $seconds = config('ui-kit.notifications.poll_seconds');

        return $this->enabled && is_numeric($seconds) && (int) $seconds > 0 ? (int) $seconds : null;
    }

    public function isVisuallyUnread(string $id, mixed $readAt): bool
    {
        return $readAt === null || in_array($id, $this->visuallyUnreadIds, true);
    }

    public function openPanel(): void
    {
        $unread = $this->items->whereNull('read_at');
        $this->visuallyUnreadIds = $unread->pluck('id')->map(fn (mixed $id): string => (string) $id)->values()->all();
        $unread->each->markAsRead();
        unset($this->unreadCount, $this->items);
    }

    public function markOne(string $id): void
    {
        $this->markNotificationRead($id);
        $this->visuallyUnreadIds = array_values(array_diff($this->visuallyUnreadIds, [$id]));
        unset($this->unreadCount, $this->items);
    }

    public function markAll(): void
    {
        $this->markAllNotificationsRead();
        $this->visuallyUnreadIds = [];
        unset($this->unreadCount, $this->items);
    }

    public function refresh(): void
    {
        if (! $this->enabled) {
            return;
        }

        // ponytail: second-resolution cursor (>=): a notification created in the same second
        // as the last check can toast twice. Harmless at a 15s poll; switch to a last-seen-id
        // cursor if duplicates ever matter.
        $new = auth()->user()->notifications()->where('created_at', '>=', $this->lastCheckedAt)->get();

        foreach ($new as $notification) {
            if (($notification->data['toast'] ?? false) === true) {
                Flux::toast(text: $this->renderMessage($notification), heading: $this->renderTitle($notification));
            }
        }

        $this->lastCheckedAt = now()->toDateTimeString();
        unset($this->unreadCount, $this->items);
    }
};
