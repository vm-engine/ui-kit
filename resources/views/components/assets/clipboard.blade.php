{{-- Shared clipboard helper (window.uiClipboard). One @assets location, so it loads once per page. --}}
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/clipboard.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets
