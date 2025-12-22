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

    <body class="bg-background text-foreground" style="overflow-y: scroll;" data-authenticated="{{ auth()->check() ? 'true' : 'false' }}">

        {{-- NAVBAR --}}
        @include('partials.navbar')
        <main class="pt-20 pb-12">
            <section id="content">
                @yield('content')
            </section>

        </main>

        {{-- Container para o pusher notification --}}
            <div id="notification"
                class="fixed bottom-15 right-10 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transition-opacity duration-500 pointer-events-none z-50">
            </div>

        {{-- FOOTER --}}
        @include('partials.footer')

        @stack('scripts')
    </body>
</html>

{{-- isto é usado para o pusher notification, para conseguir saber o user que vai receber a notification, deve ser seguro --}}
<script>
    @auth
        window.userId = {{ auth()->id() }};
    @else
        window.userId = null;
    @endauth
</script>
