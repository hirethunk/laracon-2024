<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="cardo-bold text-gray-900 antialiased font-normal">
        <div class="min-h-screen flex flex-col gap-y-10 px-4 sm:justify-center items-center py-10 bg-black">
            <div>
                <a href="/">
                    <x-application-logo class="text-6xl" />
                </a>
            </div>

            <main class="max-w-lg w-full">
                <x-form.card>
                    {{ $slot }}
                </x-form.card>
            </main>
        </div>
    </body>
</html>
