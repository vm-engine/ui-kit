<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use InvalidArgumentException;
use VmEngine\UiKit\Concerns\BindsWireModel;
use VmEngine\UiKit\Concerns\SanitizesWireArguments;

/**
 * Jodit rich-text editor bound to a Livewire property (deferred, like wire:model).
 * Falls back to a plain textarea if Jodit fails to load. ui-kit does not sanitise
 * the HTML: purify it before rendering it (e.g. mews/purifier).
 */
final class Rte extends Component
{
    use BindsWireModel;
    use SanitizesWireArguments;

    public ?string $extension;

    /**
     * @param  list<string>|null  $buttons  Jodit toolbar buttons; the default set when null
     * @param  string|null  $extension  name registered with window.uiRte.extend()
     * @param  array<string, mixed>  $extensionConfig  handed to that extension
     */
    public function __construct(
        public int $height = 360,
        public ?array $buttons = null,
        ?string $extension = null,
        public array $extensionConfig = [],
    ) {
        $this->extension = $this->safeKey($extension);
    }

    public function field(): string
    {
        return $this->wireModelName() ?? throw new InvalidArgumentException('ui-kit: rte needs wire:model.');
    }

    public function inputId(): string
    {
        return 'rte-'.str_replace(['.', '[', ']'], '-', $this->field());
    }

    /**
     * @return array<string, mixed>
     */
    public function config(): array
    {
        $config = ['field' => $this->field(), 'height' => $this->height];

        if ($this->buttons !== null) {
            $config['buttons'] = $this->buttons;
        }

        if ($this->extension !== null) {
            $config['extension'] = $this->extension;
            $config['extensionConfig'] = $this->extensionConfig;
        }

        return $config;
    }

    public function render(): View
    {
        return view('ui-kit::components.rte');
    }
}
