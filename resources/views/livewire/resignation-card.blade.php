<div>
    <x-card>
        @if($this->player->is_active)
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

            <div class="flex justify-between items-center mt-8">
                <x-danger-button wire:click="resign" wire:loading.attr="disabled">
                    Resign
                </x-danger-button>
            </div>
        @else
            <h2>You have resigned.</h2>

            <div class="flex justify-between items-center mt-8">
                <div>
                    @isset($this->beneficiary)
                        <p class="mb-2">Beneficiary</p>
                        <p class="text-gold-900">{{ $this->beneficiary }}</p>
                    @endisset
                </div>
            </div>
        @endif
    </x-card>
</div>
