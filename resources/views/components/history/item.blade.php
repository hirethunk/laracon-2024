@props([
    'text',
    'time',
    'isLast' => false,
])

<li class="relative flex gap-x-4 justify-between">
    <p class="flex-auto py-0.5 text-xs leading-5 text-neutral-500"><span class="font-medium text-neutral-900">
        {{ $text }}
    </p>
    <time class="flex-none py-0.5 text-xs leading-5 text-neutral-500">
        {{$time}}
    </time>
</li>
