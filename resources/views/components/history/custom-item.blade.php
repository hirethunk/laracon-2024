@props([
    'dto',
    'time',
    'isLast' => false,
])

<li class="relative flex gap-x-4">
    <x-dynamic-component
        :component="$dto->component?->component"
        :attributes="new \Illuminate\View\ComponentAttributeBag($dto->component?->props)"
        time="{{ $time }}"
    />
</li>
