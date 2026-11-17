{{-- Unconditional root: Livewire needs one root element even when disabled. --}}
<div
    @if ($enabled)
        class="relative"
        x-data="{ open: false }"
        x-on:keydown.escape.window="open = false"
        @if ($this->pollSeconds()) wire:poll.{{ $this->pollSeconds() }}s="refresh" @endif
    @endif
>
    @if ($enabled)
        <flux:button
            variant="ghost"
            size="sm"
            icon="bell"
            aria-label="{{ __('ui-kit::ui.notifications') }}"
            x-bind:aria-expanded="open ? 'true' : 'false'"
            x-on:click="open = ! open; if (open) $wire.openPanel()"
        />
        @if ($this->unreadCount > 0)
            <span
                data-ui-unread-count="{{ $this->unreadCount }}"
                aria-label="{{ trans_choice('ui-kit::ui.unread', $this->unreadCount) }}"
                class="pointer-events-none absolute -end-1 -top-1 min-w-5 rounded-full bg-red-500 px-1 text-center text-xs leading-5 font-medium text-white"
            >{{ $this->unreadCount > 9 ? '9+' : $this->unreadCount }}</span>
        @endif

        <div
            x-show="open"
            x-cloak
            x-transition.opacity
            x-on:click.outside="open = false"
            class="{{ \VmEngine\UiKit\Support\Styles::POPOVER }} absolute end-0 z-50 mt-2 w-80"
        >
            <div class="flex items-center justify-between gap-2 px-2 py-1.5">
                <span class="text-sm font-medium text-zinc-800 dark:text-white">{{ __('ui-kit::ui.notifications') }}</span>
                <button
                    type="button"
                    wire:click="markAll"
                    class="text-xs text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white"
                >{{ __('ui-kit::ui.mark_all_read') }}</button>
            </div>

            <ul class="max-h-96 overflow-y-auto">
                @forelse ($this->items as $notification)
                    <x-ui::notifications.row
                        :notification="$notification"
                        :unread="$this->isVisuallyUnread((string) $notification->id, $notification->read_at)"
                        :url="$this->notificationUrl($notification)"
                        :icon="$this->notificationIcon($notification)"
                        :title="$this->renderTitle($notification)"
                        :message="$this->renderMessage($notification)"
                        wire:key="bell-{{ $notification->id }}"
                    />
                @empty
                    <li class="px-2 py-6 text-center {{ \VmEngine\UiKit\Support\Styles::MUTED }}">{{ __('ui-kit::ui.no_notifications') }}</li>
                @endforelse
            </ul>

            <div class="border-t border-zinc-200 px-2 pt-1.5 dark:border-zinc-600">
                <button
                    type="button"
                    x-on:click="open = false; $flux.modal('ui-notifications').show(); $dispatch('ui-notifications-opened')"
                    class="w-full rounded-md px-2 py-1.5 text-center text-sm text-zinc-700 hover:bg-zinc-50 dark:text-zinc-200 dark:hover:bg-zinc-600"
                >{{ __('ui-kit::ui.view_all') }}</button>
            </div>
        </div>
    @endif
</div>
