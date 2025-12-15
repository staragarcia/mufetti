@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-3xl">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-foreground mb-3">About <span class="text-primary">mufetti</span></h1>
        <p class="text-muted-foreground">Your music social network</p>
    </div>

    <div class="bg-white rounded-lg p-8 shadow-sm border border-gray-200 space-y-6">
        
        <div>
            <h2 class="text-xl font-semibold text-foreground mb-2">Who We Are</h2>
            <p class="text-muted-foreground">
                Mufetti is a social platform for music lovers. Connect with fellow music enthusiasts, 
                share your favorite albums, discover new artists, and join music communities.
            </p>
        </div>

        <div>
            <h2 class="text-xl font-semibold text-foreground mb-2">What We Offer</h2>
            <ul class="text-muted-foreground space-y-1 ml-4">
                <li>• Meter aqui</li>
                <li>• As features</li>
                <li>• No final</li>
                <li>• </li>
                <li>• </li>
                <li>• </li>
            </ul>
        </div>

    </div>

</div>
@endsection
