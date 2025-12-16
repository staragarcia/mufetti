<footer class="fixed bottom-0 left-0 right-0 bg-card border-t border-border z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-10">
            
            {{-- Brand --}}
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-primary">mufetti.</span>
                <span class="text-xs text-muted-foreground">© {{ date('Y') }}</span>
            </div>

            {{-- Links --}}
            <div class="flex items-center gap-4">
                <a href="{{ route('about') }}"
                   class="text-xs font-medium transition-colors
                       {{ request()->is('about') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                    About
                </a>
                <a href="{{ route('contact') }}"
                   class="text-xs font-medium transition-colors
                       {{ request()->is('contact') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                    Contact
                </a>
                <a href="{{ route('features') }}"
                   class="text-xs font-medium transition-colors
                       {{ request()->is('features') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                    Features
                </a>
            </div>
        </div>
    </div>
</footer>
