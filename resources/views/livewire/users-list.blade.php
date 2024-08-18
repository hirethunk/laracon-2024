<div class="p-4 max-w-80 mx-auto bg-white">
    <lwa::autocomplete>
        <lwa::autocomplete-input class="p-4 text-red-500" />

        <lwa::autocomplete-list class="text-blue-400">
            @foreach ($this->users as $user)
                <lwa::autocomplete-item>
                    {{ $user->name }}
                </lwa::autocomplete-item>
            @endforeach
        </lwa::autocomplete-list>
    </lwa::autocomplete>
</div>
