{{--
    Rich-text editor (Jodit), bound to wire:model. Sanitise the HTML before rendering it.

    <x-ui::rte wire:model="form.body" :height="400" />
    <x-ui::rte wire:model="body" extension="file-manager" />   (see window.uiRte.extend in the README)
--}}
<x-ui::assets.jodit />
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/rte.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<div
    wire:ignore
    x-data="uiRte(@js($config()))"
    {{ $attributes->whereDoesntStartWith('wire:model') }}
>
    <textarea
        id="{{ $inputId() }}"
        x-ref="textarea"
        rows="10"
        class="{{ \VmEngine\UiKit\Support\Styles::FIELD }} py-2 pe-3"
    ></textarea>
</div>
