<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Concerns\BindsWireModel;

/**
 * Money input shown as "1.250.000,50" ('.' thousands, ',' decimals). With `raw`
 * the bound property receives the plain number "1250000.50"; otherwise it
 * receives the formatted text.
 */
final class Currency extends Component
{
    use BindsWireModel;

    public function __construct(
        public string $currency = 'Rp.',
        public int $decimals = 0,
        public bool $raw = false,
    ) {
        $this->decimals = max(0, min(6, $decimals));
    }

    public function usesRawBinding(): bool
    {
        return $this->raw && $this->wireModelName() !== null;
    }

    /**
     * Milliseconds to wait before committing a raw `.live` value, matching
     * wire:model: 150ms for .live, 250ms for a bare .debounce, N for .debounce.Nms;
     * null when not live (sent with the next request).
     */
    public function syncDelay(): ?int
    {
        $model = $this->attributes->wire('model');

        if (! $model->hasModifier('live')) {
            return null;
        }

        $delay = $model->hasModifier('debounce') ? 250 : 150;

        foreach ($model->modifiers() as $modifier) {
            if (preg_match('/^(\d+)ms$/', (string) $modifier, $match) === 1) {
                $delay = (int) $match[1];
            }
        }

        return $delay;
    }

    public function render(): View
    {
        return view('ui-kit::components.currency');
    }
}
