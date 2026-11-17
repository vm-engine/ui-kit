// <x-ui::password-generator>: generates a password with crypto.getRandomValues
// (unbiased; works over plain HTTP too), copies it, or sets it on Livewire properties.
{
    const LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
    const UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const NUMBERS = '0123456789';
    const SYMBOLS = '!@#$%^&*()_+-=[]{}|;:,.<>?';

    // Rejection sampling: drop values from the incomplete last block so every index is equally likely.
    const randomIndex = (max) => {
        const limit = Math.floor(0x100000000 / max) * max;
        const buffer = new Uint32Array(1);
        do {
            crypto.getRandomValues(buffer);
        } while (buffer[0] >= limit);
        return buffer[0] % max;
    };

    const pick = (chars) => chars[randomIndex(chars.length)];

    const register = () => {
        Alpine.data('uiPasswordGenerator', (initialLength, models) => ({
            passwordLength: initialLength,

            generatePassword(length) {
                const all = LOWERCASE + UPPERCASE + NUMBERS + SYMBOLS;
                const password = [pick(LOWERCASE), pick(UPPERCASE), pick(NUMBERS), pick(SYMBOLS)];

                for (let i = password.length; i < length; i++) {
                    password.push(pick(all));
                }

                for (let i = password.length - 1; i > 0; i--) {
                    const j = randomIndex(i + 1);
                    [password[i], password[j]] = [password[j], password[i]];
                }

                return password.join('');
            },

            generate() {
                this.$refs.passwordInput.value = this.generatePassword(this.passwordLength);
                this.$refs.passwordInput.dispatchEvent(new Event('input'));
            },

            setProperty() {
                const password = this.$refs.passwordInput.value;
                if (password) {
                    (models || []).forEach((property) => this.$wire.set(property, password));
                    this.$dispatch('password-set');
                }
            },

            async copyPassword() {
                const password = this.$refs.passwordInput.value;
                if (password && await window.uiClipboard.write(password)) {
                    this.$dispatch('password-copied');
                }
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
