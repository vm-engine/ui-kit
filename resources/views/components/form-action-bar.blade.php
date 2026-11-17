{{--
    Form action bar: sticky save bar for long forms on their own page.
    Slot: extra buttons, rendered before the save button.

    <x-ui::form-action-bar action="save" :cancel-url="route('users.index')" />
--}}
<div
    {{ $attributes->class('sticky bottom-0 z-10 flex items-center justify-between gap-4 border-t border-zinc-200 bg-white/90 px-4 py-3 backdrop-blur dark:border-white/10 dark:bg-zinc-800/90') }}>
    @if ($hintText() !== null)
        <flux:text>
            @if ($showsRequiredMarker())
                <span
                    class="text-red-500"
                    aria-hidden="true"
                >*</span>
            @endif
            {{ $hintText() }}
        </flux:text>
    @else
        <span></span>
    @endif

    <div class="flex items-center gap-2">
        @if (filled($cancelUrl))
            <flux:button
                :href="$cancelUrl"
                wire:navigate
                variant="ghost"
                icon="x-mark"
            >{{ __('ui-kit::ui.cancel') }}</flux:button>
        @endif
        {{ $slot }}
        <flux:button
            type="button"
            variant="primary"
            icon="check"
            wire:click="{{ $safeAction }}"
            wire:target="{{ $saveTarget ?? $safeAction }}"
        >{{ $saveLabel ?? __('ui-kit::ui.save') }}</flux:button>
    </div>
</div>
