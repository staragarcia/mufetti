<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        {{-- Tailwind + Vite --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Alpine.js --}}
        <script src="//unpkg.com/alpinejs" defer></script>

        {{-- Script js --}}
        <script src="{{ asset('js/group.js') }}"></script>

        @stack('styles')
    </head>

    <body class="bg-background text-foreground" style="overflow-y: scroll;">

        {{-- NAVBAR --}}
        @include('partials.navbar')

        <main class="pt-20 pb-12">
            <section id="content">
                @yield('content')
            </section>
        </main>

        {{-- FOOTER --}}
        @include('partials.footer')

        @stack('scripts')
    </body>
</html>
