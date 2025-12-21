<nav class="fixed top-0 left-0 right-0 z-50 bg-card/95 backdrop-blur-sm border-b border-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-3xl font-bold tracking-tight text-primary">mufetti.</span>
            </a>

            {{-- Navigation Links --}}
            <div class="flex items-center gap-6">

                {{-- any user/visitor --}}

                <a href="{{ route('feed.show') }}"
                class="text-sm font-medium transition-colors
                {{ request()->is('feed') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                    Public Feed
                </a>

                <a href="/search"
                class="text-sm font-medium transition-colors
                    {{ request()->is('search') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                    Search
                </a>

                @auth
                    <a href="{{ route('albums.index') }}"
                        class="text-sm font-medium transition-colors
                        {{ request()->is('albums') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                        Albums
                    </a>

                    <a href="/groups"
                        class="text-sm font-medium transition-colors
                        {{ request()->is('groups') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                        Groups
                    </a>
                    <a href="/posts/create"
                       class="text-sm font-medium transition-colors
                            {{ request()->is('posts/create') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                        Create
                    </a>
                    <a href="/notifications"
                    class="text-sm font-medium transition-colors relative
                            {{ request()->is('notifications') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                        
                        <!-- Sininho -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
                                    6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388
                                    6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0
                                    11-6 0v-1m6 0H9" />
                        </svg>

                        <!-- Círculo vermelho se houver notificações não lidas -->
                        @if($unreadCount > 0)
                            <span class="absolute top-0 right-0 inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                        @endif
                    </a>



                    <a href="{{ route('pages.profile.show') }}"
                       class="text-sm font-medium transition-colors
                            {{ request()->is('profile') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                        Profile
                    </a>

                    {{-- Admin link for admins only --}}
                    @if(auth()->check() && auth()->user()->is_admin)
                        <a href="{{ route('admin.users.index') }}"
                        class="text-sm font-medium transition-colors
                                {{ request()->is('admin/users*') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                            Painel Admin
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="px-3 py-1 border border-border rounded-md text-sm text-muted-foreground hover:text-primary transition">
                            Logout
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                       class="px-3 py-1 border border-border rounded-md text-sm text-muted-foreground hover:text-primary transition">
                        Log In
                    </a>
                @endguest

            </div>
        </div>
    </div>
</nav>
