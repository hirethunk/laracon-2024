<div>
    <x-card>
        @if ($this->can_vote)
            <div class="flex flex-col">
                <p class="pb-4 text-sm text-neutral-300">
                    Once per hour, you can upvote someone, and downvote someone. Vote wisely.
                </p>

                 <x-form.autocomplete
                    dusk="upvote_target"
                    label="upvote_target"
                    search="upvote_search"
                    selected="upvote_target_id"
                    :options="$this->upvote_options->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
                />

                <div class="pt-4">
                    <x-form.autocomplete
                        dusk="downvote_target"
                        label="downvote_target"
                        search="downvote_search"
                        selected="downvote_target_id"
                        :options="$this->downvote_options->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
                    />
                </div>
            </div>

            @if (session()->has('error'))
                <div class="pt-4 text-red-600">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex justify-between items-center mt-4" x-data>
                <x-primary-button wire:click="vote" wire:loading.attr="disabled" x-on:click="$el.setAttribute('disabled', true)">
                    Vote
                </x-primary-button>
            </div>
        @else
            <div>
                <p class="text-sm text-neutral-300">You may vote once per hour. Vote again {{ $this->player->state()->lastVotedAt()->addHours(1)->diffForHumans() }}.</p>
            </div>
        @endif
    </x-card>
</div>
