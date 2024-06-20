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
    <body class="cardo-bold text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col px-4 sm:justify-center items-center pt-6 sm:pt-0 bg-black">
            <div>
                <a href="/">
                    <x-application-logo class="text-6xl" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 rounded-lg py-4 bg-gold-500 shadow-md overflow-hidden">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
