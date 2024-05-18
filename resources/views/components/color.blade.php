@props([
    'label' => null
])

<div {{$attributes->merge(['class' => 'h-48 w-full p-20']) }}>
    @if (isset($label))
        <span class="text-white">{{ $label }}</span>
    @endif
</div>
