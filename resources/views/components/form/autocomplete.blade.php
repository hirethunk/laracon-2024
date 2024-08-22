@props([
    'label' => '',
    'search' => '',
    'selected' => '',
    'search' => '',
    'clear' => '',
    'options' => [],
])

<x-form.label customStyle="text-sm text-neutral-200 font-medium" name="{{ $label }}" label="{{ $label }}" />

<x-autocomplete wire:model.live="{{ $selected }}" class="bg-neutral-900">
    <x-autocomplete.input
        wire:model.live="{{ $search }}"
        class="px-4 w-full bg-inherit border-2 rounded-lg"
    >
        {{-- @todo figure out placement --}}
        {{-- @if (isset($clear) && $clear !== '')
            <x-autocomplete.clear-button />
        @endif --}}
    </x-autocomplete.input>

    <x-autocomplete.list class="absolute overflow-auto bg-neutral-900 w-full pt-1 max-h-56">
        @foreach ($options as $id => $name)
            <x-autocomplete.item
                :key="(string) $id"
                :value="$name"
                class="px-4"
            >
                {{ $name }}
            </x-autocomplete.item>
        @endforeach
    </x-autocomplete.list>
</x-autocomplete>
