<div>
    <div class="py-12 bg-black">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-white border-amber-400 border-8 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col">
                        Had enough? If you resign, your score will be given to the player you choose below. 
                        <div class="mt-8">
                            <x-form.select
                                label="Beneficiary"
                                name="beneficiary"
                                custom="w-6/12"
                                wire:model="beneficiary_id"
                                :options="$this->players->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
                                selected="Choose a Player"
                            />
                        </div>
                    </div>
                    <x-primary-button wire:click="resign" wire:loading.attr="disabled" class="mt-8">
                        Resign
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>
</div>
