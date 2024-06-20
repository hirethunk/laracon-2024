@props([
    'text',
    'time',
    'isLast' => false,
])

<li class="relative flex gap-x-4 justify-between">
    <p class="flex-auto py-0.5 text-xs leading-5 text-gray-500"><span class="font-medium text-gray-900">
        {{ $text }}
    </p>
    <time class="flex-none py-0.5 text-xs leading-5 text-gray-500">
        {{$time}}
    </time>
</li>
