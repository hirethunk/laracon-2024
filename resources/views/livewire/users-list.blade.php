<div class="p-4 max-w-80 mx-auto bg-white">
    <x-autocomplete>
        <x-autocomplete-input class="p-4 text-red-500" />

        <x-autocomplete-list class="text-blue-400 max-h-56">
            @foreach ($this->users as $user)
                <x-autocomplete-item>
                    {{ $user->name }}
                </x-autocomplete-item>
            @endforeach
        </x-autocomplete-list>
    </x-autocomplete>
</div>
