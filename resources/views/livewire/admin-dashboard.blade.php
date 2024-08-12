<div>
    {{-- Approve Users --}}
    <x-card>
        <h1 class="text-lg text-gold-500 font-serif font-medium">Approve Users<h1>

        <div class="flex flex-col">
            <p class="mt-1 pb-4 text-sm text-neutral-300">
                A User's name <span class="text-gold-500">must</span> match their Laracon Badge
            </p>
            <x-form.select
                label="Unapproved Users"
                name="user"
                wire:model.live="user_id"
                :options="$this->options"
                selected="Select a User"
            />
        </div>

        <div class="flex justify-between items-center mt-4">
            <x-primary-button wire:click="approve" wire:loading.attr="disabled">
                Approve
            </x-primary-button>
        </div>
    </x-card>
</div>
