<?php

declare(strict_types=1);

use Livewire\Livewire;
use VmEngine\UiKit\Demo\DemoNotification;
use VmEngine\UiKit\Demo\DemoSearchProvider;

it('renders every section for a signed-in user', function (): void {
    $response = $this->actingAs(makeUser())->get('/ui-kit-demo')->assertOk();

    foreach (array_keys(DemoSearchProvider::SECTIONS) as $id) {
        $response->assertSee('id="'.$id.'"', false);
    }
});

it('sends the demo notification to the signed-in user', function (): void {
    $user = makeUser();

    Livewire::actingAs($user)->test('ui::demo')->call('sendNotification');

    expect($user->notifications()->count())->toBe(1)
        ->and($user->notifications()->firstOrFail()->type)->toBe(DemoNotification::class);
});

it('hides the file-manager editor when file-manager is not installed', function (): void {
    Livewire::actingAs(makeUser())->test('ui::demo')
        ->assertDontSeeHtml('fm-jodit.js')
        ->assertDontSeeHtml('extension="file-manager"');
});

it('uses the package sample image for the lightbox', function (): void {
    Livewire::actingAs(makeUser())->test('ui::demo')->assertSeeHtml('demo/sample.svg');
});
