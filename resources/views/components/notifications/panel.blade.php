{{-- Companion to <livewire:ui::notifications.bell />: place both in the layout. A separate embed
     because Livewire 4 can't nest a namespaced child inside another component's template. The
     list is lazy, so it loads (and marks rows read) only once the flyout is actually open. --}}
@if (auth()->check() && \VmEngine\UiKit\Support\NotificationGate::enabled())
    <flux:modal name="ui-notifications" variant="flyout" class="w-full md:w-[28rem]">
        <div class="space-y-4">
            <flux:heading size="lg">{{ __('ui-kit::ui.notifications') }}</flux:heading>
            <livewire:ui::notifications.list lazy />
        </div>
    </flux:modal>
@endif
