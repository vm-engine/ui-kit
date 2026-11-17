<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use InvalidArgumentException;
use VmEngine\UiKit\Concerns\SanitizesWireArguments;
use VmEngine\UiKit\Support\SafeUrl;

/**
 * Sticky save bar for long forms on their own page. Short forms and forms in
 * modals keep their buttons inline instead.
 */
final class FormActionBar extends Component
{
    use SanitizesWireArguments;

    public string $safeAction;

    /**
     * @param  string|bool|null  $hint  null = the default "* Required field" hint, false/'' = none
     */
    public function __construct(
        public string $action = 'save',
        public ?string $cancelUrl = null,
        public ?string $saveLabel = null,
        public ?string $saveTarget = null,
        public string|bool|null $hint = null,
    ) {
        $this->safeAction = $this->wireMethod($action)
            ?? throw new InvalidArgumentException('ui-kit: form-action-bar needs an action.');
        $this->cancelUrl = SafeUrl::isSafe($this->cancelUrl) ? $this->cancelUrl : null;
    }

    public function hintText(): ?string
    {
        if ($this->hint === false || $this->hint === '') {
            return null;
        }

        return is_string($this->hint) ? $this->hint : (string) __('ui-kit::ui.required_field');
    }

    public function showsRequiredMarker(): bool
    {
        return $this->hint === null || $this->hint === true;
    }

    public function render(): View
    {
        return view('ui-kit::components.form-action-bar');
    }
}
