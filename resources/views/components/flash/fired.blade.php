@props([
    'key' => 'event',
])

@if (session()->has($key))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-cloak
        x-transition
        x-init="setTimeout(() => show = false, 2000)"
        class="flex items-center gap-1.5"
    >
        <x-icons.fire class="text-red-500 h-4 w-4" />
        <span class="pt-px text-sm text-green-400">{{ __(session($key)) }}</span>
    </div>
@endif
