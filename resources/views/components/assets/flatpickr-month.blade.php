{{-- flatpickr monthSelect plugin (window.monthSelectPlugin). Load after <x-ui::assets.flatpickr />. --}}
@assets
    <link rel="stylesheet" href="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('vendor/flatpickr/month-select.css') }}">
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('vendor/flatpickr/month-select.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets
