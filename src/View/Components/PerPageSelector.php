<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Page-size select bound to the host component's $perPage (10/15/25/50),
 * next to the paginator's links.
 */
final class PerPageSelector extends Component
{
    /** @var list<int> */
    public const SIZES = [10, 15, 25, 50];

    /**
     * @param  LengthAwarePaginator<array-key, mixed>  $paginator
     */
    public function __construct(
        public LengthAwarePaginator $paginator,
        public ?string $label = null,
    ) {}

    public function ariaLabel(): string
    {
        return $this->label ?? (string) __('ui-kit::ui.per_page');
    }

    public function render(): View
    {
        return view('ui-kit::components.per-page-selector');
    }
}
