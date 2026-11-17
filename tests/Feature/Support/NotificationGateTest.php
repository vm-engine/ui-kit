<?php

declare(strict_types=1);

use VmEngine\UiKit\Support\NotificationGate;

it('re-evaluates per request scope instead of for the whole process', function (): void {
    config(['ui-kit.notifications.enabled' => false]);
    expect(NotificationGate::enabled())->toBeFalse();

    // Long-running workers (Octane, queues) flush scoped instances between requests/jobs.
    config(['ui-kit.notifications.enabled' => true]);
    app()->forgetScopedInstances();

    expect(NotificationGate::enabled())->toBeTrue();
});
