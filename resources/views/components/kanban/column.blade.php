@aware(['sortMethod' => null, 'group' => 'kanban'])

<section {{ $attributes->class('flex w-72 shrink-0 flex-col gap-3 rounded-lg bg-zinc-50 p-3 dark:bg-white/5') }}>
    <header class="flex items-center gap-2">
        <span class="size-2 shrink-0 rounded-full {{ $dotClass() }}" aria-hidden="true"></span>
        <h4 class="flex-1 truncate text-sm font-medium text-zinc-800 dark:text-white">{{ $label }}</h4>
        @if ($count !== null)
            <span class="rounded-md bg-zinc-200/70 px-1.5 text-xs font-medium text-zinc-600 dark:bg-white/10 dark:text-zinc-300">{{ $count }}</span>
        @endif
    </header>
    <div
        class="flex min-h-12 flex-col gap-2"
        @if ($sortMethod && $id)
            wire:sort="{{ $sortMethod }}"
            wire:sort:group="{{ $group }}"
            wire:sort:group-id="{{ $id }}"
        @endif
    >
        {{ $slot }}
        @unless ($slot->hasActualContent())
            <div class="rounded-md border border-dashed border-zinc-300 px-3 py-4 text-center text-sm text-zinc-500 dark:border-white/15 dark:text-zinc-400" wire:sort:ignore>{{ $emptyText }}</div>
        @endunless
    </div>
</section>
