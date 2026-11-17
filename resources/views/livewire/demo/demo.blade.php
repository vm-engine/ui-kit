<div class="space-y-10">
    <div>
        <flux:heading
            size="xl"
            level="1"
        >{{ __('UI kit demo') }}</flux:heading>
        <flux:text>{{ __('Every vm-engine/ui-kit component, for light and dark checks.') }}</flux:text>
    </div>

    <section
        id="empty-state"
        class="space-y-3"
    >
        <flux:heading size="lg">empty-state</flux:heading>
        <flux:card>
            <x-ui::empty-state
                icon="users"
                title="No members yet"
                text="Invite the first member to get started."
            >
                <flux:button
                    variant="primary"
                    icon="plus"
                >New member</flux:button>
            </x-ui::empty-state>
        </flux:card>
    </section>

    <section
        id="stat-tile"
        class="space-y-3"
    >
        <flux:heading size="lg">stat-tile</flux:heading>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui::stat-tile
                icon="document-text"
                color="blue"
                label="Total invoices"
                value="2,613"
            />
            <x-ui::stat-tile
                icon="users"
                color="green"
                label="Members"
                value="0"
                value-color="green"
            />
            <x-ui::stat-tile
                :bordered="false"
                label="Plain quick stat"
                value="48%"
            />
            <x-ui::stat-tile
                icon="exclamation-triangle"
                color="orange"
                :icon-background="false"
                label="Bare icon"
                value="7"
            />
            <x-ui::stat-tile
                variant="card"
                icon="clock"
                color="red"
                tinted
                label="Stuck transactions"
                value="4,277"
                sublabel="> 30 min old"
            />
            <x-ui::stat-tile
                variant="card"
                icon="banknotes"
                icon-position="left"
                color="indigo"
                label="Revenue"
                value="Rp 1.250.000"
                sublabel="+12% vs last month"
                sublabel-color="green"
            />
            <x-ui::stat-tile
                variant="card"
                icon="chart-bar"
                icon-position="background"
                color="purple"
                label="Sessions"
                value="18,204"
            />
            <x-ui::stat-tile
                variant="card"
                href="#stat-tile"
                label="Clickable card"
                value="0"
                sublabel="+0% vs yesterday"
            />
        </div>
    </section>

    <section
        id="per-page-selector"
        class="space-y-3"
    >
        <flux:heading size="lg">per-page-selector</flux:heading>
        <flux:card class="space-y-3">
            <flux:text>{{ implode(', ', $this->rows->items()) }}</flux:text>
            <x-ui::per-page-selector :paginator="$this->rows" />
        </flux:card>
    </section>

    <section
        id="form-action-bar"
        class="space-y-3"
    >
        <flux:heading size="lg">form-action-bar</flux:heading>
        <flux:card class="space-y-4 p-0">
            <div class="space-y-4 p-6">
                <flux:input label="Name" />
                <flux:textarea
                    label="Notes"
                    rows="3"
                />
            </div>
            <x-ui::form-action-bar
                action="save"
                :cancel-url="url('/')"
            />
        </flux:card>
    </section>

    <section id="copy-button" class="space-y-3">
        <flux:heading size="lg">copy-button</flux:heading>
        <flux:card class="flex flex-wrap items-center gap-6">
            <span class="font-mono text-sm">/storage/reports/2026-11.pdf</span>
            <x-ui::copy-button text="/storage/reports/2026-11.pdf" />
            <x-ui::copy-button text="tok_9f2c1d" label="Copy token" :icon-only="false" />
            <textarea data-paste-target class="h-10 flex-1 rounded border border-zinc-200 p-2 text-sm dark:border-white/10" placeholder="paste here"></textarea>
        </flux:card>
    </section>

    <section id="password-generator" class="space-y-3">
        <flux:heading size="lg">password-generator</flux:heading>
        <flux:card class="max-w-md space-y-3">
            <x-ui::password-generator :model="['password', 'password_confirmation']" :length="16" />
            <flux:text>password: <span class="font-mono" data-demo-password>{{ $password }}</span> / confirmation: <span class="font-mono">{{ $password_confirmation }}</span></flux:text>
        </flux:card>
    </section>

    <section id="confirm-button" class="space-y-3">
        <flux:heading size="lg">confirm-button + confirm-dialog</flux:heading>
        <flux:card class="divide-y divide-zinc-200 p-0 dark:divide-white/10">
            @foreach ($this->tokenRows() as $row)
                <div class="flex items-center justify-between px-4 py-2" data-row="{{ $row['name'] }}">
                    <span>{{ $row['name'] }}</span>
                    <div class="flex items-center gap-2">
                        <x-ui::confirm-button method="archive" :token="$row['delete_token']" label="Archive" icon="archive-box" color="blue" labelled title="Archive {{ $row['name'] }}?" message="It can be restored later." />
                        <x-ui::confirm-button method="delete" :token="$row['delete_token']" label="Delete" title="Delete {{ $row['name'] }}?" message="This cannot be undone." />
                    </div>
                </div>
            @endforeach
        </flux:card>
    </section>

    <section id="tabs" class="space-y-3">
        <flux:heading size="lg">tabs</flux:heading>
        <div class="grid gap-4 lg:grid-cols-2">
            <flux:card>
                <x-ui::tabs data-demo="client-tabs">
                    <x-slot:tabs>
                        <x-ui::tabs.tab name="overview" icon="home">Overview</x-ui::tabs.tab>
                        <x-ui::tabs.tab name="activity" icon="clock">Activity</x-ui::tabs.tab>
                        <x-ui::tabs.tab name="settings" icon="cog-6-tooth">Settings</x-ui::tabs.tab>
                    </x-slot:tabs>
                    <x-ui::tabs.panel name="overview"><flux:text>Overview panel (client-only).</flux:text></x-ui::tabs.panel>
                    <x-ui::tabs.panel name="activity"><flux:text>Activity panel.</flux:text></x-ui::tabs.panel>
                    <x-ui::tabs.panel name="settings"><flux:text>Settings panel.</flux:text></x-ui::tabs.panel>
                </x-ui::tabs>
            </flux:card>
            <flux:card>
                <x-ui::tabs wire:model.live="tab" data-demo="bound-tabs">
                    <x-slot:tabs>
                        <x-ui::tabs.tab name="profile" icon="user">Profile</x-ui::tabs.tab>
                        <x-ui::tabs.tab name="security" icon="lock-closed">Security</x-ui::tabs.tab>
                    </x-slot:tabs>
                    <x-ui::tabs.panel name="profile"><flux:text>Profile panel.</flux:text></x-ui::tabs.panel>
                    <x-ui::tabs.panel name="security"><flux:text>Security panel.</flux:text></x-ui::tabs.panel>
                </x-ui::tabs>
                <flux:text class="mt-3">Server-side $tab: <span data-demo-tab>{{ $tab }}</span></flux:text>
            </flux:card>
        </div>
    </section>

    <section id="steps" class="space-y-3">
        <flux:heading size="lg">steps</flux:heading>
        <flux:card class="space-y-4">
            <x-ui::steps :steps="['Details', 'Media', 'Review']" :current="$step" go-method="goToStep" />
            <flux:button size="sm" wire:click="nextStep">Next step</flux:button>
        </flux:card>
    </section>

    <section id="split-pane" class="space-y-3">
        <flux:heading size="lg">split-pane</flux:heading>
        <flux:card>
            <x-ui::split-pane aside-width="16rem" resizable storage-key="demo-split" data-demo="split">
                <x-slot:aside>
                    <div class="rounded-lg bg-zinc-50 p-4 text-sm dark:bg-white/5">Aside: folders, filters…</div>
                </x-slot:aside>
                <div class="rounded-lg border border-dashed border-zinc-300 p-4 text-sm dark:border-white/20">Main content</div>
            </x-ui::split-pane>
        </flux:card>
    </section>

    <section id="lightbox" class="space-y-3">
        <flux:heading size="lg">lightbox</flux:heading>
        <flux:card>
            <x-ui::lightbox name="preview" />
            <button type="button" data-demo="open-lightbox" x-data x-on:click="$dispatch('open-lightbox-preview', { url: @js($this->sampleImageUrl()), alt: 'Sample image' })">
                <img src="{{ $this->sampleImageUrl() }}" alt="Sample image thumbnail" class="h-16 rounded-lg">
            </button>
        </flux:card>
    </section>

    <section id="currency" class="space-y-3">
        <flux:heading size="lg">currency</flux:heading>
        <flux:card class="grid max-w-xl gap-4">
            <div class="space-y-1" data-demo="currency-raw">
                <x-ui::currency currency="Rp" raw :decimals="2" wire:model.live="amount" />
                <flux:text>Raw value: <span class="font-mono" data-demo-amount>{{ var_export($amount, true) }}</span></flux:text>
                <flux:button size="sm" wire:click="resetAmount">Reset</flux:button>
            </div>
            <div class="space-y-1" data-demo="currency-formatted">
                <x-ui::currency currency="$" wire:model.live="formattedAmount" />
                <flux:text>Formatted value: <span class="font-mono">{{ $formattedAmount }}</span></flux:text>
            </div>
        </flux:card>
    </section>

    <section id="color-picker" class="space-y-3">
        <flux:heading size="lg">color-picker</flux:heading>
        <flux:card class="space-y-2">
            <x-ui::color-picker wire:model.live="color" :swatches="['#ef4444', '#3b82f6', '#10b981', '#f59e0b']" />
            <flux:text>Bound colour: <span class="font-mono" data-demo-color>{{ $color }}</span></flux:text>
        </flux:card>
    </section>

    <section id="datepicker" class="space-y-3">
        <flux:heading size="lg">datepicker</flux:heading>
        <flux:card class="grid max-w-xl gap-5">
            <div class="space-y-1" data-demo="date">
                <x-ui::datepicker wire:model.live="expiresAt" placeholder="Pick a date" />
                <x-ui::datepicker wire:model.live="expiresAt" placeholder="Same property (second picker)" />
                <flux:text>expiresAt: <span class="font-mono" data-demo-date>{{ var_export($expiresAt, true) }}</span></flux:text>
                <flux:button size="sm" wire:click="clearDate">Clear</flux:button>
            </div>
            <div class="space-y-1" data-demo="month">
                <x-ui::datepicker mode="month" wire:model.live="period" placeholder="Pick a month" />
                <flux:text>period: <span class="font-mono" data-demo-period>{{ var_export($period, true) }}</span></flux:text>
            </div>
            <form method="GET" action="{{ route('ui-kit.demo') }}" class="space-y-1" data-demo="date-form">
                <x-ui::datepicker name="starts_on" placeholder="Unbound (plain form)" />
                <flux:button size="sm" type="submit">Submit GET</flux:button>
                <flux:text>request starts_on: <span class="font-mono">{{ request('starts_on') }}</span></flux:text>
            </form>
        </flux:card>
    </section>

    <section id="adv-select" class="space-y-3">
        <flux:heading size="lg">adv-select</flux:heading>
        <flux:card class="grid max-w-xl gap-5">
            <div class="space-y-1" data-demo="select-static">
                <x-ui::adv-select wire:model.live="status" :options="[['value' => 'draft', 'label' => 'Draft'], ['value' => 'published', 'label' => 'Published'], ['value' => 'archived', 'label' => 'Archived']]" />
                <flux:text>status: <span class="font-mono" data-demo-status>{{ var_export($status, true) }}</span></flux:text>
            </div>
            <div class="space-y-1" data-demo="select-tags">
                <x-ui::adv-select wire:model.live="tags" multiple allow-create placeholder="Tags" :options="[['value' => 'laravel', 'label' => 'laravel'], ['value' => 'livewire', 'label' => 'livewire'], ['value' => 'flux', 'label' => 'flux']]" />
                <flux:text>tags: <span class="font-mono" data-demo-tags>{{ json_encode($tags) }}</span></flux:text>
                <flux:button size="sm" wire:click="clearTags">Clear (server)</flux:button>
            </div>
            <div class="space-y-1" data-demo="select-remote">
                <x-ui::adv-select wire:model.live="personId" search-method="searchPeople" load-method="loadPeople" placeholder="Search people" />
                <flux:text>personId: <span class="font-mono" data-demo-person>{{ var_export($personId, true) }}</span></flux:text>
                <flux:button size="sm" wire:click="setPerson">Set to 42 (server)</flux:button>
            </div>
            <div class="space-y-1" data-demo="select-tree">
                <x-ui::adv-select wire:model.live="pageId" placeholder="Parent page" :options="[['value' => 1, 'label' => 'Home', 'depth' => 0], ['value' => 2, 'label' => 'About', 'depth' => 1], ['value' => 3, 'label' => 'Our Team', 'depth' => 2], ['value' => 4, 'label' => 'Contact', 'depth' => 1]]" />
                <flux:text>pageId: <span class="font-mono">{{ var_export($pageId, true) }}</span></flux:text>
            </div>
        </flux:card>
    </section>

    <section id="uploads" class="space-y-3">
        <flux:heading size="lg">drag-n-drop + file-drop</flux:heading>
        <div class="grid gap-4 lg:grid-cols-2">
            <flux:card class="space-y-3" data-demo="drag-n-drop">
                <x-ui::drag-n-drop wire:model="avatar" :max-size="2048" :max-width="2000" :max-height="2000" :error="$errors->first('avatar')" />
                <flux:button size="sm" wire:click="saveAvatar">Save avatar</flux:button>
            </flux:card>
            <flux:card class="space-y-3" data-demo="file-drop">
                <x-ui::file-drop wire:model="docs" multiple :max-count="3" :current-count="count($docs)" :allowed-types="['pdf', 'jpg', 'png']" :max-size-kb="5120" :error="$errors->first('docs') ?: $errors->first('docs.*')" />
                <flux:text>{{ count($docs) }} file(s) selected</flux:text>
                <flux:button size="sm" wire:click="saveDocs">Save files</flux:button>
            </flux:card>
        </div>
    </section>

    <section id="kanban" class="space-y-3">
        <flux:heading size="lg">kanban</flux:heading>
        <x-ui::kanban sort-method="moveItem" group="demo">
            @foreach (['todo' => ['To do', 'zinc'], 'doing' => ['Doing', 'blue'], 'done' => ['Done', 'green']] as $columnId => [$columnLabel, $columnColor])
                <x-ui::kanban.column :id="$columnId" :label="$columnLabel" :color="$columnColor" :count="count($board[$columnId])" wire:key="column-{{ $columnId }}">
                    @foreach ($board[$columnId] as $card)
                        <x-ui::kanban.card :id="$card['id']" wire:key="card-{{ $card['id'] }}">{{ $card['name'] }}</x-ui::kanban.card>
                    @endforeach
                </x-ui::kanban.column>
            @endforeach
        </x-ui::kanban>
        <flux:text>Last move: <span class="font-mono" data-demo-move>{{ $lastMove }}</span></flux:text>
    </section>

    <section id="calendar" class="space-y-3">
        <flux:heading size="lg">calendar</flux:heading>
        <flux:card>
            <x-ui::calendar :month="$month" :events="$this->calendarEvents()" nav-method="setMonth" event-method="openEvent" day-method="createOn" />
        </flux:card>
    </section>

    <section id="rte" class="space-y-3">
        <flux:heading size="lg">rte</flux:heading>
        <flux:card class="space-y-3" data-demo="rte">
            <x-ui::rte wire:model="body" :height="220" />
            <div class="flex items-center gap-2">
                <flux:button size="sm" wire:click="$refresh">Sync</flux:button>
                <flux:button size="sm" wire:click="clearBody">Clear (server)</flux:button>
                <flux:text>Server HTML: <span class="font-mono break-all" data-demo-body>{{ $body }}</span></flux:text>
            </div>
        </flux:card>
        @if ($this->hasFileManager())
        <flux:card class="space-y-3" data-demo="rte-fm">
            <flux:text>With the file-manager extension (fmImage / fmFile buttons):</flux:text>
            <x-ui::rte wire:model="docBody" extension="file-manager" :height="220" />
        </flux:card>
        <livewire:filemanager::file-picker />
        <script type="module" nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}">
            import fmJoditConfig from '{{ \VmEngine\FileManager\Http\Controllers\AssetController::url('fm-jodit.js') }}';
            // extraButtons both defines and appends fmImage/fmFile, so no `buttons` entry.
            const register = () => window.uiRte.extend('file-manager', () => ({
                options: { extraButtons: fmJoditConfig.extraButtons },
            }));
            window.uiRte ? register() : document.addEventListener('alpine:init', register);
        </script>
        @endif
    </section>

    <section id="notifications" class="space-y-3">
        <flux:heading size="lg">notifications bell + panel, command palette</flux:heading>
        <flux:card class="flex flex-wrap items-center gap-3">
            <flux:button size="sm" wire:click="sendNotification" data-demo="send-notification">Send me a notification</flux:button>
            <flux:text>The bell and the palette (Ctrl/⌘+K) appear where your layout places them (see the ui-kit README).</flux:text>
        </flux:card>
    </section>
</div>
