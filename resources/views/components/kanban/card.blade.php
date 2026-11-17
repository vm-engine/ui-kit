<div
    @if ($id) wire:sort:item="{{ $id }}" @endif
    {{ $attributes->class('flex items-start gap-2 rounded-lg border border-zinc-200 bg-white p-3 text-sm text-zinc-800 shadow-xs dark:border-white/10 dark:bg-zinc-800 dark:text-zinc-100') }}
>
    <span
        wire:sort:handle
        role="button"
        tabindex="-1"
        aria-label="{{ __('ui-kit::ui.drag') }}"
        class="mt-0.5 cursor-grab text-zinc-400 active:cursor-grabbing"
    >
        <flux:icon name="bars-2" variant="micro" class="size-4" />
    </span>
    <div class="min-w-0 flex-1">{{ $slot }}</div>
</div>
