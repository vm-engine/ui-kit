{{-- One notification row, shared by the bell dropdown and the panel list. --}}
@props(['notification', 'unread' => false, 'url' => null, 'icon' => 'bell', 'title' => '', 'message' => ''])

<li {{ $attributes->class('group flex items-start gap-2 rounded-md px-2 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-600') }}>
    <flux:icon :name="$icon" variant="mini" class="mt-0.5 size-4 shrink-0 text-zinc-400" />
    @if ($url !== null)
        <a href="{{ $url }}" class="min-w-0 flex-1">
    @else
        <div class="min-w-0 flex-1">
    @endif
        <p @class(['text-sm', 'font-medium text-zinc-900 dark:text-white' => $unread, 'text-zinc-700 dark:text-zinc-300' => ! $unread])>{{ $title }}</p>
        @if ($message !== '')
            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $message }}</p>
        @endif
    @if ($url !== null)
        </a>
    @else
        </div>
    @endif
    @if ($unread)
        <button
            type="button"
            wire:click="markOne('{{ $notification->id }}')"
            aria-label="{{ __('ui-kit::ui.mark_read') }}"
            class="mt-1.5 size-2 shrink-0 rounded-full bg-accent"
        ></button>
    @endif
</li>
