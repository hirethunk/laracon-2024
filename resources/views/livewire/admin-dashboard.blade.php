<div>

    {{-- Approve Users --}}
    <x-card>
        <h1 class="text-lg text-gold-500 cinzel mb-4">Approve Users<h1>

        <div class="flex flex-col">
            <p class="text-sm">A User's name <span class="text-gold-500">must</span> match their Laracon Badge</p>
            <div class="mt-8">
                <x-form.select
                    label="Unapproved Users"
                    name="user"
                    wire:model.live="user_id"
                    :options="$this->options"
                    selected="Select a User"
                />
            </div>
        </div>
        <x-primary-button wire:click="approve" wire:loading.attr="disabled" class="mt-8" color="gold">
            Approve
        </x-primary-button>
    </x-card>
</div>
