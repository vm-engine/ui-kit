{{--
    Currency: shows "1.250.000,50". With `raw`, wire:model receives "1250000.50".

    <x-ui::currency currency="Rp" raw :decimals="2" wire:model="amount" />

    A value that is all zeros and separators is selected on focus, so typing
    replaces it. Written as replaceAll() chains, not a regex, so it also works
    with Alpine's CSP-safe build.
--}}
@if ($usesRawBinding())
    @assets
        <script
            src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/currency.js') }}"
            nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
        ></script>
    @endassets
@endif

<div class="flex w-full">
    @if ($currency !== '')
        <span
            data-ui-currency-prefix
            class="inline-flex items-center rounded-s-lg border border-e-0 border-zinc-200 bg-zinc-50 px-3 text-sm text-zinc-500 shadow-xs dark:border-white/10 dark:bg-white/5 dark:text-zinc-400"
        >{{ $currency }}</span>
    @endif
    @if ($usesRawBinding())
        <input
            type="text"
            inputmode="decimal"
            x-data="uiCurrency({{ $entangle(live: false) }}, {{ $decimals }}, {{ $syncDelay() ?? 'null' }})"
            x-model="display"
            x-mask:dynamic="$money($input, ',', '.', decimals)"
            x-on:focus="$event.target.value.replaceAll('0', '').replaceAll('.', '').replaceAll(',', '') == '' && $event.target.select()"
            {{ $attributes->whereDoesntStartWith('wire:model')->class([\VmEngine\UiKit\Support\Styles::INPUT, 'pe-3', 'rounded-s-none' => $currency !== '']) }}
        >
    @else
        <input
            type="text"
            inputmode="{{ $decimals > 0 ? 'decimal' : 'numeric' }}"
            x-data
            x-mask:dynamic="$money($input, ',', '.', {{ $decimals }})"
            x-on:focus="$event.target.value.replaceAll('0', '').replaceAll('.', '').replaceAll(',', '') == '' && $event.target.select()"
            {{ $attributes->class([\VmEngine\UiKit\Support\Styles::INPUT, 'pe-3', 'rounded-s-none' => $currency !== '']) }}
        >
    @endif
</div>
