// window.uiClipboard.write(text): navigator.clipboard on HTTPS/localhost, else the
// execCommand('copy') fallback, so copying also works over plain HTTP.
window.uiClipboard = window.uiClipboard || {
    async write(value) {
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(value);
                return true;
            }
        } catch (e) {
            // Permission denied or unsupported: fall through to the legacy path.
        }

        const area = document.createElement('textarea');
        area.value = value;
        area.setAttribute('readonly', '');
        area.style.position = 'fixed';
        area.style.opacity = '0';
        document.body.appendChild(area);
        area.select();

        let ok = false;
        try {
            ok = document.execCommand('copy');
        } catch (e) {
            ok = false;
        }
        document.body.removeChild(area);

        return ok;
    },
};
