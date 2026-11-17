# Changelog

## 0.1.0 (unreleased)

First release: Synapse v3.11.0 components ported to a standalone package that sits next to free Flux.

### Added

- **Form and data:** `adv-select` (search, remote search, load-method, multiple, allow-create, tree depth, keyboard + ARIA), `datepicker` (date and month mode, flatpickr), `currency`, `color-picker`, `password-generator`, `drag-n-drop`, `file-drop`, `rte` (Jodit, extensions, plain-textarea fallback).
- **Layout and display:** `tabs` (new, WAI-ARIA), `steps`, `split-pane`, `lightbox`, `stat-tile`, `empty-state`, `kanban` (+ `kanban.column`, `kanban.card`), `calendar`, `per-page-selector`, `form-action-bar`.
- **Actions:** `confirm-button` + `confirm-dialog` (delete tokens), `copy-button` (works over plain HTTP).
- **Livewire:** `notifications.bell`, `notifications.list` + `<x-ui::notifications.panel />`, `command-palette` with the `SearchProvider` contract.
- Asset route serving the package JS and vendored flatpickr 4.6.13 (+ monthSelect) and Jodit 4.18.4; per-component `@assets`.
- Translations: English and Indonesian.
- Opt-in demo page at `/ui-kit-demo` (`UI_KIT_DEMO`), rendered in the host layout.

### Changed from Synapse

- All bindings use `wire:model`; method props throw on invalid names; icons are Heroicons; colours use Flux names (Synapse names kept as aliases).
- `confirm-button` no longer needs a `component` prop (it uses `$wire.$id`); the dialog event is `ui-confirm`.
- `password-generator` uses `crypto.getRandomValues()` with unbiased sampling instead of `Math.random()`.
- `drag-n-drop` checks type, size and image dimensions before uploading (Synapse skipped them when bound), and implements `max-width`/`max-height`.
- `file-drop` escapes its label.
- The notification list loads lazily, so pages no longer mark notifications read just by rendering.
- `adv-select` follows server-side changes of its property and counts `min-search-length` inclusively.
- The search palette is replaced by a provider-based command palette (no `searchables` table).

### Security

- Links built from stored or provider data (notifications, command-palette results, `stat-tile` `href`, `form-action-bar` `cancel-url`) only accept same-origin paths or http(s) URLs, and reject control characters and whitespace that browsers strip (`"/\t/evil.test"`).
- Client-writable Livewire state that gates features is `#[Locked]`; the notification gate is re-checked per request/job (safe under Octane and queue workers).
