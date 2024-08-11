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

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=font-serif:wght@400..900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=font-sans:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased font-normal bg-black opacity-95">
        <div class="min-h-screen flex flex-col gap-y-10 px-4 sm:justify-center items-center py-10">
            <div>
                <a href="/">
                    <x-application-logo class="text-6xl" />
                </a>
            </div>

            <main class="max-w-lg w-full">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
