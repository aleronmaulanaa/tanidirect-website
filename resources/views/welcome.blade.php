<!DOCTYPE html>
<html 
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="scroll-smooth">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TaniDirect</title>


    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">


    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-[#F8FAF5] font-sans antialiased">

    {{-- Navbar --}}
    @include('components.landing.navbar')

    {{-- Hero --}}
    @include('components.landing.hero')

    {{-- Features --}}
    @include('components.landing.features')

    {{-- How It Works --}}
    @include('components.landing.how-it-works')

    {{-- Products --}}
    @include('components.landing.products')

    {{-- Group Buy --}}
    @include('components.landing.group-buy')


    @include('components.landing.producer')

    {{-- Call To Action --}}
    @include('components.landing.cta')
 
    {{-- Footer --}}
    @include('components.landing.footer')

    @include('components.chatbot.widget')

</body>
</html>