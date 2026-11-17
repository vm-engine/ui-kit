{{--
    Per-page selector: binds the host component's $perPage (10/15/25/50) and
    shows the paginator's links beside it.

    <x-ui::per-page-selector :paginator="$users" />
--}}
<div {{ $attributes->class('flex flex-wrap items-center gap-3') }}>
    <div class="w-20 flex-none">
        {{-- :label="null": the component's own $label must not leak into flux:select (Blaze folding). --}}
        <flux:select
            wire:model.live="perPage"
            size="sm"
            :label="null"
            aria-label="{{ $ariaLabel() }}"
        >
            @foreach (\VmEngine\UiKit\View\Components\PerPageSelector::SIZES as $size)
                <flux:select.option value="{{ $size }}">{{ $size }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>
    <div class="min-w-0 flex-1 basis-64">
        <flux:pagination :paginator="$paginator" />
    </div>
</div>
