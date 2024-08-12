@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-neutral-300']) }}>
    {{ $value ?? $slot }}
</label>
