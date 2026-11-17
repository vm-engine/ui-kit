// <x-ui::currency raw>: the input shows "1.250.000,50" (',' decimal, '.' thousands) while the
// entangled wire:model value stays a plain decimal string "1250000.50".
{
    const uiCurrencyParse = (text) => {
        let digits = String(text ?? '').trim();
        if (digits === '') return '';
        const negative = digits.startsWith('-');
        digits = digits.replace(/\./g, '').replace(',', '.').replace(/[^\d.]/g, '');
        if (digits.endsWith('.')) digits = digits.slice(0, -1);
        if (digits === '') return '';
        return (negative ? '-' : '') + digits;
    };

    const uiCurrencyFormat = (raw, decimals) => {
        let value = String(raw ?? '').trim();
        if (value === '' || Number.isNaN(Number(value))) return '';
        const negative = value.startsWith('-');
        const [whole, fraction = ''] = value.replace('-', '').split('.');
        const grouped = (whole.replace(/^0+(?=\d)/, '') || '0').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        const cents = fraction.slice(0, decimals).replace(/0+$/, '');
        return (negative ? '-' : '') + grouped + (cents !== '' ? ',' + cents : '');
    };

    const register = () => {
        // The entangle is always deferred: entangling with .live would send a $set request on every keystroke.
        // A live model (syncDelay set) is sent with one $commit once typing pauses, like wire:model.live.
        Alpine.data('uiCurrency', (value, decimals, syncDelay) => ({
            value: value,
            decimals: decimals,
            display: '',
            syncTimer: null,
            init() {
                this.display = uiCurrencyFormat(this.value, this.decimals);
                this.$watch('display', (text) => {
                    const raw = uiCurrencyParse(text);
                    if (raw === String(this.value ?? '')) return;
                    this.value = raw;
                    if (syncDelay === null) return;
                    clearTimeout(this.syncTimer);
                    this.syncTimer = setTimeout(() => this.$wire.$commit(), syncDelay);
                });
                // Server-side changes (reset after save, a value set by an action): show them formatted.
                this.$watch('value', (raw) => {
                    const shown = uiCurrencyParse(this.display);
                    const same = shown === '' ? String(raw ?? '') === '' : Number(shown) === Number(raw);
                    if (!same) this.display = uiCurrencyFormat(raw, this.decimals);
                });
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
