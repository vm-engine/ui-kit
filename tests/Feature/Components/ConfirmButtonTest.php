<?php

declare(strict_types=1);

use Illuminate\View\ViewException;

it('carries the delete token and method to the dialog, never an id', function (): void {
    $html = decoded('<x-ui::confirm-button method="delete" token="tok_abc" label="Delete" title="Delete user?" message="This cannot be undone." />');

    expect($html)->toContain('data-method="delete"')
        ->toContain('data-param="tok_abc"')
        ->toContain('data-title="Delete user?"')
        ->toContain('data-message="This cannot be undone."')
        ->toContain("\$dispatch('ui-confirm'")
        ->toContain('$wire.$id')
        ->toContain('aria-label="Delete"');
});

it('sends no params when neither token nor param is given', function (): void {
    expect(render('<x-ui::confirm-button method="archive" label="Archive" title="t" message="m" />'))->toContain('data-param=""');
});

it('renders a text button when labelled', function (): void {
    expect(render('<x-ui::confirm-button method="archive" label="Archive" title="t" message="m" labelled color="blue" />'))
        ->toContain('Archive')
        ->toContain('data-color="blue"')
        ->not->toContain('aria-label="Archive"');
});

it('throws on an unsafe or empty method', function (string $method): void {
    render('<x-ui::confirm-button method="'.$method.'" label="x" title="x" message="x" />');
})->with(['delete(); alert(1)', ''])->throws(ViewException::class);
