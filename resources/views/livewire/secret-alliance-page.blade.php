<div wire:poll>

    @php
        if ($this->ally) {
            $player_has_connected = $this->player->state()->has_connected_with_ally;
            $ally_has_connected = $this->ally->has_connected_with_ally;
            $player_choice = $this->player->state()->prisoners_dilemma_choice;
            $ally_choice = $this->ally->prisoners_dilemma_choice;
        }

        dump($player_has_connected, $ally_has_connected, $player_choice, $ally_choice);
    @endphp

    <x-card>
        <h2 class="text-2xl text-gold-100">Secret Alliance</h2>

        @if($player_has_connected)
            @if($ally_has_connected)
                @if($player_choice)
                    @if($ally_choice)
                        <p class="text-sm text-white font-normal mt-4">
                            You've made your choices. You chose {{ $player_choice }} and your ally chose {{ $ally_choice }}.
                        </p>
                    @else
                        <p class="text-sm text-white font-normal mt-4">
                            You've made your choice. Now, you just need to wait for your ally to make theirs.
                        </p>
                    @endif
                @else
                    <p class="text-sm text-white font-normal mt-4">
                        Let's make things interesting. Below you have options to play nice, or play nasty. If you and
                        <span class="text-gold-500 font-bold">{{ $this->ally->name }}</span>
                        both choose to play nice, you will both receive 2 upvotes. 
                        If they play nice and you play nasty, you will receive 5 upvotes, and they will get nothing. 
                        If you both choose to play nasty, you will both receive 2 downvotes.
                    </p>
                    <div class="flex flex-row mt-4 space-x-4 justify between">
                        <x-primary-button 
                            wire:click="playNice"
                            wire:loading.attr="disabled"
                            color="gold"
                        >
                            Play nice
                        </x-primary-button>
                        <x-primary-button 
                            wire:click="playNasty"
                            wire:loading.attr="disabled"
                            color="red"
                        >
                            Play nasty
                        </x-primary-button>
                    </div>
                @endif
            @else
                <p class="text-sm text-white font-normal mt-4">
                    You've already connected with your ally. Now, you just need to wait for them to connect with you.
                    Find them and give them this code: <span class="text-gold-500 font-bold">{{ $this->player->state()->code_to_give_to_ally }}</span>.
                    If they enter it at this URL, you will receive an upvote. They will also find a code for you at this page. 
                </p>
            @endif
        @else
            <p class="text-sm text-white font-normal mt-4">
                How about a little help from a friend? You've been randomly assigned a secret alliance. Your alliance is 
                <span class="text-gold-500 font-bold">{{ $this->ally->name }}</span>. 
                Find them and give them this code: <span class="text-gold-500 font-bold">{{ $this->player->state()->code_to_give_to_ally }}</span>.
                If they enter it at this URL, you will receive an upvote. They will also find a code for you at this page. 
            </p>
            <div class="flex flex-row mt-4 space-x-4 justify between">
                <input 
                    wire:model="code" 
                    type="text"
                    class="bg-black text-white w-full h-full p-2 rounded border border-gray-700 focus:outline-none focus:border-gold-100"
                    placeholder="Enter the code from your ally" 
                />
                <x-primary-button 
                    wire:click="connectWithAlly"
                    wire:loading.attr="disabled"
                    color="gold"
                >
                    Submit
                </x-primary-button>
            </div>
            @if (session()->has('error'))
                <div class="pt-4 text-xs text-red-600">
                    {{ session('error') }}
                </div>
            @endif
        @endif
{{-- 
        @if (! $this->ally)
            <p class="text-sm text-white font-normal mt-4">
                There are no allies available for you at this time.
                Recruit someone else to join the game and try again.
            </p>
        @elseif (! $player_has_connected)
            <p class="text-sm text-white font-normal mt-4">
                How about a little help from a friend? You've been randomly assigned a secret alliance. Your alliance is 
                <span class="text-gold-500 font-bold">{{ $this->ally->name }}</span>. 
                Find them and give them this code: <span class="text-gold-500 font-bold">{{ $this->player->state()->code_to_give_to_ally }}</span>.
                If they enter it at this URL, you will receive an upvote. They will also find a code for you at this page. 
            </p>
            <div class="flex flex-row mt-4 space-x-4 justify between">
                <input 
                    wire:model="code" 
                    type="text"
                    class="bg-black text-white w-full h-full p-2 rounded border border-gray-700 focus:outline-none focus:border-gold-100"
                    placeholder="Enter the code from your ally" 
                />
                <x-primary-button 
                    wire:click="connectWithAlly"
                    wire:loading.attr="disabled"
                    color="gold"
                >
                    Submit
                </x-primary-button>
            </div>
            @if (session()->has('error'))
                <div class="pt-4 text-xs text-red-600">
                    {{ session('error') }}
                </div>
            @endif
        @elseif (
            $player_has_connected
            && ! $ally_has_connected
        )
            <p class="text-sm text-white font-normal mt-4">
                You've already connected with your ally. Now, you just need to wait for them to connect with you.
                Find them and give them this code: <span class="text-gold-500 font-bold">{{ $this->player->state()->code_to_give_to_ally }}</span>.
                If they enter it at this URL, you will receive an upvote. They will also find a code for you at this page. 
            </p>
        @elseif (
            $player_has_connected
            && $ally_has_connected
            && ! $player_choice
        )
            
        @elseif ($player_choice && ! $ally_choice)
            <p class="text-sm text-white font-normal mt-4">
                You've made your choice. Now, you just need to wait for your ally to make theirs.
            </p>
        @elseif ($player_choice && $ally_choice)
            <p class="text-sm text-white font-normal mt-4">
                Very interesting. 
                @if ($player_choice === 'nice' && $ally_choice === 'nice')
                    Such nice devs you are. Trust goes a long way in this game. You both receive 2 upvotes.
                @elseif ($player_choice === 'nice' && $ally_choice === 'nasty')
                    You've been bamboozled. Your ally is not so nice. You received nothing. 
                @elseif ($player_choice === 'nasty' && $ally_choice === 'nice')
                    You dirty dog, you. You receive 5 upvotes. Your ally receives nothing.
                @elseif ($player_choice === 'nasty' && $ally_choice === 'nasty')    
                    Dirty rotten scoundrels... You both receive 2 downvotes.
                @endif
            </p>
        @endif --}}
    </x-card>
</div>
