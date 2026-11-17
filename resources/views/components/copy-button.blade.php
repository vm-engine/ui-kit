{{--
    Copy button: copies `text`, shows "Copied" for 2s. Works over plain HTTP.

    <x-ui::copy-button text="/storage/report.pdf" />
    <x-ui::copy-button :text="$token" label="Copy token" :icon-only="false" />
--}}
<x-ui::assets.clipboard />
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/copy-button.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<button
    type="button"
    title="{{ $label }}"
    @if ($iconOnly) aria-label="{{ $label }}" @endif
    x-data="uiCopyButton(@js($text), @js($labels()))"
    x-on:click="copy()"
    {{ $attributes->class([
        'inline-flex items-center gap-1.5 rounded-md text-sm font-medium text-zinc-500 hover:text-zinc-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent dark:text-zinc-400 dark:hover:text-white',
        'p-1' => $iconOnly,
    ]) }}
>
    <flux:icon
        name="document-duplicate"
        variant="micro"
        x-show="isIdle()"
    />
    <flux:icon
        name="check"
        variant="micro"
        class="text-green-600 dark:text-green-400"
        x-cloak
        x-show="copied"
    />
    <flux:icon
        name="exclamation-triangle"
        variant="micro"
        class="text-red-600 dark:text-red-400"
        x-cloak
        x-show="failed"
    />
    @unless ($iconOnly)
        <span x-text="buttonText()">{{ $label }}</span>
    @endunless
    <span
        class="sr-only"
        aria-live="polite"
        x-text="announcement()"
    ></span>
</button>
