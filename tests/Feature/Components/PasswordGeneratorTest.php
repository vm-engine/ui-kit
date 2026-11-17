<?php

declare(strict_types=1);

it('passes length and target properties to Alpine', function (): void {
    $html = decoded('<x-ui::password-generator :model="[\'password\', \'password_confirmation\']" :length="16" />');

    expect($html)->toContain('uiPasswordGenerator(16, '.js(['password', 'password_confirmation']).')')
        ->toContain('aria-label="'.__('ui-kit::ui.use_password').'"');
});

it('clamps the length to 8..128', function (int $given, int $used): void {
    expect(render('<x-ui::password-generator model="password" :length="'.$given.'" />'))->toContain('uiPasswordGenerator('.$used.',');
})->with([[4, 8], [500, 128], [20, 20]]);

it('hides the apply button when no property is bound', function (): void {
    expect(render('<x-ui::password-generator />'))->not->toContain('aria-label="'.__('ui-kit::ui.use_password').'"');
});
