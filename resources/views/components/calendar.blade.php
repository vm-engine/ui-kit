{{--
    Calendar: month grid (md+) and agenda list (small screens).

    <x-ui::calendar :month="$month" :events="$events" nav-method="setMonth"
        event-method="openEvent" day-method="createOn" week-start="monday" />
--}}
<div {{ $attributes->class('w-full space-y-3') }}>
    <div class="flex items-center justify-between gap-3">
        <flux:heading size="lg">{{ $monthLabel() }}</flux:heading>
        @if ($navMethod)
            <div class="flex items-center gap-1">
                <flux:button
                    size="sm"
                    variant="ghost"
                    icon="chevron-left"
                    aria-label="{{ __('ui-kit::ui.previous_month') }}"
                    wire:click="{{ $navMethod }}('{{ $previousMonth() }}')"
                />
                <flux:button
                    size="sm"
                    variant="ghost"
                    wire:click="{{ $navMethod }}('{{ $currentMonth() }}')"
                >{{ __('ui-kit::ui.today') }}</flux:button>
                <flux:button
                    size="sm"
                    variant="ghost"
                    icon="chevron-right"
                    aria-label="{{ __('ui-kit::ui.next_month') }}"
                    wire:click="{{ $navMethod }}('{{ $nextMonth() }}')"
                />
            </div>
        @endif
    </div>

    <div
        role="grid"
        class="hidden overflow-hidden rounded-lg border border-zinc-200 md:grid md:grid-cols-7 dark:border-white/10"
    >
        @foreach ($weekdayLabels() as $weekday)
            <div
                role="columnheader"
                class="border-b border-zinc-200 bg-zinc-50 px-2 py-1.5 text-xs font-medium text-zinc-500 dark:border-white/10 dark:bg-white/5 dark:text-zinc-400"
            >{{ $weekday }}</div>
        @endforeach

        @foreach ($weeks() as $week)
            @foreach ($week as $cell)
                <div
                    role="gridcell"
                    @class([
                        'flex min-h-24 flex-col gap-1 border-b border-e border-zinc-200 p-1.5 dark:border-white/10',
                        'bg-zinc-50/60 dark:bg-white/[2%]' => !$cell['inMonth'],
                    ])
                >
                    @if ($dayMethod)
                        <button
                            type="button"
                            wire:click="{{ $dayMethod }}('{{ $cell['date'] }}')"
                            @class([
                                'flex size-6 items-center justify-center self-start rounded-full text-xs hover:bg-zinc-100 focus-visible:outline-2 focus-visible:outline-accent dark:hover:bg-white/10',
                                'bg-accent font-semibold text-accent-foreground hover:bg-accent' =>
                                    $cell['isToday'],
                                'text-zinc-700 dark:text-zinc-300' => !$cell['isToday'] && $cell['inMonth'],
                                'text-zinc-400 dark:text-zinc-500' =>
                                    !$cell['isToday'] && !$cell['inMonth'],
                            ])
                        >{{ $cell['day'] }}</button>
                    @else
                        <span @class([
                            'flex size-6 items-center justify-center self-start rounded-full text-xs',
                            'bg-accent font-semibold text-accent-foreground' => $cell['isToday'],
                            'text-zinc-700 dark:text-zinc-300' => !$cell['isToday'] && $cell['inMonth'],
                            'text-zinc-400 dark:text-zinc-500' =>
                                !$cell['isToday'] && !$cell['inMonth'],
                        ])>{{ $cell['day'] }}</span>
                    @endif

                    @foreach ($cell['events'] as $event)
                        @if ($eventMethod)
                            <button
                                type="button"
                                title="{{ $event['title'] }}"
                                wire:click="{{ $eventMethod }}('{{ $event['id'] }}')"
                                class="truncate rounded px-1.5 py-0.5 text-start text-xs font-medium focus-visible:outline-2 focus-visible:outline-accent {{ $event['chip'] }}"
                            >{{ $event['title'] }}</button>
                        @else
                            <span
                                title="{{ $event['title'] }}"
                                class="truncate rounded px-1.5 py-0.5 text-xs font-medium {{ $event['chip'] }}"
                            >{{ $event['title'] }}</span>
                        @endif
                    @endforeach

                    @if ($cell['more'] > 0)
                        @if ($dayMethod)
                            <button
                                type="button"
                                wire:click="{{ $dayMethod }}('{{ $cell['date'] }}')"
                                class="self-start text-xs text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white"
                            >{{ __('ui-kit::ui.more', ['count' => $cell['more']]) }}</button>
                        @else
                            <span
                                class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('ui-kit::ui.more', ['count' => $cell['more']]) }}</span>
                        @endif
                    @endif
                </div>
            @endforeach
        @endforeach
    </div>

    <ol class="space-y-3 md:hidden">
        @forelse ($agenda() as $day)
            <li class="space-y-1">
                <div class="text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ $day['label'] }}</div>
                @foreach ($day['events'] as $event)
                    @if ($eventMethod)
                        <button
                            type="button"
                            wire:click="{{ $eventMethod }}('{{ $event['id'] }}')"
                            class="block w-full truncate rounded px-2 py-1 text-start text-sm font-medium {{ $event['chip'] }}"
                        >{{ $event['title'] }}</button>
                    @else
                        <span
                            class="block truncate rounded px-2 py-1 text-sm font-medium {{ $event['chip'] }}">{{ $event['title'] }}</span>
                    @endif
                @endforeach
            </li>
        @empty
            <li class="{{ \VmEngine\UiKit\Support\Styles::MUTED }}">{{ __('ui-kit::ui.no_events') }}</li>
        @endforelse
    </ol>
</div>
