<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TaniDirect') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased min-h-screen bg-[#F6F8F3]">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-8 sm:py-12">
            <div>
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="TaniDirect" class="h-12 w-auto">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-7 py-8 bg-white shadow-xl overflow-hidden rounded-3xl border border-gray-100/80">
                {{ $slot }}
            </div>
        </div>

        @include('components.chatbot.widget')
    </body>
</html>
