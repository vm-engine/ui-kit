<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Demo;

use Illuminate\Notifications\Notification;

/**
 * Sent by the demo page's "Send me a notification" button to exercise the bell,
 * the panel and toasts.
 */
final class DemoNotification extends Notification
{
    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Demo notification',
            'message' => 'Sent at :time',
            'params' => ['time' => now()->format('H:i:s')],
            'url' => route('ui-kit.demo', absolute: false),
            'icon' => 'sparkles',
            'toast' => true,
        ];
    }
}
