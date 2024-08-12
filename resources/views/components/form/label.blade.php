@props([
    'name',
    'customStyle' => '',
    'tooltip' => 'disabled',
    'gap' => 'mb-2',
    'label' => null,
])

<h3
    class="{{ $gap }} flex"
    {{ $attributes }}
>
    <label for="{{ $label ?? $name }}" class="text-neutral-200 font-medium {{ $customStyle }}">
        {{ ucwords(str_replace( '_', ' ', $name)) }}
    </label>
</h3>
