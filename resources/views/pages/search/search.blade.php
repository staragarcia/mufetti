@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background px-4 py-10">
    <div class="max-w-4xl mx-auto space-y-6">

        <div class="space-y-2">
            <h1 class="text-3xl font-bold text-foreground">Search</h1>
            <p class="text-muted-foreground">Find posts, comments, groups, or users.</p>
        </div>

        <!-- Search Form -->
        <div class="bg-card border border-border p-6 rounded-lg space-y-4">

            <!-- Search Input -->
            <div>
                <label class="text-sm font-medium text-foreground">Search query</label>
                <input
                    type="text"
                    id="searchInput"
                    class="w-full mt-1 px-3 py-2 rounded-md border border-border bg-background focus:ring focus:ring-primary/50"
                    placeholder="Type to search..."
                    autocomplete="off">
            </div>

            <!-- Type Selector -->
            <div>
                <label class="text-sm font-medium text-foreground">Search for</label>
                <select
                    id="searchType"
                    class="w-full mt-1 px-3 py-2 rounded-md border border-border bg-background">
                    <option value="posts">Posts</option>
                    <option value="comments">Comments</option>
                    <option value="groups">Groups</option>
                    <option value="users">Users</option>
                </select>
            </div>

            <!-- Search Button (opcional, já que é real-time) -->
            <button
                id="searchButton"
                class="w-full py-2 bg-primary text-primary-foreground font-semibold rounded-md hover:bg-primary/90 transition">
                Search
            </button>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden">
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                <span class="ml-3 text-muted-foreground">Searching...</span>
            </div>
        </div>

        <!-- Results Container -->
        <div id="resultsContainer" class="hidden">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-foreground">
                    Results for "<span id="searchQuery"></span>"
                </h2>
                <span id="resultCount" class="text-sm text-muted-foreground"></span>
            </div>

            <div id="resultsList" class="space-y-4">
                <!-- Results will be inserted here -->
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="hidden text-center py-8">
                <p class="text-muted-foreground">No results found.</p>
            </div>
        </div>

        <!-- Initial State Message -->
        <div id="initialState" class="text-center py-12 text-muted-foreground">
            <svg class="mx-auto h-12 w-12 text-muted-foreground/50 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <p>Start typing to search...</p>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchType = document.getElementById('searchType');
    const searchButton = document.getElementById('searchButton');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const resultsContainer = document.getElementById('resultsContainer');
    const resultsList = document.getElementById('resultsList');
    const noResults = document.getElementById('noResults');
    const initialState = document.getElementById('initialState');
    const searchQuery = document.getElementById('searchQuery');
    const resultCount = document.getElementById('resultCount');

    let searchTimeout;
    let currentRequest = null;

    // Debounced search function
    function performSearch() {
        const query = searchInput.value.trim();
        const type = searchType.value;

        if (query.length === 0) {
            showInitialState();
            return;
        }

        if (query.length < 2) {
            return; // Wait for at least 2 characters
        }

        // Cancel previous request
        if (currentRequest) {
            currentRequest.abort();
        }

        // Show loading
        showLoading();

        // Create new request
        currentRequest = new XMLHttpRequest();
        currentRequest.open('GET', `{{ route('search.results') }}?query=${encodeURIComponent(query)}&type=${type}`, true);
        currentRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        currentRequest.onload = function() {
            if (currentRequest.status === 200) {
                const data = JSON.parse(currentRequest.responseText);
                displayResults(data, query, type);
            }
        };

        currentRequest.onerror = function() {
            showError('An error occurred while searching.');
        };

        currentRequest.send();
    }

    // Event listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300); // 300ms debounce
    });

    searchType.addEventListener('change', function() {
        if (searchInput.value.trim().length >= 2) {
            performSearch();
        }
    });

    searchButton.addEventListener('click', performSearch);

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Display functions
    function showLoading() {
        initialState.classList.add('hidden');
        resultsContainer.classList.add('hidden');
        loadingIndicator.classList.remove('hidden');
    }

    function showInitialState() {
        loadingIndicator.classList.add('hidden');
        resultsContainer.classList.add('hidden');
        initialState.classList.remove('hidden');
    }

    function showError(message) {
        loadingIndicator.classList.add('hidden');
        resultsContainer.classList.remove('hidden');
        noResults.classList.remove('hidden');
        resultsList.classList.add('hidden');
        noResults.innerHTML = `<p class="text-destructive">${message}</p>`;
    }

    function displayResults(data, query, type) {
        loadingIndicator.classList.add('hidden');
        resultsContainer.classList.remove('hidden');
        initialState.classList.add('hidden');

        searchQuery.textContent = query;

        if (data.results.length === 0) {
            resultsList.classList.add('hidden');
            noResults.classList.remove('hidden');
            resultCount.textContent = '0 results';
            return;
        }

        noResults.classList.add('hidden');
        resultsList.classList.remove('hidden');
        resultCount.textContent = `${data.results.length} result${data.results.length !== 1 ? 's' : ''}`;

        resultsList.innerHTML = '';

        data.results.forEach(result => {
            let cardHtml = '';

            switch(type) {
                case 'posts':
                    cardHtml = createPostCard(result);
                    break;
                case 'comments':
                    cardHtml = createCommentCard(result);
                    break;
                case 'groups':
                    cardHtml = createGroupCard(result);
                    break;
                case 'users':
                    cardHtml = createUserCard(result);
                    break;
            }

            resultsList.innerHTML += cardHtml;
        });
    }

    // Card creation functions
    function createPostCard(post) {
        const truncatedDesc = post.description.length > 150
            ? post.description.substring(0, 150) + '...'
            : post.description;

        return `
            <div class="bg-card border border-border rounded-lg p-4 hover:shadow-md transition">
                <a href="/posts/${post.id}" class="block space-y-2">
                    ${post.img ? `
                        <img src="${post.img}" alt="${post.title}" class="w-full h-48 object-cover rounded-md">
                    ` : ''}
                    <h3 class="font-semibold text-lg text-foreground hover:text-primary">
                        ${post.title || 'Untitled Post'}
                    </h3>
                    <p class="text-sm text-muted-foreground">${truncatedDesc}</p>
                    <div class="flex items-center gap-4 text-xs text-muted-foreground pt-2">
                        <span>By ${post.owner_username || 'Unknown'}</span>
                        <span>•</span>
                        <span>${post.likes || 0} likes</span>
                        <span>•</span>
                        <span>${post.comments || 0} comments</span>
                    </div>
                </a>
            </div>
        `;
    }

    function createCommentCard(comment) {
        const truncatedDesc = comment.description.length > 200
            ? comment.description.substring(0, 200) + '...'
            : comment.description;

        return `
            <div class="bg-card border border-border rounded-lg p-4 hover:shadow-md transition">
                <a href="/posts/${comment.reply_to}#comment-${comment.id}" class="block space-y-2">
                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                        <span class="font-medium text-foreground">${comment.owner_username || 'Unknown'}</span>
                        <span>commented</span>
                    </div>
                    <p class="text-sm text-foreground">${truncatedDesc}</p>
                    <div class="flex items-center gap-4 text-xs text-muted-foreground pt-2">
                        <span>${comment.likes || 0} likes</span>
                        <span>•</span>
                        <span>${formatDate(comment.created_at)}</span>
                    </div>
                </a>
            </div>
        `;
    }

    function createGroupCard(group) {
        return `
            <div class="bg-card border border-border rounded-lg p-4 hover:shadow-md transition">
                <a href="/groups/${group.id}" class="block space-y-2">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg text-foreground hover:text-primary">
                            ${group.name}
                        </h3>
                        <span class="text-xs px-2 py-1 rounded ${group.is_public ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                            ${group.is_public ? 'Public' : 'Private'}
                        </span>
                    </div>
                    ${group.description ? `
                        <p class="text-sm text-muted-foreground">${group.description}</p>
                    ` : ''}
                    <div class="flex items-center gap-4 text-xs text-muted-foreground pt-2">
                        <span>${group.member_count || 0} members</span>
                        <span>•</span>
                        <span>Owner: ${group.owner_username || 'Unknown'}</span>
                    </div>
                </a>
            </div>
        `;
    }

    function createUserCard(user) {
        return `
            <div class="bg-card border border-border rounded-lg p-4 hover:shadow-md transition">
                <a href="/profile/${user.id}" class="flex items-center gap-4">
                    <img
                        src="${user.profile_picture || '/images/default-avatar.png'}"
                        alt="${user.name}"
                        class="w-16 h-16 rounded-full object-cover border-2 border-border">
                    <div class="flex-1">
                        <h3 class="font-semibold text-foreground hover:text-primary">
                            ${user.name}
                        </h3>
                        <p class="text-sm text-muted-foreground">@${user.username}</p>
                        ${user.description ? `
                            <p class="text-sm text-muted-foreground mt-1">${user.description}</p>
                        ` : ''}
                    </div>
                    ${user.is_public === false ? `
                        <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-800">
                            Private
                        </span>
                    ` : ''}
                </a>
            </div>
        `;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000); // seconds

        if (diff < 60) return 'Just now';
        if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
        if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
        if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`;

        return date.toLocaleDateString();
    }
});
</script>
@endsection
