{{--
    Datepicker: flatpickr bound to wire:model (or posted by `name`).

    <x-ui::datepicker wire:model.live="expiresAt" :options="['minDate' => 'today']" />
    <x-ui::datepicker mode="month" wire:model="period" /> (submits Y-m)
--}}
<x-ui::assets.flatpickr />
@if ($mode === 'month')
    <x-ui::assets.flatpickr-month />
@endif
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/datepicker.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<div wire:ignore>
    <flux:input
        icon-trailing="calendar"
        autocomplete="off"
        x-data="{{ $alpineData() }}"
        {{ $attributes->whereDoesntStartWith('wire:model') }}
    />
</div>
