<div>
    <x-card>
        @if($this->player->is_active)
            <div class="flex flex-col">
                <p class="pb-4 text-sm text-neutral-300">
                    Had enough? If you resign, your score will be given to the player you choose below.
                </p>
                <x-form.select
                    label="Beneficiary"
                    name="beneficiary"
                    custom="w-6/12"
                    wire:model="beneficiary_id"
                    :options="$this->players->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
                    selected="Choose a Player"
                />
            </div>

            <div class="flex justify-between items-center mt-4">
                <x-danger-button wire:click="resign" wire:loading.attr="disabled">
                    Resign
                </x-danger-button>
            </div>
        @else
            <p class="text-sm text-neutral-300">You have resigned.</p>

            <div class="flex justify-between items-center mt-2">
                <div>
                    @isset($this->beneficiary)
                        <p>
                            Beneficiary:
                            <span class="text-gold-500">{{ $this->beneficiary }}</span>
                        </p>
                    @endisset
                </div>
            </div>
        @endif
    </x-card>
</div>
