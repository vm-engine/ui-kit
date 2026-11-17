<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use InvalidArgumentException;
use VmEngine\UiKit\Concerns\SanitizesWireArguments;

/**
 * Opens <x-ui::confirm-dialog> for a Livewire method on the surrounding component.
 * Pass `token` (a model's delete_token) for deletes and `param` for anything else:
 * never a raw id for a delete.
 */
final class ConfirmButton extends Component
{
    use SanitizesWireArguments;

    /**
     * The surrounding Livewire component is resolved from $wire.$id when clicked.
     */
    private const CLICK = "\$dispatch('ui-confirm', { title: \$el.dataset.title, message: \$el.dataset.message, confirmText: \$el.dataset.confirm, color: \$el.dataset.color, method: \$el.dataset.method, params: \$el.dataset.param === '' ? [] : [\$el.dataset.param], component: \$wire.\$id })";

    public string $safeMethod;

    public function __construct(
        public string $method,
        public string $label,
        public string $title,
        public string $message,
        public ?string $token = null,
        public int|string|null $param = null,
        public string $icon = 'trash',
        public string $color = 'red',
        public bool $labelled = false,
        public ?string $confirmText = null,
    ) {
        $this->safeMethod = $this->wireMethod($method)
            ?? throw new InvalidArgumentException('ui-kit: confirm-button needs a method.');
    }

    public function argument(): string
    {
        return (string) ($this->token ?? $this->param ?? '');
    }

    /**
     * Everything the dialog needs, read from the clicked button.
     *
     * @return array<string, string>
     */
    public function buttonAttributes(): array
    {
        return [
            'data-title' => $this->title,
            'data-message' => $this->message,
            'data-confirm' => $this->confirmText ?? $this->label,
            'data-color' => $this->color,
            'data-method' => $this->safeMethod,
            'data-param' => $this->argument(),
            'x-on:click' => self::CLICK,
        ];
    }

    public function render(): View
    {
        return view('ui-kit::components.confirm-button');
    }
}
