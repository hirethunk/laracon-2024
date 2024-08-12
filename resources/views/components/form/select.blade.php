<x-form.label customStyle="text-sm text-neutral-200 font-medium" name="{{ $label }}" label="{{ $name ?? $label }}" />
<select
    name="{{ $name }}"
    id="{{ $name }}"
    class="px-2 w-full bg-neutral-900 border-2 rounded-lg {{ $custom ?? '' }}"
    {{ $attributes->whereStartsWith('wire:model') }}
>
    <option value="" selected>{{ $selected }}</option>

    @foreach ($options as $key => $option)
        <option value="{{ $key }}">{{ $option }}</option>
    @endforeach
</select>
{{$slot}}
