@props([
    'containerClass' => '',
    'placeholder' => 'Search...'
])

<x-lwa::autocomplete.input :attributes="$attributes->merge(['placeholder' => $placeholder])" :containerClass="$containerClass" unstyled>
    @if (isset($prefix))
        <x-slot:prefix>
            {{ $prefix }}
        </x-slot>
    @endif

    {{ $slot }}

    @if (isset($suffix))
        <x-slot:suffix>
            {{ $suffix }}
        </x-slot>
    @endif
</x-lwa::autocomplete.input>
