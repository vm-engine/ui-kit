{{-- Jodit (vendored 4.18.4 full build, window.Jodit). One @assets location, so it loads once per page. --}}
@assets
    <link rel="stylesheet" href="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('vendor/jodit/jodit.min.css') }}">
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('vendor/jodit/jodit.min.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets
