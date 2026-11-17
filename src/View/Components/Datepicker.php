<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;
use Illuminate\View\Component;
use InvalidArgumentException;
use VmEngine\UiKit\Concerns\BindsWireModel;

/**
 * flatpickr date (Y-m-d) or month (Y-m) picker, entangled with wire:model or
 * posted by `name`. Never falls back to the browser's native date input.
 */
final class Datepicker extends Component
{
    use BindsWireModel;

    /**
     * @param  array<string, mixed>  $options  extra flatpickr options
     */
    public function __construct(
        public string $mode = 'date',
        public array $options = [],
    ) {
        if (! in_array($mode, ['date', 'month'], true)) {
            throw new InvalidArgumentException("ui-kit: datepicker mode must be 'date' or 'month'.");
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function flatpickrOptions(): array
    {
        // disableMobile: keep flatpickr's own UI on phones (no native date input).
        return array_merge(['allowInput' => true, 'disableMobile' => true], $this->options);
    }

    /**
     * The x-data expression. Built here because @js() is not compiled inside
     * the attributes of a component tag such as <flux:input>.
     */
    public function alpineData(): string
    {
        return 'uiDatepicker('.$this->entangle().', '.Js::from($this->flatpickrOptions())->toHtml().', '.Js::from($this->mode)->toHtml().')';
    }

    public function render(): View
    {
        return view('ui-kit::components.datepicker');
    }
}
