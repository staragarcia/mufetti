<nav class="fixed top-0 left-0 right-0 z-50 bg-card/95 backdrop-blur-sm border-b border-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo com Admin um pouco maior --}}
            <a href="{{ route('admin.users.index') }}" class="flex items-baseline gap-2 group">
                <span class="text-3xl font-bold tracking-tight text-primary">mufetti.</span>
                <span class="text-[13px] font-bold uppercase tracking-wider text-muted-foreground/70 border-l border-border pl-2">
                    Admin
                </span>
            </a>

            {{-- Navigation Links - Espaçamento gap-6 para igualar à original --}}
            <div class="flex items-center gap-6">

                <a href="{{ route('admin.users.index') }}"
                    class="text-sm font-medium transition-colors
                    {{ request()->is('admin/users*') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                    Users
                </a>

                {{-- Novo link para Groups (US409) --}}
                <a href="{{ route('admin.groups.index') }}"
                    class="text-sm font-medium transition-colors
                    {{ request()->is('admin/groups*') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                    Groups
                </a>

                <div class="h-4 w-px bg-border mx-1"></div>

                {{-- Exit Admin no mesmo estilo dos links da original --}}
                <a href="{{ route('feed.show') }}" 
                   class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/>
                    </svg>
                    Exit Admin
                </a>

                <form action="{{ route('logout') }}" method="POST" class="flex items-center">
                    @csrf
                    <button class="px-3 py-1 border border-border rounded-md text-sm text-muted-foreground hover:text-primary transition">
                        Logout
                    </button>
                </form>

            </div>
        </div>
    </div>
</nav>