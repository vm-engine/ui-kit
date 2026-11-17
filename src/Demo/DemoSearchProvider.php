<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Demo;

use Illuminate\Contracts\Auth\Authenticatable;
use VmEngine\UiKit\CommandPalette\SearchProvider;
use VmEngine\UiKit\CommandPalette\SearchResult;

/**
 * Command-palette results for the /ui-kit-demo sections. Registered automatically
 * while ui-kit.demo.enabled is true; the demo is open to anyone who can reach the route.
 */
final class DemoSearchProvider implements SearchProvider
{
    /** @var array<string, array{0: string, 1: string}> section id => [title, Heroicon] */
    public const SECTIONS = [
        'empty-state' => ['Empty state', 'inbox'],
        'stat-tile' => ['Stat tile', 'chart-bar'],
        'per-page-selector' => ['Per-page selector', 'list-bullet'],
        'form-action-bar' => ['Form action bar', 'check'],
        'copy-button' => ['Copy button', 'document-duplicate'],
        'password-generator' => ['Password generator', 'key'],
        'confirm-button' => ['Confirm button', 'trash'],
        'tabs' => ['Tabs', 'rectangle-group'],
        'steps' => ['Steps', 'numbered-list'],
        'split-pane' => ['Split pane', 'view-columns'],
        'lightbox' => ['Lightbox', 'photo'],
        'currency' => ['Currency', 'banknotes'],
        'color-picker' => ['Color picker', 'swatch'],
        'datepicker' => ['Datepicker', 'calendar'],
        'adv-select' => ['Adv-select', 'chevron-up-down'],
        'uploads' => ['Drag-n-drop and file-drop', 'arrow-up-tray'],
        'kanban' => ['Kanban', 'view-columns'],
        'calendar' => ['Calendar', 'calendar-days'],
        'rte' => ['Rich-text editor', 'pencil-square'],
        'notifications' => ['Notifications and command palette', 'bell'],
    ];

    public function search(string $term, Authenticatable $user, int $limit): iterable
    {
        $needle = mb_strtolower($term);
        $page = route('ui-kit.demo', absolute: false);

        foreach (self::SECTIONS as $id => [$title, $icon]) {
            if (str_contains(mb_strtolower($title), $needle) || str_contains($id, $needle)) {
                yield new SearchResult($title, $page.'#'.$id, icon: $icon, group: 'UI kit demo');
            }
        }
    }
}
