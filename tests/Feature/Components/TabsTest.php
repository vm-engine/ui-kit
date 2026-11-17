<?php

declare(strict_types=1);

function tabsMarkup(string $rootAttributes = ''): string
{
    return <<<BLADE
        <x-ui::tabs {$rootAttributes}>
            <x-slot:tabs>
                <x-ui::tabs.tab name="profile" icon="user">Profile</x-ui::tabs.tab>
                <x-ui::tabs.tab name="security">Security</x-ui::tabs.tab>
            </x-slot:tabs>
            <x-ui::tabs.panel name="profile">Profile panel</x-ui::tabs.panel>
            <x-ui::tabs.panel name="security">Security panel</x-ui::tabs.panel>
        </x-ui::tabs>
        BLADE;
}

it('renders an accessible tablist with linked panels', function (): void {
    $html = decoded(tabsMarkup());

    expect($html)->toContain('role="tablist"')
        ->toContain('role="tab"')
        ->toContain('role="tabpanel"')
        ->toContain("x-id=\"['ui-tab', 'ui-panel']\"")
        ->toContain('data-ui-tab="profile"')
        ->toContain("\$id('ui-panel', 'security')")
        ->toContain('Profile panel')
        ->toContain('Security panel')
        ->toContain('uiTabs(null)');
});

it('entangles the selected tab with wire:model without leaking the attribute', function (): void {
    $html = decoded(tabsMarkup('wire:model.live="tab"'));

    expect($html)->toContain("uiTabs(\$wire.entangle('tab').live)")
        ->not->toContain('wire:model');
});
