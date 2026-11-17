{{--
    Steps: wizard progress. Completed steps jump back via go-method(number).

    <x-ui::steps :steps="['Details', 'Media', 'Review']" :current="$step" go-method="goToStep" />
--}}
<ol
    aria-label="{{ __('ui-kit::ui.progress') }}"
    {{ $attributes->class('flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-0') }}
>
    @foreach ($items() as $item)
        <li
            @class(['flex items-center', 'sm:flex-1' => !$loop->last])
            @if ($item['state'] === 'current') aria-current="step" @endif
        >
            @if ($item['clickable'])
                <button
                    type="button"
                    wire:click="{{ $goMethod }}({{ $item['number'] }})"
                    class="flex items-center gap-2 rounded-md text-sm font-medium text-zinc-500 hover:text-zinc-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent dark:text-zinc-400 dark:hover:text-white"
                >
                @else
                    <div @class([
                        'flex items-center gap-2 text-sm font-medium',
                        'text-zinc-900 dark:text-white' => $item['state'] === 'current',
                        'text-zinc-500 dark:text-zinc-400' => $item['state'] !== 'current',
                    ])>
            @endif
            <span @class([
                'flex size-7 shrink-0 items-center justify-center rounded-full text-xs font-semibold',
                'bg-green-500 text-white' => $item['state'] === 'complete',
                'bg-accent text-accent-foreground' => $item['state'] === 'current',
                'bg-zinc-100 text-zinc-500 dark:bg-white/10 dark:text-zinc-400' =>
                    $item['state'] === 'upcoming',
            ])>
                @if ($item['state'] === 'complete')
                    <flux:icon
                        name="check"
                        variant="micro"
                        class="size-4"
                    />
                @else
                    {{ $item['number'] }}
                @endif
            </span>
            <span>{{ $item['label'] }}</span>
            @if ($item['clickable'])
                </button>
            @else
                </div>
            @endif

            @unless ($loop->last)
                <span
                    aria-hidden="true"
                    @class([
                        'mx-3 hidden h-0.5 flex-1 sm:block',
                        'bg-green-500' => $item['state'] === 'complete',
                        'bg-zinc-200 dark:bg-white/10' => $item['state'] !== 'complete',
                    ])
                ></span>
            @endunless
        </li>
    @endforeach
</ol>
