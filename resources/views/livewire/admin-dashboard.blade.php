<div>
    {{-- Approve Users --}}
    <x-card>
        <h2 class="text-lg text-gold-500 font-serif font-medium">Approve Users<h2>

        <div class="flex flex-col">
            <p class="mt-1 pb-4 text-sm text-neutral-300">
                A User's name <span class="text-gold-500">must</span> match their Laracon Badge
            </p>

            <x-form.autocomplete
                label="Unapproved Users"
                selected="user_id"
                search="search"
                :options="$this->options"
                :clear="$user_id"
            />
        </div>

        <div class="flex space-x-4 items-center mt-4">
            <x-primary-button wire:click="approve" wire:loading.attr="disabled">
                Approve
            </x-primary-button>

            <x-danger-button wire:click="reject" wire:loading.attr="disabled">
                Reject
            </x-danger-button>
        </div>
    </x-card>
</div>
