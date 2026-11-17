<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Notifications;

use Illuminate\Notifications\DatabaseNotification;
use VmEngine\UiKit\Support\SafeUrl;

/**
 * Shared by the bell and the list. Data shape (toArray/toDatabase):
 * title, message (translation keys or plain text, rendered with `params` in the
 * viewer's locale), url, icon (Heroicon name), toast (bool).
 */
trait InteractsWithNotifications
{
    protected function markNotificationRead(string $id): void
    {
        auth()->user()?->notifications()->whereKey($id)->first()?->markAsRead();
    }

    protected function markAllNotificationsRead(): void
    {
        auth()->user()?->unreadNotifications()->update(['read_at' => now()]);
    }

    public function renderTitle(DatabaseNotification $notification): string
    {
        return $this->translateField($notification, 'title');
    }

    public function renderMessage(DatabaseNotification $notification): string
    {
        return $this->translateField($notification, 'message');
    }

    public function notificationUrl(DatabaseNotification $notification): ?string
    {
        $url = $notification->data['url'] ?? null;

        return is_string($url) && SafeUrl::isSafe($url) ? $url : null;
    }

    public function notificationIcon(DatabaseNotification $notification): string
    {
        $icon = $notification->data['icon'] ?? null;

        return is_string($icon) && preg_match('/^[a-z0-9-]+$/', $icon) === 1 ? $icon : 'bell';
    }

    private function translateField(DatabaseNotification $notification, string $field): string
    {
        $key = $notification->data[$field] ?? '';
        $params = $notification->data['params'] ?? [];

        if (! is_string($key) || $key === '') {
            return '';
        }

        return (string) __($key, is_array($params) ? $params : []);
    }
}
