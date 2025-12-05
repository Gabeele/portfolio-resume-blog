<div>
    <form wire:submit="save">
        {{ $this->form }}

        <div style="margin-top: 1rem;">
            <x-filament::button wire:click="save">
                Save and Publish
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
