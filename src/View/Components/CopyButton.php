<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Copies a fixed text to the clipboard, with an execCommand fallback so it
 * also works over plain HTTP (Flux's copyable input needs HTTPS).
 */
final class CopyButton extends Component
{
    public function __construct(
        public string $text,
        public ?string $label = null,
        public bool $iconOnly = true,
    ) {
        $this->label ??= (string) __('ui-kit::ui.copy');
    }

    /**
     * @return array{label: string, copied: string, failed: string}
     */
    public function labels(): array
    {
        return [
            'label' => (string) $this->label,
            'copied' => (string) __('ui-kit::ui.copied'),
            'failed' => (string) __('ui-kit::ui.copy_failed'),
        ];
    }

    public function render(): View
    {
        return view('ui-kit::components.copy-button');
    }
}
