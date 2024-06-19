@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-y-2 border-gray-50 text-sm leading-5 text-gold-500 focus:outline-none focus:border-gray-50 transition duration-150 ease-in-out lowercase'
            : 'inline-flex items-center px-1 pt-1 border-y-2 border-transparent text-sm leading-5 text-gold-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out lowercase';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
