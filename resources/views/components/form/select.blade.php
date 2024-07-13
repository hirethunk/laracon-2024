<x-form.label customStyle="w-8/12 font-bold" name="{{ $label }}" label="{{ $name ?? $label }}" />
<select
    name="{{ $name }}"
    id="{{ $name }}"
    class="px-2 w-full bg-gray-900 border-2 rounded-lg {{ $custom ?? '' }}"
    {{ $attributes->whereStartsWith('wire:model') }}
>
    <option value="" selected>{{ $selected }}</option>

    @foreach ($options as $key => $option)
        <option value="{{ $key }}">{{ $option }}</option>
    @endforeach
</select>
{{$slot}}
