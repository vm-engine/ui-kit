<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Support\SafeUrl;

/**
 * A single metric. variant="tile" (icon square left, label/value right) or
 * variant="card" (title row, big value, sublabel, optional tint/link).
 * Props and behaviour match Synapse's stat-tile; colours use Flux names.
 */
final class StatTile extends Component
{
    /**
     * Full class strings per colour (Tailwind only sees complete class names).
     *
     * @var array<string, array{icon: string, text: string, tint: string, tintText: string}>
     */
    private const PALETTE = [
        'zinc' => [
            'icon' => 'bg-zinc-100 text-zinc-600 dark:bg-white/10 dark:text-zinc-300',
            'text' => 'text-zinc-600 dark:text-zinc-300',
            'tint' => 'border-zinc-200 bg-zinc-50 dark:border-white/10 dark:bg-white/5',
            'tintText' => 'text-zinc-700 dark:text-zinc-200',
        ],
        'blue' => [
            'icon' => 'bg-blue-100 text-blue-600 dark:bg-blue-400/15 dark:text-blue-400',
            'text' => 'text-blue-600 dark:text-blue-400',
            'tint' => 'border-blue-200 bg-blue-50 dark:border-blue-400/25 dark:bg-blue-400/10',
            'tintText' => 'text-blue-700 dark:text-blue-300',
        ],
        'indigo' => [
            'icon' => 'bg-indigo-100 text-indigo-600 dark:bg-indigo-400/15 dark:text-indigo-400',
            'text' => 'text-indigo-600 dark:text-indigo-400',
            'tint' => 'border-indigo-200 bg-indigo-50 dark:border-indigo-400/25 dark:bg-indigo-400/10',
            'tintText' => 'text-indigo-700 dark:text-indigo-300',
        ],
        'purple' => [
            'icon' => 'bg-purple-100 text-purple-600 dark:bg-purple-400/15 dark:text-purple-400',
            'text' => 'text-purple-600 dark:text-purple-400',
            'tint' => 'border-purple-200 bg-purple-50 dark:border-purple-400/25 dark:bg-purple-400/10',
            'tintText' => 'text-purple-700 dark:text-purple-300',
        ],
        'red' => [
            'icon' => 'bg-red-100 text-red-600 dark:bg-red-400/15 dark:text-red-400',
            'text' => 'text-red-600 dark:text-red-400',
            'tint' => 'border-red-200 bg-red-50 dark:border-red-400/25 dark:bg-red-400/10',
            'tintText' => 'text-red-700 dark:text-red-300',
        ],
        'orange' => [
            'icon' => 'bg-orange-100 text-orange-600 dark:bg-orange-400/15 dark:text-orange-400',
            'text' => 'text-orange-600 dark:text-orange-400',
            'tint' => 'border-orange-200 bg-orange-50 dark:border-orange-400/25 dark:bg-orange-400/10',
            'tintText' => 'text-orange-700 dark:text-orange-300',
        ],
        'green' => [
            'icon' => 'bg-green-100 text-green-600 dark:bg-green-400/15 dark:text-green-400',
            'text' => 'text-green-600 dark:text-green-400',
            'tint' => 'border-green-200 bg-green-50 dark:border-green-400/25 dark:bg-green-400/10',
            'tintText' => 'text-green-700 dark:text-green-300',
        ],
    ];

    public function __construct(
        public ?string $icon = null,
        public string $color = 'zinc',
        public ?string $label = null,
        public ?string $value = null,
        public ?string $valueColor = null,
        public bool $bordered = true,
        public string $variant = 'tile',
        public string $iconPosition = 'title',
        public bool $iconBackground = true,
        public bool $tinted = false,
        public ?string $sublabel = null,
        public ?string $sublabelColor = null,
        public ?string $href = null,
        public bool $clickable = false,
    ) {
        $this->color = self::known($this->color) ?? 'zinc';
        $this->valueColor = self::known($this->valueColor);
        $this->sublabelColor = self::known($this->sublabelColor);
        $this->variant = in_array($this->variant, ['tile', 'card'], true) ? $this->variant : 'tile';
        $this->href = SafeUrl::isSafe($this->href) ? $this->href : null;
        $this->iconPosition = in_array($this->iconPosition, ['left', 'title', 'background'], true) ? $this->iconPosition : 'title';
    }

    public function tag(): string
    {
        return filled($this->href) ? 'a' : 'div';
    }

    public function isClickable(): bool
    {
        return $this->clickable || filled($this->href);
    }

    /**
     * The icon wrapper: a coloured square/circle, or a bare coloured glyph.
     */
    public function iconClasses(string $shape = 'rounded-lg size-12'): string
    {
        return $this->iconBackground
            ? 'flex shrink-0 items-center justify-center '.$shape.' '.self::PALETTE[$this->color]['icon']
            : 'flex shrink-0 items-center justify-center '.self::PALETTE[$this->color]['text'];
    }

    /**
     * Card title / tile label text: the tint colour, or muted zinc.
     */
    public function labelClasses(): string
    {
        if ($this->variant === 'card' && $this->tinted) {
            return self::PALETTE[$this->color]['tintText'];
        }

        return 'text-zinc-500 dark:text-zinc-400';
    }

    /**
     * Value text: the tint (card), the valueColor override (tile), or the default.
     * Only one colour class is ever emitted, so no utility-order conflicts.
     */
    public function valueClasses(): string
    {
        if ($this->variant === 'card') {
            return $this->tinted ? self::PALETTE[$this->color]['tintText'] : 'text-zinc-900 dark:text-white';
        }

        return $this->valueColor !== null ? self::PALETTE[$this->valueColor]['text'] : 'text-zinc-800 dark:text-white/90';
    }

    public function sublabelClasses(): string
    {
        return $this->sublabelColor !== null ? self::PALETTE[$this->sublabelColor]['text'] : 'text-zinc-500 dark:text-zinc-400';
    }

    public function backgroundIconColor(): string
    {
        return $this->color === 'zinc' ? 'text-zinc-400' : self::PALETTE[$this->color]['text'];
    }

    public function tintClasses(): string
    {
        return $this->tinted ? self::PALETTE[$this->color]['tint'] : 'border-zinc-200 bg-white dark:border-white/10 dark:bg-white/10';
    }

    public function render(): View
    {
        return view('ui-kit::components.stat-tile');
    }

    private static function known(?string $color): ?string
    {
        if ($color === 'gray') {
            return 'zinc';
        }

        return $color !== null && array_key_exists($color, self::PALETTE) ? $color : null;
    }
}
