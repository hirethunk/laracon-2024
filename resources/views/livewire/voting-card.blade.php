<div>
    <div class="py-12 bg-black">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-white border-amber-400 border-8 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($player_can_vote)

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
