<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Concerns\SanitizesWireArguments;

/**
 * Full-screen image viewer, opened with
 * $dispatch('open-lightbox-{name}', { url, alt }) and closed with Escape,
 * the close button or a click on the backdrop.
 */
final class Lightbox extends Component
{
    use SanitizesWireArguments;

    public string $name;

    public function __construct(string $name = 'lightbox')
    {
        // HTML lowercases attribute names, so the x-on:open-lightbox-{name} listener only matches lowercase.
        $this->name = strtolower($this->safeKey($name) ?? 'lightbox');
    }

    public function render(): View
    {
        return view('ui-kit::components.lightbox');
    }
}
