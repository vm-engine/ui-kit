<?php

declare(strict_types=1);

use Illuminate\Support\Once;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use VmEngine\UiKit\Tests\Fixtures\TestNotification;

beforeEach(function (): void {
    config(['ui-kit.notifications.enabled' => true, 'ui-kit.notifications.per_page' => 2]);
    Once::flush();
});

it('paginates and marks only the rendered page read', function (): void {
    $user = makeUser();
    foreach (range(1, 3) as $i) {
        $user->notify(new TestNotification(['title' => "N{$i}"]));
        $this->travel(1)->seconds();
    }

    Livewire::actingAs($user)->test('ui::notifications.list')
        ->assertSee('N3')->assertSee('N2')->assertDontSee('N1');

    expect($user->unreadNotifications()->count())->toBe(1);
});

it('marks a single notification read', function (): void {
    $user = makeUser();
    $user->notify(new TestNotification);
    $id = $user->notifications()->firstOrFail()->id;
    $user->notifications()->update(['read_at' => null]);

    Livewire::actingAs($user)->test('ui::notifications.list')->call('markOne', $id);

    expect($user->notifications()->firstOrFail()->read_at)->not->toBeNull();
});

it('does not mark anything read before the lazy list loads', function (): void {
    $user = makeUser();
    $user->notify(new TestNotification);
    $this->actingAs($user);

    $html = render('<x-ui::notifications.panel />');

    expect($html)->toContain('ui-notifications')
        ->and($user->unreadNotifications()->count())->toBe(1);
});

it('renders no panel when notifications are off', function (): void {
    config(['ui-kit.notifications.enabled' => false]);
    Once::flush();
    $this->actingAs(makeUser());

    expect(render('<x-ui::notifications.panel />'))->not->toContain('ui-notifications');
});

it('does not let the client switch the list on', function (): void {
    config(['ui-kit.notifications.enabled' => false]);
    Once::flush();

    Livewire::actingAs(makeUser())->test('ui::notifications.list')->set('enabled', true);
})->throws(CannotUpdateLockedPropertyException::class);
