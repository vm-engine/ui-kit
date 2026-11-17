{{--
    Empty state: a centered "nothing here" block for lists, tables and panels.

    Props: title (string), text (string, optional), icon (Heroicon name, default 'inbox').
    Slot: an optional action (button or link).

    <x-ui::empty-state icon="users" title="No members yet" text="Add the first member.">
        <flux:button href="..." variant="primary">New member</flux:button>
    </x-ui::empty-state>
--}}
@props(['title', 'text' => null, 'icon' => 'inbox'])

<div {{ $attributes->class('flex flex-col items-center justify-center px-6 py-12 text-center') }}>
    <span
        class="flex size-12 items-center justify-center rounded-full bg-zinc-100 text-zinc-400 dark:bg-white/10 dark:text-zinc-500"
    >
        <flux:icon
            :name="$icon"
            class="size-6"
        />
    </span>
    <flux:heading class="mt-3">{{ $title }}</flux:heading>
    @if (filled($text))
        <flux:text class="mt-1 max-w-sm">{{ $text }}</flux:text>
    @endif
    @if ($slot->isNotEmpty())
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
