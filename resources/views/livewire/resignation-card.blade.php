<div>
    <x-card>
        @if($this->player->is_active)
            <div class="flex flex-col">
                <p class="pb-4 text-sm text-neutral-300">
                    Had enough? Feel free to resign. You will no longer be able to vote.
                    If you resign, your score will be given to the player you choose below.
                </p>
                <x-form.autocomplete
                    label="beneficiary"
                    selected="beneficiary_id"
                    search="search"
                    :options="$this->options"
                />
            </div>

            @if (session()->has('error'))
                <div class="pt-4 text-red-600">
                    {{ session('error') }}
                </div>
            @endif

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
