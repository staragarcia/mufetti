@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-5xl">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-foreground mb-3"><span class="text-primary">mufetti</span> About Us</h1>
        <p class="text-muted-foreground">Your music social network</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <!-- Who We Are -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">Who We Are</h2>
            </div>
            <p class="text-muted-foreground leading-relaxed">
                Mufetti is a social platform for music lovers. Connect with fellow music enthusiasts, 
                share your favorite albums, discover new artists, and join music communities.
            </p>
        </div>

        <!-- The Project -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h2 class="text-xl font-semibold text-foreground">The Project</h2>
            </div>
            <p class="text-muted-foreground leading-relaxed mb-3">
                This platform was developed as part of the <strong>LBAW</strong> (Laboratório de Bases de Dados e Aplicações Web) 
                class in the Informatics Engineering course at <strong>FEUP</strong>.
            </p>
            <p class="text-muted-foreground leading-relaxed">
                Built using modern web technologies, demonstrating practical application of database design and web development principles.
            </p>
        </div>

    </div>

    <!-- Technologies -->
    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 mb-8">
        <div class="flex items-center mb-4">
            <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
            </svg>
            <h2 class="text-xl font-semibold text-foreground">Technologies Used</h2>
        </div>
        <div class="flex flex-wrap gap-2 ml-11">
            <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-medium">Laravel</span>
            <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-medium">PHP</span>
            <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-medium">PostgreSQL</span>
            <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-medium">Tailwind CSS</span>
            <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-medium">JavaScript</span>
            <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-medium">Docker</span>
        </div>
    </div>

    <!-- Team Section -->
    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
        <div class="flex items-center mb-4">
            <svg class="w-8 h-8 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h2 class="text-xl font-semibold text-foreground">Our Team</h2>
        </div>
        <p class="text-muted-foreground mb-4 ml-11">
            <strong>Group 2585:</strong>
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ml-11">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="font-medium text-foreground">Casemiro Melo Jorge de Medeiros</p>
                <p class="text-sm text-muted-foreground mt-1">up202301897@up.pt</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="font-medium text-foreground">Gonçalo Jorge Bebiano Bexiga</p>
                <p class="text-sm text-muted-foreground mt-1">up202303903@up.pt</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="font-medium text-foreground">Mariana Cabral Almeida</p>
                <p class="text-sm text-muted-foreground mt-1">up202405731@up.pt</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="font-medium text-foreground">Sara Alves García</p>
                <p class="text-sm text-muted-foreground mt-1">up202306877@up.pt</p>
            </div>
        </div>
    </div>

</div>
@endsection
