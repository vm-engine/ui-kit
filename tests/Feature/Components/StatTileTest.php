<?php

declare(strict_types=1);

it('renders the tile variant with label, value and icon', function (): void {
    $html = render('<x-ui::stat-tile icon="document-text" color="blue" label="Invoices" value="2,613" />');

    expect($html)->toContain('Invoices')
        ->toContain('2,613')
        ->toContain('data-flux-icon')
        ->toContain('bg-blue-100');
});

it('renders the card variant with sublabel, as a link when href is given', function (): void {
    $html = render('<x-ui::stat-tile variant="card" label="Users" value="9" sublabel="this month" sublabel-color="green" href="/admin/users" />');

    expect($html)->toContain('<a')
        ->toContain('href="/admin/users"')
        ->toContain('this month')
        ->toContain('text-green-600')
        ->toContain('hover:shadow-md');
});

it('shows a zero value', function (): void {
    expect(render('<x-ui::stat-tile label="Open" value="0" />'))->toMatch('/>\s*0\s*</');
});

it('falls back to zinc for an unknown colour and tile for an unknown variant', function (): void {
    $html = render('<x-ui::stat-tile icon="user" label="X" value="1" color="chartreuse" variant="weird" />');

    expect($html)->toContain('bg-zinc-100')->not->toContain('chartreuse');
});

it('tints the whole card in its colour', function (): void {
    expect(render('<x-ui::stat-tile variant="card" color="red" tinted label="Stuck" value="4" />'))
        ->toContain('bg-red-50')
        ->toContain('text-red-700');
});

it('emits only the override colour so utility order cannot hide it', function (): void {
    expect(render('<x-ui::stat-tile label="M" value="0" value-color="green" />'))
        ->toContain('text-green-600')
        ->not->toContain('text-zinc-800');

    expect(render('<x-ui::stat-tile variant="card" color="red" tinted label="S" value="4" />'))
        ->not->toContain('text-zinc-900');
});

it('drops an unsafe href instead of rendering a link', function (): void {
    expect(render('<x-ui::stat-tile variant="card" label="X" value="1" href="javascript:alert(1)" />'))
        ->not->toContain('javascript:')
        ->not->toContain('<a');
});
