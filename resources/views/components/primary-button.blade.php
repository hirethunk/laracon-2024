@props([
    'color' => 'black',
])

@php
switch ($color) {
    case 'black':
        $bgColor = 'bg-black';
        $textColor = 'text-white';
        $hover = 'hover:bg-gray-700';
        break;
    case 'gold':
        $bgColor = 'bg-gold-500';
        $textColor = 'text-black';
        $hover = 'hover:bg-gold-100';
        break;
}
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => "$bgColor $textColor $hover inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"]) }}>
    {{ $slot }}
</button>
