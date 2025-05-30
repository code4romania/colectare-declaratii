<x-filament-panels::page.simple>
    @if ($this->recentlySuccessful)
        {{-- <x-alert
            icon="heroicon-s-check-circle"
            color="success"
            :title="__('app.request.sent.title')"
            :message="__('app.request.sent.message')" /> --}}
    @else
        <x-filament-panels::form wire:submit="handle">
            {{ $this->form }}

            <div class="flex justify-between w-full gap-4">
                <div>
                    @session('error')
                        <div class="p-4 border-l-4 border-danger-400 bg-danger-50">
                            <div class="flex gap-3">
                                {{-- <x-heroicon-s-exclamation class="w-5 h-5 text-danger-400 shrink-0" /> --}}

                                <p class="text-sm text-danger-700">
                                    {{ $value }}
                                </p>
                            </div>
                        </div>
                    @endsession
                </div>

                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()" />
            </div>
        </x-filament-panels::form>
    @endif
</x-filament-panels::page.simple>
