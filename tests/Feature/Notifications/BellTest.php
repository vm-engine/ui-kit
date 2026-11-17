<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Once;
use Livewire\Livewire;
use VmEngine\UiKit\Tests\Fixtures\TestNotification;

beforeEach(function (): void {
    config(['ui-kit.notifications.enabled' => true]);
    Once::flush();
});

it('shows the unread count and renders translated title and message', function (): void {
    $user = makeUser();
    $user->notify(new TestNotification);
    $user->notify(new TestNotification);

    Livewire::actingAs($user)->test('ui::notifications.bell')
        ->assertSeeHtml('data-ui-unread-count="2"')
        ->assertSee('Invoice paid')
        ->assertSee('Invoice INV-7 was paid.');
});

it('marks the visible notifications read on open but keeps them styled unread', function (): void {
    $user = makeUser();
    $user->notify(new TestNotification);

    $bell = Livewire::actingAs($user)->test('ui::notifications.bell')->call('openPanel');

    expect($user->unreadNotifications()->count())->toBe(0)
        ->and($bell->get('visuallyUnreadIds'))->toHaveCount(1);
});

it('never marks another user\'s notification', function (): void {
    $owner = makeUser('owner@example.test');
    $owner->notify(new TestNotification);
    $foreignId = $owner->notifications()->firstOrFail()->id;

    Livewire::actingAs(makeUser('intruder@example.test'))->test('ui::notifications.bell')->call('markOne', $foreignId);

    expect($owner->unreadNotifications()->count())->toBe(1);
});

it('marks all of the user\'s notifications read', function (): void {
    $user = makeUser();
    $user->notify(new TestNotification);
    $user->notify(new TestNotification);

    Livewire::actingAs($user)->test('ui::notifications.bell')->call('markAll');

    expect($user->unreadNotifications()->count())->toBe(0);
});

it('toasts only new notifications flagged with toast', function (): void {
    $user = makeUser();
    $bell = Livewire::actingAs($user)->test('ui::notifications.bell');

    $user->notify(new TestNotification(['title' => 'Loud', 'toast' => true]));
    $user->notify(new TestNotification(['title' => 'Quiet']));

    $bell->call('refresh')
        ->assertDispatched('toast-show', fn (string $name, array $params): bool => ($params['slots']['heading'] ?? null) === 'Loud')
        ->assertNotDispatched('toast-show', fn (string $name, array $params): bool => ($params['slots']['heading'] ?? null) === 'Quiet');
});

it('drops unsafe notification urls', function (): void {
    $user = makeUser();
    $user->notify(new TestNotification(['url' => 'javascript:alert(1)']));
    $user->notify(new TestNotification(['title' => 'Safe one', 'url' => '/invoices/7']));

    Livewire::actingAs($user)->test('ui::notifications.bell')
        ->assertDontSeeHtml('javascript:alert(1)')
        ->assertSeeHtml('href="/invoices/7"');
});

it('renders nothing when disabled', function (): void {
    config(['ui-kit.notifications.enabled' => false]);

    Livewire::actingAs(makeUser())->test('ui::notifications.bell')
        ->assertDontSeeHtml('aria-label="'.__('ui-kit::ui.notifications').'"');
});

it('renders nothing without the notifications table', function (): void {
    Schema::drop('notifications');

    Livewire::actingAs(makeUser())->test('ui::notifications.bell')
        ->assertDontSeeHtml('aria-label="'.__('ui-kit::ui.notifications').'"');
});
