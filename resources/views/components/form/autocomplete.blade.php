@props([
    'label' => '',
    'search' => '',
    'selected' => '',
    'search' => '',
    'options' => [],
])

<x-form.label customStyle="text-sm text-neutral-200 font-medium" name="{{ $label }}" label="{{ $label }}" />

<x-autocomplete wire:model.live="{{ $selected }}" class="bg-neutral-900">
    <x-autocomplete.input wire:model.live="{{ $search }}" class="w-full bg-inherit border-2 rounded-lg" />

    <x-autocomplete.list class="absolute bg-neutral-900 max-h-56 px-4">
        @foreach ($options as $id => $name)
            <x-autocomplete.item
                :key="(string) $id"
                :value="$name"
            >
                {{ $name }}
            </x-autocomplete.item>
        @endforeach
    </x-autocomplete.list>
</x-autocomplete>
