@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-3xl">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-foreground mb-3">Contact <span class="text-primary">Us</span></h1>
        <p class="text-muted-foreground">We'd love to hear from you</p>
    </div>

    <div class="bg-white rounded-lg p-8 shadow-sm border border-gray-200 space-y-6">
        
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-primary mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-foreground">Email</h3>
                    <p class="text-muted-foreground">support@mufetti.com</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-primary mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-foreground">Location</h3>
                    <p class="text-muted-foreground">Porto, Portugal</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-primary mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-foreground">Support Hours</h3>
                    <p class="text-muted-foreground">Monday - Friday, 9AM - 6PM GMT</p>
                </div>
            </div>
        </div>

        <div class="pt-4 text-center text-sm text-muted-foreground">
            We typically respond within 24-48 hours
        </div>

    </div>

</div>
@endsection
