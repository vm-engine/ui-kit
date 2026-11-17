<?php

declare(strict_types=1);

use VmEngine\UiKit\View\Components\DragNDrop;

it('renders a labelled file input zone without binding wire:model to it', function (): void {
    $html = decoded('<x-ui::drag-n-drop wire:model="avatar" :max-size="2048" accept="image/png,image/jpeg" />');

    expect($html)->toContain('type="file"')
        ->toContain('accept="image/png,image/jpeg"')
        ->toContain('uiDragNDrop(')
        ->toContain(__('ui-kit::ui.browse'))
        ->not->toContain('wire:model');
});

it('passes limits, the bound property and translated messages to Alpine', function (): void {
    $component = new DragNDrop(accept: 'image/png,image/jpeg', maxSize: 2048, maxWidth: 800, maxHeight: 600);
    $component->withAttributes(['wire:model' => 'avatar']);
    $config = $component->config();

    expect($config['wireModel'])->toBe('avatar')
        ->and($config['maxSize'])->toBe(2048)
        ->and($config['maxWidth'])->toBe(800)
        ->and($config['messages']['fileTooLarge'])->toContain('2048')
        ->and($config['messages']['invalidType'])->toContain('PNG, JPEG')
        ->and($config['messages']['imageTooLarge'])->toContain('800 × 600');
});

it('shows a server-side error', function (): void {
    expect(render('<x-ui::drag-n-drop error="The avatar must be an image." />'))->toContain('The avatar must be an image.');
});
