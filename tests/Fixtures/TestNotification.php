<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Tests\Fixtures;

use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(private array $data = []) {}

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
        return $this->data + ['title' => 'Invoice paid', 'message' => 'Invoice :number was paid.', 'params' => ['number' => 'INV-7']];
    }
}
