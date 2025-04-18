@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full pl-3 pr-4 py-2 text-gold-500-light text-start text-base font-medium hover:text-neutral-800 hover:bg-gold-100 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'block w-full pl-3 pr-4 py-2 text-start text-base font-medium text-neutral-400 hover:text-neutral-600 hover:bg-gold-100 focus:outline-none focus:text-neutral-800 focus:bg-neutral-50 focus:border-neutral-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
