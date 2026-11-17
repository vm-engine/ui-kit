<?php

declare(strict_types=1);

it('binds wire:model on the input, builds the accept list and shows the counter', function (): void {
    $html = render('<x-ui::file-drop wire:model="docs" multiple :max-count="3" :current-count="1" :allowed-types="[\'pdf\', \'jpg\']" :max-size-kb="5120" />');

    expect($html)->toContain('accept=".pdf,.jpg"')
        ->toContain('multiple')
        ->toContain('wire:model="docs"')
        ->toContain('1 / 3')
        ->toContain('PDF, JPG')
        ->toContain(__('ui-kit::ui.max_size', ['size' => '5,120']));
});

it('shows the full message instead of the zone when the limit is reached', function (): void {
    expect(render('<x-ui::file-drop wire:model="docs" :max-count="2" :current-count="2" />'))
        ->toContain(__('ui-kit::ui.files_full'))
        ->not->toContain('type="file"');
});

it('escapes the label', function (): void {
    expect(render('<x-ui::file-drop label="<b>x</b>" />'))->toContain('&lt;b&gt;x&lt;/b&gt;');
});
