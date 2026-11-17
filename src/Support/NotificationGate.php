<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Support;

use Illuminate\Support\Facades\Schema;

/**
 * In-app notifications are usable when the config flag is on AND Laravel's
 * stock `notifications` table exists. Bound as a scoped instance, so the
 * answer is memoised per request/job and re-checked in long-running workers.
 */
final class NotificationGate
{
    private ?bool $enabled = null;

    public static function enabled(): bool
    {
        return app(self::class)->check();
    }

    public function check(): bool
    {
        return $this->enabled ??= (bool) config('ui-kit.notifications.enabled', false)
            && Schema::hasTable('notifications');
    }
}
