{{--
    Lightbox: full-screen image viewer. Focus moves to the close button and is
    returned to the opener on close.

    <x-ui::lightbox name="preview" />
    <button x-on:click="$dispatch('open-lightbox-preview', { url: '/img.jpg', alt: 'Cover' })">…</button>
--}}
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/lightbox.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<div
    x-data="uiLightbox"
    x-on:open-lightbox-{{ $name }}.window="open($event.detail)"
    x-on:close-lightbox-{{ $name }}.window="close()"
    {{ $attributes }}
>
    <template x-teleport="body">
        <div
            x-show="show"
            x-cloak
            x-transition.opacity
            role="dialog"
            aria-modal="true"
            aria-label="{{ __('ui-kit::ui.image_preview') }}"
            class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/80 p-4"
            x-on:click="close()"
            x-on:keydown.escape.window="show && close()"
            x-on:keydown.tab="trap($event)"
        >
            <div
                class="relative"
                x-on:click.stop
            >
                <button
                    type="button"
                    x-ref="close"
                    aria-label="{{ __('ui-kit::ui.close') }}"
                    class="absolute -end-3 -top-3 flex size-8 items-center justify-center rounded-full bg-white text-zinc-800 shadow-lg hover:bg-zinc-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                    x-on:click="close()"
                >
                    <flux:icon
                        name="x-mark"
                        variant="mini"
                        class="size-4"
                    />
                </button>
                <img
                    x-bind:src="url"
                    x-bind:alt="alt"
                    class="max-h-[85vh] max-w-[85vw] rounded-lg object-contain shadow-2xl"
                >
            </div>
        </div>
    </template>
</div>
