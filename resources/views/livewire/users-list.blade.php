<div class="p-4 max-w-80 mx-auto bg-white text-black">
    <p>Search: {{ $search }}</p>

    <p>User ID: {{ $userId }}</p>

    <x-lwa::autocomplete wire:model.live="userId">
        <x-lwa::autocomplete.input wire:model.live="search" class="p-4 text-red-500" />

        <x-lwa::autocomplete.list class="text-blue-400 bg-white max-h-56">
            @foreach ($this->users as $user)
                <x-lwa::autocomplete.item
                    :key="$user->id"
                    :value="$user->name"
                >
                    {{ $user->name }}
                </x-lwa::autocomplete.item>
            @endforeach
        </x-lwa::autocomplete.list>
    </x-lwa::autocomplete>
</div>
