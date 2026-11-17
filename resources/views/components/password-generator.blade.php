{{--
    Password generator: generate, copy, and (with `model`) set the password on
    Livewire properties.

    <x-ui::password-generator :model="['password', 'password_confirmation']" :length="16" />
--}}
<x-ui::assets.clipboard />
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/password-generator.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<div
    x-data="uiPasswordGenerator({{ $length }}, @js($models()))"
    {{ $attributes->only('class')->class('relative') }}
>
    <input
        type="text"
        readonly
        x-ref="passwordInput"
        placeholder="{{ __('ui-kit::ui.password_placeholder') }}"
        aria-label="{{ __('ui-kit::ui.password_placeholder') }}"
        class="{{ \VmEngine\UiKit\Support\Styles::INPUT }} pe-28 font-mono"
        {{ $attributes->except('class') }}
    />
    <div class="absolute inset-y-0 end-1.5 flex items-center gap-0.5">
        <button
            type="button"
            title="{{ __('ui-kit::ui.generate') }}"
            aria-label="{{ __('ui-kit::ui.generate') }}"
            x-on:click="generate()"
            class="rounded-md p-1.5 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-700 focus-visible:outline-2 focus-visible:outline-accent dark:hover:bg-white/10 dark:hover:text-white"
        >
            <flux:icon
                name="arrow-path"
                variant="mini"
                class="size-4"
            />
        </button>
        @if ($models() !== [])
            <button
                type="button"
                title="{{ __('ui-kit::ui.use_password') }}"
                aria-label="{{ __('ui-kit::ui.use_password') }}"
                x-on:click="setProperty()"
                class="rounded-md p-1.5 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-700 focus-visible:outline-2 focus-visible:outline-accent dark:hover:bg-white/10 dark:hover:text-white"
            >
                <flux:icon
                    name="check"
                    variant="mini"
                    class="size-4"
                />
            </button>
        @endif
        <button
            type="button"
            title="{{ __('ui-kit::ui.copy') }}"
            aria-label="{{ __('ui-kit::ui.copy') }}"
            x-on:click="copyPassword()"
            class="rounded-md p-1.5 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-700 focus-visible:outline-2 focus-visible:outline-accent dark:hover:bg-white/10 dark:hover:text-white"
        >
            <flux:icon
                name="document-duplicate"
                variant="mini"
                class="size-4"
            />
        </button>
    </div>
</div>
