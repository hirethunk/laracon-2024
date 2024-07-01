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
    <label for="{{ $label ?? $name }}" class="{{ $customStyle }}">
        {{ ucwords(str_replace( '_', ' ', $name)) }}
    </label>
</h3>
