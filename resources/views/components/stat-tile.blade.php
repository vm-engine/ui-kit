{{--
    Stat tile: one metric. Props match Synapse (see StatTile.php); colours are
    zinc, blue, indigo, purple, red, orange, green. Slots: default (extra content),
    label / value / sublabel (override the props with rich content).

    <x-ui::stat-tile icon="document-text" color="blue" label="Total invoices" value="2,613" />
    <x-ui::stat-tile variant="card" icon="clock" color="red" tinted label="Stuck" value="4,277" sublabel="> 30 min old" />
--}}
@if ($variant === 'card')
    <{{ $tag() }}
        @if (filled($href)) href="{{ $href }}" @endif
        {{ $attributes->class([
            'relative flex flex-col gap-1 overflow-hidden rounded-xl border p-4 transition-shadow',
            $tintClasses(),
            'cursor-pointer hover:shadow-md' => $isClickable(),
        ]) }}
    >
        @if ($iconPosition === 'left' && $icon)
            <div class="flex items-center gap-4">
                <div class="{{ $iconClasses() }}">
                    <flux:icon
                        :name="$icon"
                        variant="outline"
                        class="size-6"
                    />
                </div>
                <div class="min-w-0 flex-1">
                    @if (filled($label))
                        <div
                            class="text-xs font-semibold uppercase tracking-wide {{ $labelClasses() }}">
                            {{ $label }}</div>
                    @endif
                    @if (filled($value))
                        <div class="mt-1 text-2xl font-bold {{ $valueClasses() }}">
                            {{ $value }}</div>
                    @endif
                    @if (filled($sublabel))
                        <div class="mt-1 text-xs {{ $sublabelClasses() }}">
                            {{ $sublabel }}</div>
                    @endif
                    {{ $slot }}
                </div>
            </div>
        @else
            @if ($iconPosition === 'background' && $icon)
                <flux:icon
                    :name="$icon"
                    variant="outline"
                    class="pointer-events-none absolute -bottom-3 -end-3 size-20 opacity-10 {{ $backgroundIconColor() }}"
                />
            @endif

            <div class="flex items-start justify-between gap-2">
                @if (filled($label))
                    <div
                        class="text-xs font-semibold uppercase tracking-wide {{ $labelClasses() }}">
                        {{ $label }}</div>
                @endif
                @if ($iconPosition === 'title' && $icon)
                    <span class="{{ $iconClasses('rounded-full size-8') }}">
                        <flux:icon
                            :name="$icon"
                            variant="mini"
                            class="size-4"
                        />
                    </span>
                @endif
            </div>

            @if (filled($value))
                <div class="mt-1 text-2xl font-bold {{ $valueClasses() }}">
                    {{ $value }}</div>
            @endif
            @if (filled($sublabel))
                <div class="mt-1 text-xs {{ $sublabelClasses() }}">
                    {{ $sublabel }}</div>
            @endif

            {{ $slot }}
        @endif
        </{{ $tag() }}>
    @else
        <div
            {{ $attributes->class([
                'flex items-center gap-4 p-4',
                'rounded-xl border border-zinc-200 bg-white dark:border-white/10 dark:bg-white/10' => $bordered,
                'rounded-lg bg-zinc-50 dark:bg-white/5' => !$bordered,
            ]) }}>
            @if ($icon)
                <div class="{{ $iconClasses() }}">
                    <flux:icon
                        :name="$icon"
                        variant="outline"
                        class="size-6"
                    />
                </div>
            @endif

            <div class="min-w-0 flex-1">
                @if (filled($label))
                    <div class="text-sm font-medium {{ $labelClasses() }}">{{ $label }}</div>
                @endif
                @if (filled($value))
                    <div
                        class="mt-1 text-lg font-semibold {{ $valueClasses() }}">
                        {{ $value }}</div>
                @endif
                {{ $slot }}
            </div>
        </div>
@endif
