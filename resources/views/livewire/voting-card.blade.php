<div>
    <x-card>
        @if ($player_can_vote)
            <div class="flex flex-col">
                Once per hour, you can upvote someone, and downvote someone. Vote wisely.
                <div class="mt-8">
                    <x-form.select
                        label="Upvote"
                        name="upvote_target"
                        custom="w-6/12"
                        wire:model="upvote_target_id"
                        :options="$this->upvote_options->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
                        selected="Choose a Player"
                    />
                </div>
                <div class="mt-4">
                    <x-form.select
                        label="Downvote"
                        name="downvote_target"
                        custom="w-6/12"
                        wire:model="downvote_target_id"
                        :options="$this->downvote_options->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
                        selected="Choose a Player"
                    />
                </div>
            </div>

            <div class="flex justify-between items-center mt-8">
                <x-primary-button wire:click="vote" wire:loading.attr="disabled" color="gold">
                    Vote
                </x-primary-button>
            </div>
        @else
            <div class="text-center">
                <h1 class="text-2xl sm:text-3xl font-bold text-gold-500">You can only vote once per hour</h1>
                <p class="mt-4">Vote again {{ $this->player->state()->lastVotedAt()->addHours(1)->diffForHumans() }}</p>
            </div>
        @endif
    </x-card>
</div>
