{{-- flatpickr (vendored 4.6.13). One @assets location, so it loads once per page. --}}
@assets
    <link rel="stylesheet" href="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('vendor/flatpickr/flatpickr.min.css') }}">
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('vendor/flatpickr/flatpickr.min.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets
