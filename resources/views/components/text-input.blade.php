@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-black focus:outline-none text-white rounded-md shadow-sm']) !!}>
