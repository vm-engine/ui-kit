<?php

declare(strict_types=1);

use Livewire\Livewire;
use VmEngine\UiKit\Tests\Fixtures\PagesProvider;

beforeEach(function (): void {
    config(['ui-kit.command_palette.providers' => [PagesProvider::class]]);
});

it('renders nothing for a guest', function (): void {
    Livewire::test('ui::command-palette')->assertDontSeeHtml('ui-command-palette');
});

it('shows grouped results for a signed-in user', function (): void {
    Livewire::actingAs(makeUser())->test('ui::command-palette')
        ->set('term', 'ro')
        ->assertSee('Pages')
        ->assertSee('Roles')
        ->assertSee('Who can do what')
        ->assertSeeHtml('href="/admin/roles"')
        ->assertDontSee('Users');
});

it('asks for at least two characters', function (): void {
    Livewire::actingAs(makeUser())->test('ui::command-palette')
        ->set('term', 'r')
        ->assertSee(__('ui-kit::ui.search_hint'));
});

it('says when nothing matches', function (): void {
    Livewire::actingAs(makeUser())->test('ui::command-palette')
        ->set('term', 'zzz')
        ->assertSee(__('ui-kit::ui.no_results'));
});

it('is not teleported, so re-renders keep the search input focused', function (): void {
    Livewire::actingAs(makeUser())->test('ui::command-palette')
        ->assertDontSeeHtml('x-teleport');
});

it('closes the palette when a result is chosen, so same-page anchors do not leave it open', function (): void {
    Livewire::actingAs(makeUser())->test('ui::command-palette')
        ->set('term', 'ro')
        ->assertSeeHtml("x-on:click=\"\$flux.modal('ui-command-palette').close()\"");
});
