<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Concerns\SanitizesWireArguments;

/**
 * Aside + main layout. On lg+ the aside sits beside the main area (optionally
 * drag/keyboard-resizable, width remembered per storage-key); below lg it is
 * toggled by a button.
 */
final class SplitPane extends Component
{
    use SanitizesWireArguments;

    private const DEFAULT_WIDTH = '20rem';

    public ?string $storageKey;

    public string $toggleLabel;

    public function __construct(
        public string $asideWidth = self::DEFAULT_WIDTH,
        public bool $resizable = false,
        ?string $storageKey = null,
        ?string $toggleLabel = null,
    ) {
        if (preg_match('/^\d+(\.\d+)?(px|rem|%)$/', $asideWidth) !== 1) {
            $this->asideWidth = self::DEFAULT_WIDTH;
        }

        // Only a resizable pane remembers its width.
        $this->storageKey = $resizable ? $this->safeKey($storageKey) : null;
        $this->toggleLabel = $toggleLabel ?? (string) __('ui-kit::ui.toggle_panel');
    }

    public function render(): View
    {
        return view('ui-kit::components.split-pane');
    }
}
