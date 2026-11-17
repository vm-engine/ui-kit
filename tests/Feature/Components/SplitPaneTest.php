<?php

declare(strict_types=1);

it('passes the storage key only when resizable', function (): void {
    expect(decoded('<x-ui::split-pane resizable storage-key="nodes"><x-slot:aside>A</x-slot:aside> B</x-ui::split-pane>'))
        ->toContain("uiSplitPane('nodes', '20rem')")
        ->toContain('role="separator"')
        ->and(decoded('<x-ui::split-pane storage-key="nodes"><x-slot:aside>A</x-slot:aside> B</x-ui::split-pane>'))
        ->toContain('uiSplitPane(null,')
        ->not->toContain('role="separator"');
});

it('drops an unsafe storage key and an invalid width', function (): void {
    expect(decoded('<x-ui::split-pane resizable storage-key="a b;c" aside-width="calc(1px)"><x-slot:aside>A</x-slot:aside> B</x-ui::split-pane>'))
        ->toContain("uiSplitPane(null, '20rem')");
});
