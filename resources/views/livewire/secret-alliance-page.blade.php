<div>
    <x-card>
        <h2 class="text-2xl text-gold-100">Secret Alliance</h2>
        @if (! $this->player->state()->has_connected_with_ally)
            <p class="text-sm text-white font-normal mt-4">
                How about a little help from a friend? You've been randomly assigned a secret alliance. Your alliance is 
                <span class="text-gold-100 font-bold">{{ $this->ally->name }}</span>. 
                Find them and give them this code: <span class="text-gold-100 font-bold">{{ $this->ally->code_to_give_to_ally }}</span>.
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
            @if ($this->message)
                <div class="mt-4">
                    <p class="text-gray-400 text-sm">{{ $this->message }}</p>
            @endif
        @else
            <p class="text-sm text-white font-normal mt-4">
                Let's make things interesting. Below you have options to play nice, or play dirty. If you and
                <span class="text-gold-100 font-bold">{{ $this->ally->name }}</span>
                both choose to play nice, you will both receive 2 upvotes. 
                If they play nice and you play dirty, you will receive 5 upvotes, and they will get nothing. 
                If you both choose to play dirty, you will both receive 2 downvotes.
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
                    wire:click="playDirty"
                    wire:loading.attr="disabled"
                    color="black"
                >
                    Play dirty
                </x-primary-button>
        @endif
    </x-card>
</div>
