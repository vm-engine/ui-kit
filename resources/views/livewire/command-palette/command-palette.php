<?php

use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\UiKit\CommandPalette\CommandPaletteSearch;
use VmEngine\UiKit\CommandPalette\SearchResult;

/**
 * <livewire:ui::command-palette />: Ctrl/⌘+K search over the configured
 * SearchProviders (ui-kit.command_palette.providers).
 */
new class extends Component
{
    public string $term = '';

    #[Computed]
    public function enabled(): bool
    {
        return app(CommandPaletteSearch::class)->enabled(auth()->user());
    }

    public function hasTerm(): bool
    {
        return mb_strlen(trim($this->term)) >= CommandPaletteSearch::MIN_TERM_LENGTH;
    }

    /**
     * @return array<string, list<SearchResult>>
     */
    #[Computed]
    public function groups(): array
    {
        if (! $this->enabled || ! $this->hasTerm()) {
            return [];
        }

        $groups = [];

        foreach (app(CommandPaletteSearch::class)->search($this->term, auth()->user()) as $result) {
            $groups[$result->group ?? __('ui-kit::ui.results')][] = $result;
        }

        return $groups;
    }
};
