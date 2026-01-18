<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Links
        </x-slot>

        <div class="space-y-2">
            @foreach ($this->getLinks() as $link)
                <div class="flex items-center justify-between gap-3 rounded-lg bg-gray-50 px-3 py-2 dark:bg-white/5">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $link['label'] }}
                        </div>
                        <div class="text-xs font-mono text-gray-500 dark:text-gray-400 truncate">
                            {{ $link['url'] }}
                        </div>
                    </div>
                    <x-filament::button
                        x-data="{
                            copyToClipboard() {
                                const url = '{{ $link['url'] }}';
                                navigator.clipboard.writeText(url).then(() => {
                                    $tooltip('Copied!', { timeout: 2000 });
                                });
                            }
                        }"
                        @click="copyToClipboard"
                        size="sm"
                        icon="heroicon-o-clipboard"
                    >
                        Copy
                    </x-filament::button>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
