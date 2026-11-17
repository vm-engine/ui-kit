{{--
    Tabs: WAI-ARIA tablist (arrows, Home, End). Panels stay in the DOM.

    <x-ui::tabs wire:model.live="tab">
        <x-slot:tabs>
            <x-ui::tabs.tab name="profile" icon="user">Profile</x-ui::tabs.tab>
            <x-ui::tabs.tab name="security" icon="lock-closed">Security</x-ui::tabs.tab>
        </x-slot:tabs>
        <x-ui::tabs.panel name="profile">…</x-ui::tabs.panel>
        <x-ui::tabs.panel name="security">…</x-ui::tabs.panel>
    </x-ui::tabs>
--}}
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/tabs.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<div
    x-data="uiTabs({{ $entangle() }})"
    x-id="['ui-tab', 'ui-panel']"
    {{ $attributes->whereDoesntStartWith('wire:model')->class('w-full [contain:inline-size]') }}
>
    <div
        role="tablist"
        x-ref="tablist"
        class="flex gap-6 overflow-x-auto [box-shadow:inset_0_-1px_0_var(--color-zinc-200)] dark:[box-shadow:inset_0_-1px_0_rgb(255_255_255/0.1)]"
        x-on:keydown.arrow-right.prevent="focusNext()"
        x-on:keydown.arrow-left.prevent="focusPrev()"
        x-on:keydown.home.prevent="focusFirst()"
        x-on:keydown.end.prevent="focusLast()"
    >
        {{ $tabs }}
    </div>

    {{ $slot }}
</div>
