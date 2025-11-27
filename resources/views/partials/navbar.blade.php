<nav class="fixed top-0 left-0 right-0 z-50 bg-card/95 backdrop-blur-sm border-b border-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-3xl font-bold tracking-tight text-primary">mufetti.</span>
            </a>

            {{-- Navigation Links --}}
            <div class="flex items-center gap-6">

                @auth

                    <a href="/feed"
                    class="text-sm font-medium transition-colors
                        {{ request()->is('feed') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                        Feed
                    </a>

                    <a href="/search"
                    class="text-sm font-medium transition-colors
                        {{ request()->is('search') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                        Search
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


                    <a href="{{ route('profile.show') }}"
                       class="text-sm font-medium transition-colors
                            {{ request()->is('profile') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                        Profile
                    </a>

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
