<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Concerns\BindsWireModel;

/**
 * Single-file drop zone with preview. Bound with wire:model it checks type, size
 * and image dimensions in the browser, then uploads with $wire.upload(); unbound,
 * it is a plain `name` file input for a normal form. Browser checks only help the
 * user: always validate the upload on the server.
 */
final class DragNDrop extends Component
{
    use BindsWireModel;

    public string $label;

    public string $description;

    public string $browseText;

    /**
     * @param  int  $maxSize  KB
     */
    public function __construct(
        public string $name = 'file',
        public string $accept = 'image/png,image/jpeg,image/webp',
        public int $maxSize = 5120,
        public ?int $maxWidth = null,
        public ?int $maxHeight = null,
        public bool $preview = true,
        ?string $label = null,
        ?string $description = null,
        ?string $browseText = null,
        public ?string $error = null,
    ) {
        $this->label = $label ?? (string) __('ui-kit::ui.drop_file');
        $this->description = $description ?? (string) __('ui-kit::ui.drop_file_description');
        $this->browseText = $browseText ?? (string) __('ui-kit::ui.browse');
    }

    /**
     * "PNG, JPEG, WEBP" from the accept list.
     */
    public function acceptExtensions(): string
    {
        $types = array_filter(array_map(trim(...), explode(',', $this->accept)));

        return implode(', ', array_map(fn (string $type): string => strtoupper((string) preg_replace('#^.*/#', '', $type)), $types));
    }

    /**
     * @return array{wireModel: ?string, accept: string, maxSize: int, maxWidth: ?int, maxHeight: ?int, preview: bool, messages: array<string, string>}
     */
    public function config(): array
    {
        return [
            'wireModel' => $this->wireModelName(),
            'accept' => $this->accept,
            'maxSize' => $this->maxSize,
            'maxWidth' => $this->maxWidth,
            'maxHeight' => $this->maxHeight,
            'preview' => $this->preview,
            'messages' => [
                'invalidType' => (string) __('ui-kit::ui.invalid_type', ['types' => $this->acceptExtensions()]),
                'fileTooLarge' => (string) __('ui-kit::ui.file_too_large', ['size' => $this->maxSize]),
                'imageTooLarge' => (string) __('ui-kit::ui.image_too_large', ['width' => $this->maxWidth ?? '∞', 'height' => $this->maxHeight ?? '∞']),
                'uploadFailed' => (string) __('ui-kit::ui.upload_failed'),
            ],
        ];
    }

    public function render(): View
    {
        return view('ui-kit::components.drag-n-drop');
    }
}
