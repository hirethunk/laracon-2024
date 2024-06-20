<div>
    <x-card>
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
        <x-danger-button wire:click="resign" wire:loading.attr="disabled" class="mt-8">
            Resign
        </x-danger-button>
    </x-card>
</div>
