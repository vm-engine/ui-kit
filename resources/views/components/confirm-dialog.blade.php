{{--
    Confirm dialog: place ONCE in the layout, outside any Livewire component.
    <x-ui::confirm-button> dispatches `ui-confirm` with { title, message, confirmText,
    color, method, params, component }; Confirm calls component[method](...params).
--}}
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/confirm-dialog.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<div
    x-data="uiConfirmDialog"
    x-on:ui-confirm.window="open($event.detail)"
>
    <flux:modal
        name="ui-confirm-dialog"
        class="md:w-96"
    >
        <div class="space-y-6">
            <div class="space-y-2">
                <flux:heading size="lg"><span x-text="title"></span></flux:heading>
                <flux:text><span x-text="message"></span></flux:text>
            </div>
            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('ui-kit::ui.cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button
                    variant="danger"
                    x-show="isDanger()"
                    x-on:click="confirm()"
                ><span x-text="confirmText"></span></flux:button>
                <flux:button
                    variant="primary"
                    x-show="! isDanger()"
                    x-on:click="confirm()"
                ><span x-text="confirmText"></span></flux:button>
            </div>
        </div>
    </flux:modal>
</div>
