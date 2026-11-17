{{--
    File drop: multi-file upload zone. wire:model goes on the native input, so
    Livewire uploads the files (use WithFileUploads). Validate on the server.

    <x-ui::file-drop wire:model="docs" multiple :max-count="3" :current-count="count($existing)"
        :allowed-types="['pdf', 'jpg']" :max-size-kb="5120" :error="$errors->first('docs.*')" />
--}}
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/file-drop.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<div
    x-data="uiFileDrop"
    {{ $attributes->whereDoesntStartWith('wire:model')->class('w-full space-y-2') }}
>
    @if ($isFull())
        <p class="{{ \VmEngine\UiKit\Support\Styles::DROPZONE }}">{{ $resolvedFullText() }}</p>
    @else
        <label
            class="{{ \VmEngine\UiKit\Support\Styles::DROPZONE }} cursor-pointer focus-within:ring-2 focus-within:ring-accent"
            x-bind:class="isDragging ? 'ring-2 ring-accent' : ''"
            x-on:dragover.prevent="isDragging = true"
            x-on:dragenter.prevent="isDragging = true"
            x-on:dragleave.prevent="isDragging = false"
            x-on:drop.prevent="handleDrop($event)"
        >
            <flux:icon
                name="cloud-arrow-up"
                class="size-8 text-zinc-400"
            />
            <span>
                {{ $resolvedLabel() }}
                <span class="font-medium text-accent-content underline">{{ __('ui-kit::ui.browse') }}</span>
            </span>
            @if ($resolvedHint() !== '')
                <span class="text-xs">{{ $resolvedHint() }}</span>
            @endif
            @if ($counterText() !== null)
                <span class="text-xs font-medium">{{ $counterText() }}</span>
            @endif
            <input
                type="file"
                class="sr-only"
                x-ref="input"
                name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                @if ($acceptString() !== '') accept="{{ $acceptString() }}" @endif
                @if ($multiple) multiple @endif
                {{ $attributes->whereStartsWith('wire:model') }}
            >
        </label>
    @endif

    @if ($wireModelName() !== null)
        <p
            wire:loading
            wire:target="{{ $wireModelName() }}"
            class="flex items-center gap-2 {{ \VmEngine\UiKit\Support\Styles::MUTED }}"
        >
            <flux:icon.loading class="size-4" /> {{ $resolvedUploadingText() }}
        </p>
    @endif

    @if (filled($error))
        <p
            class="text-sm text-red-600 dark:text-red-400"
            role="alert"
        >{{ $error }}</p>
    @endif
</div>
