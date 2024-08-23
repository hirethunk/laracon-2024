@props([
    'label' => '',
    'search' => '',
    'selected' => '',
    'search' => '',
    'options' => [],
])

<x-form.label customStyle="text-sm text-neutral-200 bg-black font-medium" name="{{ $label }}" label="{{ $label }}" />

<x-autocomplete wire:model.live="{{ $selected }}">
    <x-autocomplete.input
        wire:model.live="{{ $search }}"
        containerClass="flex items-center gap-x-2"
        class="px-4 w-full bg-black border-2 rounded-lg"
    >
        <x-autocomplete.clear-button />
    </x-autocomplete.input>

    <x-autocomplete.list class="absolute z-20 overflow-auto bg-black w-full max-h-52 shadow-lg">
        @foreach ($options as $id => $name)
            <x-autocomplete.item
                :key="(string) $id"
                :value="$name"
                class="py-2 px-4 cursor-pointer"
            >
                {{ $name }}
            </x-autocomplete.item>
        @endforeach

        @if(! $options->isNotEmpty())
            <x-autocomplete.empty
                x-show="! hasSelectedItem()"
                x-cloak
                inactive="bg-black"
                class="py-2 px-4"
            />
        @endif
    </x-autocomplete.list>
</x-autocomplete>
