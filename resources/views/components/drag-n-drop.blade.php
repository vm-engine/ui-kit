{{--
    Drag & drop (single file, image preview). With wire:model the file is checked in the
    browser and uploaded with $wire.upload() (use WithFileUploads); without it, it is a
    plain `name` input for a normal form. Always validate on the server too.

    <x-ui::drag-n-drop wire:model="avatar" :max-size="2048" :max-width="1024" :error="$errors->first('avatar')" />
--}}
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/drag-n-drop.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<div
    x-data="uiDragNDrop(@js($config()))"
    {{ $attributes->whereDoesntStartWith('wire:model')->class('w-full space-y-2') }}
>
    <label
        x-show="!fileName"
        class="{{ \VmEngine\UiKit\Support\Styles::DROPZONE }} cursor-pointer focus-within:ring-2 focus-within:ring-accent"
        x-bind:class="isDragging ? 'ring-2 ring-accent' : ''"
        x-on:dragover.prevent="isDragging = true"
        x-on:dragenter.prevent="isDragging = true"
        x-on:dragleave.prevent="isDragging = false"
        x-on:drop.prevent="onDrop($event)"
    >
        <flux:icon
            name="arrow-up-tray"
            class="size-8 text-zinc-400"
        />
        <span class="font-medium text-zinc-700 dark:text-zinc-200">{{ $label }}</span>
        <span>{{ $description }}</span>
        <span class="font-medium text-accent-content underline">{{ $browseText }}</span>
        <input
            type="file"
            class="sr-only"
            x-ref="fileInput"
            name="{{ $name }}"
            accept="{{ $accept }}"
            x-on:change="pick($event.target.files)"
        >
    </label>

    <div
        x-show="fileName"
        x-cloak
        class="flex items-center gap-3 rounded-lg border border-zinc-200 p-3 dark:border-white/10"
    >
        <template x-if="previewUrl">
            <img
                x-bind:src="previewUrl"
                alt=""
                class="size-16 shrink-0 rounded-md object-cover"
            >
        </template>
        <div class="min-w-0 flex-1">
            <p
                class="truncate text-sm font-medium text-zinc-800 dark:text-white"
                x-text="fileName"
            ></p>
            <p
                class="{{ \VmEngine\UiKit\Support\Styles::MUTED }}"
                x-text="fileSize"
            ></p>
            <div
                x-show="uploading"
                class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-zinc-200 dark:bg-white/10"
            >
                <div
                    class="h-full bg-accent transition-all"
                    x-bind:style="'width: ' + progress + '%'"
                ></div>
            </div>
        </div>
        <flux:button
            type="button"
            size="xs"
            variant="ghost"
            icon="x-mark"
            aria-label="{{ __('ui-kit::ui.remove') }}"
            x-on:click="removeFile()"
        />
    </div>

    <p
        x-show="error"
        x-cloak
        x-text="error"
        class="text-sm text-red-600 dark:text-red-400"
        role="alert"
    ></p>
    @if (filled($error))
        <p
            class="text-sm text-red-600 dark:text-red-400"
            role="alert"
        >{{ $error }}</p>
    @endif
</div>
