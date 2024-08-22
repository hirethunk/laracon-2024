<x-lwa::autocomplete.item
    :attributes="$attributes->class('')"
    active="bg-blue-500 text-white"
    inactive=""
    disabled=""
    unstyled
>
    {{ $slot }}
</x-lwa::autocomplete.item>
