@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'border-0 border-b border-b-neutral-300 bg-black focus:border-b-0 focus:outline-none outline-none focus:ring-2 focus:ring-gold-500-light active:ring-2 active:ring-gold-500-light text-neutral-300 focus:rounded-md shadow-sm']) !!}>
