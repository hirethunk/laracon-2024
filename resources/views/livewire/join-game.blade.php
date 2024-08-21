<div>
    <x-card>
        <h2 class="text-2xl text-gold-100">{{ $game->name }}</h2>
        <p class="text-sm text-white font-normal mt-4">
            You are not currently a player in this game. If you would like to join, please click the button below.
        </p>
        <div class="flex flex-row mt-4 space-x-4 justify between">
            <x-primary-button
                wire:click="requestJoinGame"
                wire:loading.attr="disabled"
                color="gold"
            >
                Request to join
            </x-primary-button>
        </div>
    </x-card>
</div>
