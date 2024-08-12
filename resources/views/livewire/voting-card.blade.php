<div>
    <x-card>
        @if ($this->can_vote)
            <div class="flex flex-col">
                <p class="pb-4 text-sm text-neutral-300">
                    Once per hour, you can upvote someone, and downvote someone. Vote wisely.
                </p>
                <x-form.select
                    label="Upvote"
                    name="upvote_target"
                    wire:model="upvote_target_id"
                    :options="$this->upvote_options->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
                    selected="Choose a Player"
                />
                <div class="pt-4">
                    <x-form.select
                        label="Downvote"
                        name="downvote_target"
                        wire:model="downvote_target_id"
                        :options="$this->downvote_options->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
                        selected="Choose a Player"
                    />
                </div>
            </div>

            @if (session()->has('error'))
                <div class="pt-4 text-red-600">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex justify-between items-center mt-4">
                <x-primary-button wire:click="vote" wire:loading.attr="disabled">
                    Vote
                </x-primary-button>
            </div>
        @else
            <div >
                <p>You may vote once per hour. Vote again {{ $this->player->state()->lastVotedAt()->addHours(1)->diffForHumans() }}.</p>
            </div>
        @endif
    </x-card>
</div>
