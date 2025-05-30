<x-filament-panels::page.simple >
    @if($this->recentlySuccessful)
        <p class="text-lg font-semibold">
            Declarația a fost încărcată cu succes. Îți mulțumim pentru contribuția ta. Nu uita să încarci toate declarațiile aferente persoanei care ți-a fost alocată din toți anii pentru care acestea sunt disponibile pe site-ul ANI. Dacă ai mai mult timp la dispoziție, ia legătura cu echipa pentru a ți se aloca o nouă persoană pentru care să operezi căutarea. Mulțumim.
        </p>

        {{$this->refreshAction}}
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
