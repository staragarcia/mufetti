{{-- Album Card Partial --}}
<a href="{{ route('albums.show', $album) }}" class="block border rounded-lg hover:shadow-lg transition overflow-hidden">
    <div class="flex gap-3 p-3">
        {{-- Album Cover (small) --}}
        <div class="flex-shrink-0">
            @if($album->cover_url)
                <img 
                    src="{{ $album->cover_url }}" 
                    alt="{{ $album->title }} cover"
                    class="w-16 h-16 object-cover rounded"
                    onerror="this.onerror=null; this.src=''; this.parentElement.innerHTML='<div class=\'w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded flex items-center justify-center\'><svg class=\'w-6 h-6 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3\'></path></svg></div>';"
                >
            @else
                <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Album Info --}}
        <div class="flex-1 min-w-0">
            <h3 class="font-semibold text-gray-900 truncate">{{ $album->title }}</h3>
            <p class="text-sm text-gray-600 truncate">
                @if(isset($album->artists) && $album->artists->count() > 0)
                    {{ $album->artists->pluck('name')->join(', ') }}
                @else
                    Unknown Artist
                @endif
            </p>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-yellow-500 text-sm">★</span>
                <span class="text-xs text-gray-600">
                    {{ number_format($album->avg_rating ?? 0, 1) }}
                </span>
                <span class="text-xs text-gray-400">·</span>
                <span class="text-xs text-gray-500">
                    {{ $album->reviews_total ?? 0 }} reviews
                </span>
            </div>
        </div>
    </div>
</a>
