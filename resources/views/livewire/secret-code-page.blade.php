<div>
    <x-card>
        <h2 class="text-2xl text-gold-100">Secret code</h2>
        <p class="text-sm text-white font-normal mt-4">
            Shh, don't tell anyone about this secret page. If you enter an invalid code, you will receive a downvote.
        </p>
        <div class="flex flex-row mt-4 space-x-4 justify between">
            <input 
                wire:model="code" 
                type="text"
                class="bg-black text-white w-full h-full p-2 rounded border border-gray-700 focus:outline-none focus:border-gold-100"
                placeholder="Enter your secret code" 
            />
            <x-primary-button 
                wire:click="submitCode"
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
    </x-card>
</div>
