<div class="relative w-full h-screen p-4 flex flex-col  items-center">
    <div class="flex space-x-4">
        <div class="w-48">
            <label class="font-bold" for="user-name">Search for User</label>
            <div>
                <x-autocomplete wire:model.live="userId">
                    <x-autocomplete.label>User:</x-autocomplete.label>

                    <x-autocomplete.input wire:model.live="name" containerClass="flex items-center justify-between" class="px-7 w-24 text-black">
                        <svg class="absolute left-0 ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>

                        @if ($userId)
                            <button type="button" x-on:click="clear()" class="absolute right-0 mr-1">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        @else
                            <button type="button" x-on:click="toggle" class="absolute right-0 mr-1" tabindex="-1">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif
                    </x-autocomplete.input>

                    <x-autocomplete.list class="mx-2 mt-1 max-h-64" x-cloak>
                        @foreach ($this->users as $listUser)
                            <x-autocomplete.item
                                :key="$listUser->id"
                                :value="$listUser->name"
                                :isDisabled="$listUser->id == 1 || $listUser->id == 5"
                                {{-- active="bg-blue-500" --}}
                                {{-- inactive="bg-white" --}}>
                                <span>{{ $listUser->name }}</span>
                            </x-autocomplete.item>
                        @endforeach
                    </x-autocomplete.list>
                </x-autocomplete>
            </div>
        </div>

        <div class="w-48">
            <h1 class="font-bold">Selected User</h1>
            <p>ID: {{ $this->user->id ?? 'none' }}</p>
            <p>Name: {{ $this->user->name ?? 'none' }}</p>
        </div>
    </div>
</div>
