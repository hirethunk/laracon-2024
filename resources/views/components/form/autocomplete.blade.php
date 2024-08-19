@props([
    'label' => '',
    'search' => '',
    'selected' => '',
    'options' => [],
])

<x-form.label customStyle="text-sm text-neutral-200 font-medium" name="{{ $label }}" label="{{ $label }}" />

<x-autocomplete wire:model.live="{{ $selected }}">
    <x-autocomplete.input wire:model.live="search" class="px-2 w-full bg-neutral-900 border-2 rounded-lg" />

    <x-autocomplete.list class="max-h-56">
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
