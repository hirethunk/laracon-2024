<div>
    <div class="py-12 bg-black">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-white border-amber-400 border-8 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($player_can_vote)
                        <div class="flex flex-col">
                            Once per hour, you can upvote someone, and downvote someone. Vote wisely.
                            <div class="mt-8">
                                <x-form.select
                                    label="Upvote"
                                    name="upvote_target"
                                    custom="w-6/12"
                                    wire:model="upvote_target_id"
                                    :options="$this->players->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
                                    selected="Choose a Player"
                                />
                            </div>
                            <div class="mt-4">
                                <x-form.select
                                    label="Downvote"
                                    name="downvote_target"
                                    custom="w-6/12"
                                    wire:model="downvote_target_id"
                                    :options="$this->players->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
                                    selected="Choose a Player"
                                />
                            </div>
                        </div>
                        <x-primary-button wire:click="vote" wire:loading.attr="disabled" class="mt-8">
                            Vote
                        </x-primary-button>
                    @else
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-amber-400">You can only vote once per hour</h1>
                            <p>Vote again in Carbon {{ $this->player->last_voted_at->diffForHumans() }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
