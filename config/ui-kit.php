<?php

declare(strict_types=1);

return [
    /*
     * URL prefix of the package asset route (JS, flatpickr, Jodit).
     */
    'assets_prefix' => env('UI_KIT_ASSETS_PREFIX', 'ui-kit-assets'),

    /*
     * In-app notifications on Laravel's stock `notifications` table.
     * The bell and panel render nothing until this is true AND the table exists.
     */
    'notifications' => [
        'enabled' => env('UI_KIT_NOTIFICATIONS', false),
        'dropdown_limit' => 5,
        'per_page' => 15,
        'poll_seconds' => 15, // null = no polling
    ],

    /*
     * Command palette (Ctrl/⌘+K). Each provider implements
     * VmEngine\UiKit\CommandPalette\SearchProvider and must filter by the user's permissions.
     */
    'command_palette' => [
        'providers' => [],
        'limit_per_provider' => 5,
    ],

    /*
     * Demo page of every component at /ui-kit-demo. Off by default: it performs real
     * actions (uploads, notifications). Turn on in development only (UI_KIT_DEMO=true).
     * layout: Livewire layout for the page; null uses livewire.component_layout.
     */
    'demo' => [
        'enabled' => env('UI_KIT_DEMO', false),
        'middleware' => ['web', 'auth'],
        'layout' => null,
    ],
];
