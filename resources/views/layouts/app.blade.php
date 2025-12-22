<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Mufetti'))</title>

        {{-- Open Graph --}}
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('title', 'Mufetti - Music Community')">
        <meta property="og:description" content="@yield('description', 'Share your music passion, discover albums, and connect with music lovers.')">
        <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">

        {{-- Folha de Estilos para Impressão --}}
        <style>
            @media print {
                /* Esconde elementos que não fazem sentido no papel */
                nav, 
                footer, 
                #notification, 
                .skip-link,
                button,
                .no-print {
                    display: none !important;
                }

                /* Ajusta o conteúdo para usar todo o papel */
                body {
                    background-color: white !important;
                    color: black !important;
                }
                
                main {
                    padding-top: 0 !important;
                    margin: 0 !important;
                }
            }
        </style>

        {{-- Tailwind + Vite --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Alpine.js --}}
        <script src="//unpkg.com/alpinejs" defer></script>

        {{-- Script js --}}
        <script src="{{ asset('js/group.js') }}"></script>

        @stack('styles')
    </head>

    <body class="bg-background text-foreground" style="overflow-y: scroll;" data-authenticated="{{ auth()->check() ? 'true' : 'false' }}">

        {{-- Atalho para saltar links repetitivos (Acessibilidade) --}}
        <a href="#content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary text-white p-2 z-[100] rounded-md skip-link">
            Saltar para o conteúdo
        </a>

        {{-- NAVBAR DINÂMICA --}}
        @if(request()->is('admin*'))
            @include('partials.admin-navbar')
        @else
            @include('partials.navbar')
        @endif

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

        <script>
            @auth
                window.userId = {{ auth()->id() }};
            @else
                window.userId = null;
            @endauth
        </script>
    </body>
</html>