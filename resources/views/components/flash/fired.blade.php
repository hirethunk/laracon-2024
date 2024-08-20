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
        class="flex items-center justify-center gap-2 p-4"
    >
        <x-icons.fire class="text-red-500 h-6 w-6" />
        <span class="text-sm text-green-400">{{ __(session($key)) }}</span>
    </div>
@endif
