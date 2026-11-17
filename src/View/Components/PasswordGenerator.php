<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Read-only field that generates a strong password (crypto.getRandomValues),
 * copies it, and can set it on one or more Livewire properties.
 */
final class PasswordGenerator extends Component
{
    public int $length;

    /**
     * @param  string|list<string>|null  $model  property, or [password, confirmation]
     */
    public function __construct(
        public string|array|null $model = null,
        int $length = 12,
    ) {
        $this->length = max(8, min(128, $length));
    }

    /**
     * @return list<string>
     */
    public function models(): array
    {
        return array_values(array_filter((array) $this->model, fn (string $property): bool => $property !== ''));
    }

    public function render(): View
    {
        return view('ui-kit::components.password-generator');
    }
}
