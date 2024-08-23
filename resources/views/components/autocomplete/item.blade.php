<x-lwa::autocomplete.item
    :attributes="$attributes->class('')"
    active="bg-blue-600 text-white"
    inactive="bg-black"
    disabled=""
    unstyled
>
    {{ $slot }}
</x-lwa::autocomplete.item>
