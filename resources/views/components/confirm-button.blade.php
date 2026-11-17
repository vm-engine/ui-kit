{{--
    Confirm button: opens <x-ui::confirm-dialog> (place it once in the layout) and,
    on confirm, calls `method` on the surrounding Livewire component.

    <x-ui::confirm-button method="delete" :token="$user->delete_token" :label="__('Delete')"
        :title="__('Delete user?')" :message="__('This cannot be undone.')" />

    Flux props are passed explicitly (:icon, :color): Blaze folds flux:button into
    this view, and this component's own $icon/$color would leak into it otherwise.
--}}
@if ($labelled)
    <flux:button
        type="button"
        size="sm"
        :variant="$color === 'red' ? 'danger' : 'primary'"
        :color="null"
        :icon="$icon"
        {{ $attributes->merge($buttonAttributes()) }}
    >{{ $label }}</flux:button>
@else
    <flux:tooltip :content="$label">
        <flux:button
            type="button"
            size="sm"
            variant="ghost"
            square
            :color="null"
            :icon="null"
            aria-label="{{ $label }}"
            {{ $attributes->merge($buttonAttributes()) }}
        >
            <flux:icon
                :name="$icon"
                variant="mini"
                @class([
                    'size-4',
                    'text-red-600 dark:text-red-400' => $color === 'red',
                ])
            />
        </flux:button>
    </flux:tooltip>
@endif
