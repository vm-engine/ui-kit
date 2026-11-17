{{--
    Colour picker: "#rrggbb", swatches + native picker + hex field.

    <x-ui::color-picker wire:model="form.color" :swatches="['#ef4444', '#3b82f6', '#10b981']" />
--}}
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/color-picker.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<div
    wire:ignore
    x-data="uiColorPicker({{ $entangle() }})"
    {{ $attributes->whereDoesntStartWith('wire:model')->class('flex flex-wrap items-center gap-3') }}
>
    @if ($allowCustom)
        <div class="flex items-center gap-2">
            <input
                type="color"
                aria-label="{{ __('ui-kit::ui.pick_color') }}"
                class="size-10 cursor-pointer rounded-lg border border-zinc-200 bg-white p-1 dark:border-white/10 dark:bg-white/10"
                x-bind:value="nativeValue()"
                x-on:input="fromNative($event)"
            >
            <input
                type="text"
                maxlength="7"
                placeholder="#000000"
                spellcheck="false"
                aria-label="{{ __('ui-kit::ui.hex_color') }}"
                class="{{ \VmEngine\UiKit\Support\Styles::INPUT }} w-28 pe-3 font-mono"
                x-model="value"
            >
        </div>
    @endif

    @if ($swatches !== [])
        <div class="flex flex-wrap gap-2">
            @foreach ($swatches as $swatch)
                <button
                    type="button"
                    title="{{ $swatch }}"
                    aria-label="{{ $swatch }}"
                    style="background-color: {{ $swatch }}"
                    class="size-7 rounded-full border border-black/10 ring-offset-2 ring-offset-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent dark:border-white/20 dark:ring-offset-zinc-800"
                    x-bind:class="isSelected(@js($swatch)) ? 'ring-2 ring-accent' : ''"
                    x-bind:aria-pressed="isSelected(@js($swatch)) ? 'true' : 'false'"
                    x-on:click="pick(@js($swatch))"
                ></button>
            @endforeach
        </div>
    @endif
</div>
