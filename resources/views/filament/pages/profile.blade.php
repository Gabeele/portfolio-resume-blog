<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 py-5 flex justify-end" style="margin-top: 1.5rem">
            <button
                type="submit"
                class="fi-btn fi-btn-color-primary fi-btn-size-md inline-flex items-center justify-center gap-1.5 rounded-lg border border-transparent px-3 py-2 text-sm font-semibold shadow-sm ring-1 transition duration-75 focus-visible:outline-none focus-visible:ring-2 disabled:pointer-events-none disabled:opacity-70 bg-primary-600 text-white ring-primary-600/10 hover:bg-primary-500 dark:bg-primary-500 dark:text-white dark:ring-primary-500/20 dark:hover:bg-primary-400"
            >
                Save Changes
            </button>
        </div>
    </form>
</x-filament-panels::page>
