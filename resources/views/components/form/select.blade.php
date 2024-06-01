<x-form.label customStyle="button w-8/12" name="{{ $label }}" label="{{ $name ?? $label }}" />
<select
    name="{{ $name }}"
    id="{{ $name }}"
    class="pl-2 pr-2 min-w-md small-body bg-gray-900 text-steel-95 text-opacity-high block border-2 border-steel-40 rounded-[10px] {{$custom ?? ''}}"
    {{ $attributes->whereStartsWith('wire:model') }}
>
    <option value="" selected>{{ $selected }}</option>

    @foreach ($options as $key => $option)
        <option value="{{ $key }}">{{ $option }}</option>
    @endforeach
</select>
{{$slot}}
