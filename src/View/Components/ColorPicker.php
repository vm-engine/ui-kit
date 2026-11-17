<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Concerns\BindsWireModel;

/**
 * "#rrggbb" picker: swatches plus (optionally) the native colour input and a
 * hex text field, entangled with wire:model. Validate with `hex_color` server-side.
 */
final class ColorPicker extends Component
{
    use BindsWireModel;

    private const HEX_PATTERN = '/^#[0-9a-fA-F]{6}$/';

    /** @var list<string> */
    public array $swatches;

    /**
     * @param  array<int|string, mixed>  $swatches
     */
    public function __construct(
        array $swatches = [],
        public bool $allowCustom = true,
    ) {
        $this->swatches = array_values(array_map(
            strtolower(...),
            array_filter($swatches, fn (mixed $color): bool => is_string($color) && preg_match(self::HEX_PATTERN, $color) === 1),
        ));

        // Without swatches the custom picker is the only way to choose.
        if ($this->swatches === []) {
            $this->allowCustom = true;
        }
    }

    public function render(): View
    {
        return view('ui-kit::components.color-picker');
    }
}
