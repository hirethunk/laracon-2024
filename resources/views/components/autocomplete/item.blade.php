<x-lwa::autocomplete.item
    :attributes="$attributes->class('')"
    active="bg-gold-500 text-black font-medium"
    inactive="bg-black"
    disabled=""
    unstyled
>
    {{ $slot }}
</x-lwa::autocomplete.item>
