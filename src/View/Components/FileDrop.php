<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Concerns\BindsWireModel;

/**
 * Multi-file drop zone: a native file input (wire:model passes straight to it, so
 * Livewire handles the uploads) with a counter and a "full" state at max-count.
 * Always validate count, type and size on the server.
 */
final class FileDrop extends Component
{
    use BindsWireModel;

    /**
     * @param  list<string>  $allowedTypes  extensions without the dot, e.g. ['pdf', 'jpg']
     */
    public function __construct(
        public bool $multiple = false,
        public ?int $maxCount = null,
        public ?int $maxSizeKb = null,
        public array $allowedTypes = [],
        public ?string $label = null,
        public ?string $hint = null,
        public int $currentCount = 0,
        public string $name = 'file',
        public ?string $error = null,
        public ?string $fullText = null,
        public ?string $uploadingText = null,
    ) {}

    public function isFull(): bool
    {
        return $this->maxCount !== null && $this->currentCount >= $this->maxCount;
    }

    public function resolvedLabel(): string
    {
        return $this->label ?? (string) __('ui-kit::ui.drop_files');
    }

    public function resolvedFullText(): string
    {
        return $this->fullText ?? (string) __('ui-kit::ui.files_full');
    }

    public function resolvedUploadingText(): string
    {
        return $this->uploadingText ?? (string) __('ui-kit::ui.uploading');
    }

    /**
     * "PDF, JPG · max 5,120 KB" unless a hint is given.
     */
    public function resolvedHint(): string
    {
        if ($this->hint !== null) {
            return $this->hint;
        }

        $parts = [];

        if ($this->allowedTypes !== []) {
            $parts[] = strtoupper(implode(', ', $this->allowedTypes));
        }

        if ($this->maxSizeKb !== null) {
            $parts[] = (string) __('ui-kit::ui.max_size', ['size' => number_format($this->maxSizeKb)]);
        }

        return implode(' · ', $parts);
    }

    public function acceptString(): string
    {
        return implode(',', array_map(fn (string $type): string => '.'.ltrim($type, '.'), $this->allowedTypes));
    }

    public function counterText(): ?string
    {
        return $this->maxCount === null ? null : $this->currentCount.' / '.$this->maxCount;
    }

    public function render(): View
    {
        return view('ui-kit::components.file-drop');
    }
}
