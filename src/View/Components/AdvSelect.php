<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;
use Illuminate\View\Component;
use InvalidArgumentException;
use VmEngine\UiKit\Concerns\BindsWireModel;
use VmEngine\UiKit\Concerns\SanitizesWireArguments;

/**
 * Searchable single/multi select bound to wire:model: static options, remote
 * search (search-method), label lookup for preset values (load-method), allow-create,
 * and tree indentation from an option's `depth`.
 */
final class AdvSelect extends Component
{
    use BindsWireModel;
    use SanitizesWireArguments;

    public string $placeholder;

    public string $searchLabel;

    /**
     * @param  array<int|string, mixed>|null  $preload  already-selected option rows
     * @param  array<int|string, mixed>  $options  [['value' => …, 'label' => …, 'depth' => 0], …]
     */
    public function __construct(
        public ?string $searchMethod = null,
        public ?string $loadMethod = null,
        public bool $multiple = false,
        ?string $placeholder = null,
        public string $labelField = 'label',
        public string $valueField = 'value',
        public ?array $preload = null,
        public array $options = [],
        public int $minSearchLength = 2,
        public bool $allowCreate = false,
        public bool $autoLoad = true,
        public bool $disabled = false,
        ?string $searchLabel = null,
    ) {
        $this->searchMethod = $this->wireMethod($searchMethod);
        $this->loadMethod = $this->wireMethod($loadMethod);
        $this->placeholder = $placeholder ?? (string) __('ui-kit::ui.select_placeholder');
        $this->searchLabel = $searchLabel ?? (string) __('ui-kit::ui.search');
    }

    public function boundProperty(): string
    {
        return $this->wireModelName() ?? throw new InvalidArgumentException('ui-kit: adv-select needs wire:model.');
    }

    /**
     * The x-data expression (argument order matches uiAdvSelect in adv-select.js).
     */
    public function alpineData(): string
    {
        $arguments = [
            $this->preload ?? [],
            $this->multiple,
            $this->searchMethod,
            $this->loadMethod,
            $this->boundProperty(),
            $this->labelField,
            $this->valueField,
            array_values($this->options),
            $this->minSearchLength,
            $this->isLive(),
            $this->allowCreate,
            $this->autoLoad,
            $this->disabled,
        ];

        return 'uiAdvSelect('.implode(', ', array_map(fn (mixed $argument): string => Js::from($argument)->toHtml(), $arguments)).')';
    }

    public function render(): View
    {
        return view('ui-kit::components.adv-select');
    }
}
