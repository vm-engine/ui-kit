# vm-engine/ui-kit

Blade, Alpine and Livewire components that sit next to **free [Flux](https://fluxui.dev)**: the pieces free Flux lacks (searchable select, date picker, calendar, kanban, uploads, tabs, rich-text editor, notifications bell, command palette, …) and a few it does less well (a confirm button that carries a delete token, a copy button that works over plain HTTP). They are ports of the Synapse v3 components, restyled with Tailwind utilities so they look native beside Flux in light and dark mode.

---

## Contents

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Demo page](#demo-page)
4. [Configuration](#configuration)
5. [Conventions](#conventions)
6. [Components](#components)
7. [Notifications](#notifications)
8. [Command palette](#command-palette)
9. [Rich-text editor](#rich-text-editor)
10. [Security notes](#security-notes)
11. [Livewire gotchas](#livewire-gotchas)
12. [Known limits](#known-limits)
13. [Testing the package](#testing-the-package)
14. [Licences](#licences)

---

## Requirements

- PHP 8.3+
- Laravel 13
- Livewire 4
- Flux (free) 2.20+
- Tailwind CSS 4 (already part of any Flux app)

There are **no npm dependencies**. The package ships its own small JS files plus vendored flatpickr and Jodit, served from a package route and loaded only on pages that use them.

## Installation

```bash
composer require vm-engine/ui-kit
```

**1. CSS.** Add one line to your `resources/css/app.css`, after the Flux import, then rebuild (`npm run build`):

```css
@import '../../vendor/livewire/flux/dist/flux.css';
@import '../../vendor/vm-engine/ui-kit/resources/css/ui-kit.css';
```

That file tells Tailwind to scan the package's views and classes, and adds the flatpickr theme and `[x-cloak]`.

**2. Layout.** Add these to your app layout once (outside any Livewire component):

```blade
<flux:toast />                       {{-- if your layout doesn't have it yet --}}
<x-ui::confirm-dialog />              {{-- used by <x-ui::confirm-button> --}}

{{-- optional --}}
<livewire:ui::notifications.bell />   {{-- in your header/toolbar --}}
<x-ui::notifications.panel />         {{-- the "View all" flyout --}}
<livewire:ui::command-palette />      {{-- Ctrl/⌘+K search --}}
```

Component JS needs Alpine on the page, which Livewire provides (`@fluxScripts` forces it on every Flux page).

**3. Optional publishing:**

```bash
php artisan vendor:publish --tag=ui-kit-config   # config/ui-kit.php
php artisan vendor:publish --tag=ui-kit-lang     # lang/vendor/ui-kit (en, id)
php artisan vendor:publish --tag=ui-kit-views    # resources/views/vendor/ui-kit
```

## Demo page

A page showing every component, for development: set `UI_KIT_DEMO=true` and open `/ui-kit-demo`.
It renders in your app layout (`ui-kit.demo.layout`, default: Livewire's `component_layout`) behind
`ui-kit.demo.middleware` (default `['web', 'auth']`), and adds a demo provider to the command palette.
Keep it off in production: it performs real actions (uploads, notifications). The rich-text section
also shows the file-manager extension when `vm-engine/file-manager` is installed.

## Configuration

`config/ui-kit.php`:

| Key | Default | Meaning |
|---|---|---|
| `assets_prefix` | `ui-kit-assets` (`UI_KIT_ASSETS_PREFIX`) | URL prefix of the asset route (`/ui-kit-assets/{file}`) |
| `notifications.enabled` | `false` (`UI_KIT_NOTIFICATIONS`) | Turn the bell and panel on (they also need the `notifications` table) |
| `notifications.dropdown_limit` | `5` | Rows in the bell dropdown |
| `notifications.per_page` | `15` | Rows per page in the panel |
| `notifications.poll_seconds` | `15` | Bell polling interval; `null` turns polling off |
| `command_palette.providers` | `[]` | `SearchProvider` classes, resolved from the container |
| `command_palette.limit_per_provider` | `5` | Most results taken from each provider |
| `demo.enabled` | `false` (`UI_KIT_DEMO`) | Register the `/ui-kit-demo` page (development only) |
| `demo.middleware` | `['web', 'auth']` | Middleware on the demo route |
| `demo.layout` | `null` | Livewire layout for the demo; `null` uses `livewire.component_layout` |

## Conventions

- **Binding is always `wire:model`** (with `.live`, `.blur`, `.debounce` as usual). Components that need the property name read it from that attribute.
- **Method props** (`method`, `go-method`, `sort-method`, `nav-method`, `search-method`, …) must be plain method names. Anything else throws `InvalidArgumentException` when the component renders.
- **Icons** are Heroicon names, rendered with `<flux:icon>` (`trash`, `user`, `document-text`).
- **Colours** are Flux palette names. Components with colour options accept `zinc`, `blue`, `indigo`, `purple`, `red`, `orange`, `green` (stat tile) or `zinc`, `accent`, `blue`, `green`, `amber`, `red`, `purple` (kanban, calendar). Synapse's names (`gray`, `brand`, `success`, `warning`, `danger`, `info`) still work as aliases.
- **Default text** comes from `ui-kit::ui` translations (English and Indonesian included).

## Components

### confirm-button + confirm-dialog

Opens the global `<x-ui::confirm-dialog />` and, on confirm, calls `method` on the surrounding Livewire component. Pass a model's **delete token** for deletes, never a raw id.

```blade
<x-ui::confirm-button method="delete" :token="$user->delete_token"
    :label="__('Delete')" :title="__('Delete user?')" :message="__('This cannot be undone.')" />

<x-ui::confirm-button method="archive" :param="$row->code" icon="archive-box" color="blue" labelled
    label="Archive" title="Archive this?" message="It can be restored later." />
```

| Prop | Default | |
|---|---|---|
| `method` | (required) | Livewire method to call |
| `label`, `title`, `message` | (required) | Button label (tooltip when icon-only), dialog title and text |
| `token` / `param` | `null` | The single argument passed to `method` (token for deletes) |
| `icon` | `trash` | Heroicon |
| `color` | `red` | `red` = danger styling, anything else = primary |
| `labelled` | `false` | Text button instead of an icon button |
| `confirm-text` | the label | Text of the dialog's confirm button |

The dialog listens for the `ui-confirm` window event, so you can also open it yourself: `$dispatch('ui-confirm', { title, message, confirmText, color, method, params, component: $wire.$id })`.

### copy-button

Copies text; falls back to `execCommand('copy')` where the async clipboard API is unavailable (plain HTTP).

```blade
<x-ui::copy-button text="/storage/report.pdf" />
<x-ui::copy-button :text="$token" label="Copy token" :icon-only="false" />
```

### tabs

```blade
<x-ui::tabs wire:model.live="tab">   {{-- or no wire:model: client-only, first tab selected --}}
    <x-slot:tabs>
        <x-ui::tabs.tab name="profile" icon="user">Profile</x-ui::tabs.tab>
        <x-ui::tabs.tab name="security" icon="lock-closed">Security</x-ui::tabs.tab>
    </x-slot:tabs>
    <x-ui::tabs.panel name="profile">…</x-ui::tabs.panel>
    <x-ui::tabs.panel name="security">…</x-ui::tabs.panel>
</x-ui::tabs>
```

WAI-ARIA tabs: ←/→, Home, End. Add `#[Url]` to the bound property to keep the tab in the URL. For tabs that are separate routes, use `flux:navbar` instead.

### adv-select

Searchable single/multi select.

```blade
<x-ui::adv-select wire:model.live="status" :options="[['value' => 'draft', 'label' => 'Draft'], …]" />
<x-ui::adv-select wire:model="tags" multiple allow-create :options="$tagOptions" />
<x-ui::adv-select wire:model="userId" search-method="searchUsers" load-method="loadUsers" />
```

| Prop | Default | |
|---|---|---|
| `options` | `[]` | `[['value' => …, 'label' => …, 'depth' => 0], …]`; `depth` indents tree options |
| `multiple` | `false` | Chips; Backspace on an empty search removes the last one |
| `search-method` | `null` | `fn (string $term): array` returning option rows, called from `min-search-length` characters (default 2) |
| `load-method` | `null` | `fn (int\|string\|array $values): array` returning the rows for preset values |
| `allow-create` | `false` | Enter on a search with no match creates an option from the text |
| `preload` | `null` | Already-selected option rows |
| `label-field`, `value-field` | `label`, `value` | Keys in the option rows |
| `placeholder`, `search-label`, `disabled`, `auto-load` | | |

Keyboard: ↓/Enter/Space open, ↑/↓ move, Enter selects, Escape closes. The select also follows server-side changes of the property (a reset after save).

### datepicker

flatpickr bound to a property, or posted by `name`. It never falls back to the browser's native date input (also on phones).

```blade
<x-ui::datepicker wire:model.live="expiresAt" :options="['minDate' => 'today']" />
<x-ui::datepicker mode="month" wire:model="period" />   {{-- submits Y-m --}}
<x-ui::datepicker name="starts_on" />                    {{-- plain form --}}
```

`mode` is `date` (default, `Y-m-d`) or `month`. `options` are extra flatpickr options.

### currency

```blade
<x-ui::currency currency="Rp" raw :decimals="2" wire:model.live="amount" />   {{-- shows 1.250.000,50, sends "1250000.50" --}}
<x-ui::currency currency="$" wire:model="formatted" />                          {{-- sends the formatted text --}}
```

With `raw`, a `.live` model is sent once typing pauses (150ms, or the `.debounce.Nms` you give).

### color-picker

```blade
<x-ui::color-picker wire:model="color" :swatches="['#ef4444', '#3b82f6', '#10b981']" />
```

`#rrggbb` values; `allow-custom` (default `true`) adds the native picker and a hex field. Validate with `hex_color`.

### drag-n-drop

Single file with preview. Bound with `wire:model` (use `WithFileUploads`), it checks type, size and image dimensions in the browser, then uploads with `$wire.upload()` and shows progress. Without `wire:model` it is a plain `name` input for a normal form.

```blade
<x-ui::drag-n-drop wire:model="avatar" :max-size="2048" :max-width="1024" :max-height="1024"
    :error="$errors->first('avatar')" />
```

Props: `accept` (MIME list, default PNG/JPEG/WebP), `max-size` (KB), `max-width`, `max-height`, `preview`, `name`, `label`, `description`, `browse-text`, `error`.

### file-drop

Multi-file drop zone; `wire:model` goes on the native input, so Livewire handles the uploads.

```blade
<x-ui::file-drop wire:model="docs" multiple :max-count="3" :current-count="count($existing)"
    :allowed-types="['pdf', 'jpg']" :max-size-kb="5120" :error="$errors->first('docs.*')" />
```

Props: `multiple`, `max-count`, `current-count`, `allowed-types`, `max-size-kb`, `label`, `hint`, `name`, `error`, `full-text`, `uploading-text`.

### kanban

Native Livewire 4 `wire:sort`. Dropping a card calls `sort-method($itemId, $position, $columnId)`.

```blade
<x-ui::kanban sort-method="moveItem">
    <x-ui::kanban.column id="todo" label="To do" color="zinc" :count="count($todo)">
        @foreach ($todo as $item)
            <x-ui::kanban.card :id="$item->id" wire:key="card-{{ $item->id }}">{{ $item->name }}</x-ui::kanban.card>
        @endforeach
    </x-ui::kanban.column>
</x-ui::kanban>
```

The board scrolls sideways inside its container. Wrap buttons inside a card in `wire:sort:ignore`.

### calendar

Month event grid (md+) with an agenda list on small screens.

```blade
<x-ui::calendar :month="$month" :events="$events" nav-method="setMonth"
    event-method="openEvent" day-method="createOn" week-start="monday" />
```

`month` is `Y-m` (default: current month). Events are `['id' => …, 'date' => 'Y-m-d', 'title' => …, 'color' => 'blue']`; events with an id that isn't `[A-Za-z0-9_-]` or with an invalid date are dropped. `nav-method('Y-m')`, `event-method('id')`, `day-method('Y-m-d')`.

### steps, split-pane, lightbox

```blade
<x-ui::steps :steps="['Details', 'Media', 'Review']" :current="$step" go-method="goToStep" />

<x-ui::split-pane aside-width="20rem" resizable storage-key="folders">
    <x-slot:aside>…</x-slot:aside>
    …main…
</x-ui::split-pane>

<x-ui::lightbox name="preview" />
<button x-on:click="$dispatch('open-lightbox-preview', { url: '/img.jpg', alt: 'Cover' })">…</button>
```

The split pane remembers its width per `storage-key` and resizes by drag or ←/→ on the separator; below `lg` the aside is toggled by a button. The lightbox traps focus on its close button and returns focus on close.

### stat-tile, empty-state, per-page-selector, form-action-bar, password-generator

```blade
<x-ui::stat-tile icon="document-text" color="blue" label="Total invoices" value="2,613" />
<x-ui::stat-tile variant="card" icon="clock" color="red" tinted label="Stuck" value="4,277" sublabel="> 30 min old" />

<x-ui::empty-state icon="users" title="No members yet" text="Invite the first one.">
    <flux:button variant="primary">Invite</flux:button>
</x-ui::empty-state>

<x-ui::per-page-selector :paginator="$users" />          {{-- binds $perPage (10/15/25/50) + pagination links --}}

<x-ui::form-action-bar action="save" :cancel-url="route('users.index')" />   {{-- sticky bar for long page forms --}}

<x-ui::password-generator :model="['password', 'password_confirmation']" :length="16" />
```

Stat tile props (Synapse parity): `icon`, `color`, `label`, `value`, `value-color`, `bordered`, `variant` (`tile`/`card`), `icon-position` (`left`/`title`/`background`), `icon-background`, `tinted`, `sublabel`, `sublabel-color`, `href`, `clickable`.

## Notifications

In-app notifications on Laravel's stock `database` channel.

1. Create the table if you don't have it: `php artisan make:notifications-table && php artisan migrate`.
2. Set `ui-kit.notifications.enabled` to `true`.
3. Embed `<livewire:ui::notifications.bell />` and `<x-ui::notifications.panel />` (two separate tags: Livewire 4 can't nest a namespaced child in another component's template).

**Data shape** (`toArray()` / `toDatabase()`):

```php
return [
    'title' => 'notifications.invoice_paid.title',      // translation key or plain text
    'message' => 'notifications.invoice_paid.message',
    'params' => ['number' => $this->invoice->number],   // used by title and message
    'url' => route('invoices.show', $this->invoice, absolute: false),
    'icon' => 'document-text',                          // Heroicon
    'toast' => true,                                    // pop a Flux toast when the bell picks it up
];
```

Title and message are translated when displayed, in the viewer's locale. The bell polls every `poll_seconds`; opening it marks the shown rows read (they keep their unread styling until the page reloads). The panel's list loads lazily, only once the flyout is open, so rows are never marked read just by loading a page.

## Command palette

`<livewire:ui::command-palette />` searches over **providers** you register:

```php
use Illuminate\Contracts\Auth\Authenticatable;
use VmEngine\UiKit\CommandPalette\SearchProvider;
use VmEngine\UiKit\CommandPalette\SearchResult;

class UserSearch implements SearchProvider
{
    public function search(string $term, Authenticatable $user, int $limit): iterable
    {
        if (! $user->can('users.view')) {
            return [];
        }

        return User::where('name', 'like', "%{$term}%")->limit($limit)->get()
            ->map(fn (User $u) => new SearchResult($u->name, route('users.edit', $u, absolute: false), $u->email, 'user', 'Users'));
    }
}
```

```php
// config/ui-kit.php
'command_palette' => ['providers' => [App\Search\UserSearch::class]],
```

- **Each provider is responsible for permissions**: return only what the user may open.
- Search starts at 2 characters; each provider gets at most `limit_per_provider` results.
- A provider that throws is logged (`warning`) and skipped. Results whose `url` isn't a relative path or an http(s) URL are dropped.
- Ctrl/⌘+K opens the palette; ↑/↓ move, Enter opens the result with `wire:navigate`.
- Place the palette in a container that is always rendered: its dialog can't show inside an ancestor with `display: none`.

## Rich-text editor

```blade
<x-ui::rte wire:model="form.body" :height="400" />
```

Jodit (vendored), synced like `wire:model` (deferred). It follows server-side changes of the property, uses Jodit's dark theme when the page loads in dark mode, and falls back to a plain bound textarea if Jodit fails to load.

**Extensions** add buttons and Jodit options:

```js
window.uiRte.extend('name', (rte, config) => ({
    buttons: ['myButton'],      // appended to the toolbar
    options: { /* merged into Jodit.make() options */ },
    destroy() {},
}));
```

```blade
<x-ui::rte wire:model="body" extension="name" :extension-config="['disk' => 'public']" />
```

**With vm-engine/file-manager** (its `extraButtons` both define and append `fmImage`/`fmFile`, so pass only `options`):

```blade
<livewire:filemanager::file-picker />
<script type="module" nonce="{{ Vite::cspNonce() }}">
    import fmJoditConfig from '{{ \VmEngine\FileManager\Http\Controllers\AssetController::url('fm-jodit.js') }}';
    const register = () => window.uiRte.extend('file-manager', () => ({
        options: { extraButtons: fmJoditConfig.extraButtons },
    }));
    window.uiRte ? register() : document.addEventListener('alpine:init', register);
</script>
<x-ui::rte wire:model="body" extension="file-manager" />
```

## Security notes

- **The editor does not sanitise HTML.** Purify it before you render it (for example with `mews/purifier`).
- **Upload checks in the browser only help the user.** Always validate uploads on the server (`image`, `mimes`, `max`, `dimensions`).
- **Deletes:** pass a delete token to `confirm-button`, never a record id.
- Method props are validated as plain identifiers, and data keys (card ids, event ids) are restricted to `[A-Za-z0-9_-]`, so props can't inject script into Livewire expressions.
- Notification and search-result URLs are only used as links when they are relative paths or http(s) URLs.
- Scripts carry `Vite::cspNonce()`. The components are not tested with Livewire's CSP-safe Alpine build.

## Livewire gotchas

- **Clearing a typed property:** Livewire never hydrates `null` into a typed property, so `public ?string $amount = '100';` snaps back to `'100'` on the next request after you set it to `null`. Give such properties a `null` default and set their initial value in `mount()`.
- **Slots in Blade strings:** keep whitespace after a closing named slot (`</x-slot:aside> …`). `</x-slot:aside>B` compiles to `@endslotB`, which Blade doesn't recognise.

## Known limits

- flatpickr month and day names are English.
- Jodit picks its light/dark theme when the page loads; switching appearance applies on the next page load.
- The bell's polling cursor has one-second resolution; a notification created in the same second as a poll can toast twice.

## Testing the package

```bash
cd packages/ui-kit
composer install
vendor/bin/phpstan analyse --memory-limit=1G
vendor/bin/pint
vendor/bin/pest
```

## Licences

ui-kit is MIT licensed. Bundled third-party files (licences alongside them):

- [flatpickr](https://flatpickr.js.org) 4.6.13 and its monthSelect plugin: MIT, `resources/dist/vendor/flatpickr/LICENSE`
- [Jodit](https://xdsoft.net/jodit/) 4.18.4: MIT, `resources/dist/vendor/jodit/LICENSE`
