{{--
    Split pane: aside + main. Resizable panes remember their width per storage-key.

    <x-ui::split-pane aside-width="20rem" resizable storage-key="folders">
        <x-slot:aside>…tree…</x-slot:aside>
        …main…
    </x-ui::split-pane>
--}}
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/split-pane.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<div
    x-data="uiSplitPane(@js($storageKey), @js($asideWidth))"
    style="--ui-split-aside: {{ $asideWidth }}"
    x-bind:style="gridStyle()"
    {{ $attributes->class([
        'flex flex-col gap-4 lg:grid lg:gap-0',
        'lg:grid-cols-[var(--ui-split-aside)_minmax(0,1fr)]' => !$resizable,
        'lg:grid-cols-[var(--ui-split-aside)_0.5rem_minmax(0,1fr)]' => $resizable,
    ]) }}
>
    <flux:button
        type="button"
        size="sm"
        variant="filled"
        icon="bars-3"
        class="self-start lg:hidden"
        x-bind:aria-expanded="asideOpen ? 'true' : 'false'"
        x-on:click="toggleAside()"
    >{{ $toggleLabel }}</flux:button>

    <aside
        class="min-w-0"
        x-bind:class="asideOpen ? '' : 'max-lg:hidden'"
    >{{ $aside ?? '' }}</aside>

    @if ($resizable)
        <div
            role="separator"
            aria-orientation="vertical"
            aria-label="{{ __('ui-kit::ui.resize_panel') }}"
            tabindex="0"
            class="hidden cursor-col-resize bg-zinc-100 transition-colors hover:bg-zinc-200 focus-visible:bg-zinc-300 focus-visible:outline-none lg:block dark:bg-white/5 dark:hover:bg-white/15 dark:focus-visible:bg-white/20"
            x-on:pointerdown.prevent="startDrag()"
            x-on:keydown.arrow-left.prevent="nudge(-16)"
            x-on:keydown.arrow-right.prevent="nudge(16)"
        ></div>
    @endif

    <div class="min-w-0">{{ $slot }}</div>
</div>
