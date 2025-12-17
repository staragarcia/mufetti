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


                    <a href="{{ route('pages.profile.show') }}"
                       class="text-sm font-medium transition-colors
                            {{ request()->is('profile') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                        Profile
                    </a>

                    {{-- Admin link for admins only --}}
                    @if(auth()->check() && auth()->user()->is_admin)
                        <a href="{{ route('admin.users.index') }}"
                           class="text-sm font-medium transition-colors
                               {{ request()->is('admin*') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }}">
                            Admin
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
