<div>
    @if ($enabled && $this->items)
        <div class="mb-2 flex justify-end">
            <button
                type="button"
                wire:click="markAll"
                class="text-xs text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white"
            >{{ __('ui-kit::ui.mark_all_read') }}</button>
        </div>

        <ul class="space-y-1">
            @forelse ($this->items as $notification)
                <x-ui::notifications.row
                    :notification="$notification"
                    :unread="false"
                    :url="$this->notificationUrl($notification)"
                    :icon="$this->notificationIcon($notification)"
                    :title="$this->renderTitle($notification)"
                    :message="$this->renderMessage($notification)"
                    wire:key="list-{{ $notification->id }}"
                />
            @empty
                <li class="py-6 text-center {{ \VmEngine\UiKit\Support\Styles::MUTED }}">{{ __('ui-kit::ui.no_notifications') }}</li>
            @endforelse
        </ul>

        @if ($this->items->hasPages())
            <div class="mt-4">
                <flux:pagination :paginator="$this->items" />
            </div>
        @endif
    @endif
</div>
