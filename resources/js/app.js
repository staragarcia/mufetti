// Reaction functionality
function initializeReactions() {
    // reaction button
    document.querySelectorAll('.reaction-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const postId = this.dataset.postId;
            const reactionType = this.dataset.type;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(`/posts/${postId}/react`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        type: reactionType
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Find post card using multiple possible selectors
                    const postCard = this.closest('.bg-white') ||
                                   this.closest('.border-gray-200')?.closest('.border-gray-200') ||
                                   this.closest('[data-post-id]')?.closest('div');

                    if (postCard) {
                        const likesCountElement = postCard.querySelector('.likes-count');
                        const confettiCountElement = postCard.querySelector('.confetti-count');

                        if (likesCountElement) {
                            likesCountElement.textContent = data.likes_count;
                        }
                        if (confettiCountElement) {
                            confettiCountElement.textContent = data.confetti_count;
                        }

                        const allReactionBtns = postCard.querySelectorAll('.reaction-btn');
                        allReactionBtns.forEach(btn => {
                            btn.classList.remove('text-blue-600', 'text-yellow-600', 'scale-110');
                        });

                        if (data.user_reaction === reactionType) {
                            this.classList.add(
                                reactionType === 'like' ? 'text-blue-600' : 'text-yellow-600',
                                'scale-110'
                            );
                        }
                    }

                    // animation when reactions are clicked/selected
                    this.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);

                } else {
                    console.error('Error:', data.error);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    // Load initial reaction states on page load
    document.querySelectorAll('[data-post-id]').forEach(async (element) => {
        const postId = element.dataset.postId;
        const postCard = element.closest('.bg-white') ||
                        element.closest('.border-gray-200')?.closest('.border-gray-200') ||
                        element.closest('div');

        if (!postCard) return;

        try {
            const response = await fetch(`/posts/${postId}/reactions`);
            const data = await response.json();

            if (response.ok) {
                //counts
                const likesCountElement = postCard.querySelector('.likes-count');
                const confettiCountElement = postCard.querySelector('.confetti-count');

                if (likesCountElement) likesCountElement.textContent = data.likes_count;
                if (confettiCountElement) confettiCountElement.textContent = data.confetti_count;

                // Set active states
                if (data.user_reaction) {
                    const activeBtn = postCard.querySelector(`[data-type="${data.user_reaction}"]`);
                    if (activeBtn) {
                        activeBtn.classList.add(
                            data.user_reaction === 'like' ? 'text-blue-600' : 'text-yellow-600',
                            'scale-110'
                        );
                    }
                }
            }
        } catch (error) {
            console.error('Error loading reactions:', error);
        }
    });

    // Comment reactions
    document.querySelectorAll('.comment-reaction-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const commentId = this.dataset.commentId;
            const reactionType = this.dataset.type;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(`/comments/${commentId}/react`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        type: reactionType
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    const commentCard = this.closest('.bg-gray-50');

                    if (commentCard) {
                        const likesCountElement = commentCard.querySelector('.comment-likes-count');
                        const confettiCountElement = commentCard.querySelector('.comment-confetti-count');

                        if (likesCountElement) {
                            likesCountElement.textContent = data.likes_count;
                        }
                        if (confettiCountElement) {
                            confettiCountElement.textContent = data.confetti_count;
                        }

                        const allReactionBtns = commentCard.querySelectorAll('.comment-reaction-btn');
                        allReactionBtns.forEach(btn => {
                            btn.classList.remove('text-blue-600', 'text-yellow-600', 'scale-110');
                        });

                        if (data.user_reaction === reactionType) {
                            this.classList.add(
                                reactionType === 'like' ? 'text-blue-600' : 'text-yellow-600',
                                'scale-110'
                            );
                        }
                    }

                    this.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);

                } else {
                    console.error('Error:', data.error);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    // Load initial comment reaction states
    document.querySelectorAll('[data-comment-id]').forEach(async (element) => {
        const commentId = element.dataset.commentId;
        const commentCard = element.closest('.bg-gray-50');

        if (!commentCard) return;

        try {
            const response = await fetch(`/comments/${commentId}/reactions`);
            const data = await response.json();

            if (response.ok) {
                const likesCountElement = commentCard.querySelector('.comment-likes-count');
                const confettiCountElement = commentCard.querySelector('.comment-confetti-count');

                if (likesCountElement) likesCountElement.textContent = data.likes_count;
                if (confettiCountElement) confettiCountElement.textContent = data.confetti_count;

                if (data.user_reaction) {
                    const activeBtn = commentCard.querySelector(`[data-comment-id="${commentId}"][data-type="${data.user_reaction}"]`);
                    if (activeBtn) {
                        activeBtn.classList.add(
                            data.user_reaction === 'like' ? 'text-blue-600' : 'text-yellow-600',
                            'scale-110'
                        );
                    }
                }
            }
        } catch (error) {
            console.error('Error loading comment reactions:', error);
        }
    });
}

function initializePostTruncation() {
    document.querySelectorAll('[id^="text-"]').forEach(textElement => {
        const postId = textElement.id.replace('text-', '');
        const fadeElement = document.getElementById(`fade-${postId}`);

        if (!fadeElement) return;

        // Check if text is overflowing
        const maxHeight = 192; // max-h-48 in pixels (12rem = 192px)

        if (textElement.scrollHeight > maxHeight) {
            fadeElement.classList.remove('hidden');
        }
    });
}

function initializeReviewTruncation() {
    document.querySelectorAll('.review-text').forEach(textElement => {
        const reviewCard = textElement.closest('.review-card');
        if (!reviewCard) return;

        const readMoreBtn = reviewCard.querySelector('.read-more-btn');
        if (!readMoreBtn) return;

        const maxHeight = 24;

        if (textElement.scrollHeight > maxHeight) {
            readMoreBtn.classList.remove('hidden');
            textElement.classList.add('line-clamp-1');
        }

        // Toggle read more/less
        readMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (textElement.classList.contains('line-clamp-1')) {
                textElement.classList.remove('line-clamp-1');
                reviewCard.classList.remove('h-28', 'overflow-hidden');
                reviewCard.classList.add('h-auto');
                readMoreBtn.textContent = 'Read less';
            } else {
                textElement.classList.add('line-clamp-1');
                reviewCard.classList.remove('h-auto');
                reviewCard.classList.add('h-28', 'overflow-hidden');
                readMoreBtn.textContent = 'Read more';
            }
        });
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize reactions if user is authenticated
    const isAuthenticated = document.body.dataset.authenticated === 'true';
    if (isAuthenticated) {
        initializeReactions();
    }

    initializePostTruncation();
    initializeReviewTruncation();
});


//para pusher notifications
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

window.Echo.private(`notifications.${window.userId}`)
    .listen('.notification.created', (e) => {
        console.log('New notification:', e.notification);
        showNotification(e.notification);
    });

function showNotification(notification) {
    const container = document.getElementById('notification');

    let text = '';

    switch (notification.type) {
        case 'reaction':
            text = `${notification.name} liked your post`;
            break;
        case 'reactionComment':
            text = `${notification.name} liked your comment`;
            break;
        case 'comment':
            text = `${notification.name} commented on your post`;
            break;
        case 'followRequest':
            text = `${notification.name} wants to follow you`;
            break;
        case 'reply':
            text = `${notification.name} replied to your comment`
            break;
        default:
            text = 'You have a new notification';
    }

    container.innerHTML = `<a href="/notifications"> ${text} </a>`;

    // Mostrar notificação
    container.classList.remove('opacity-0');
    container.classList.add('opacity-100', 'pointer-events-auto');
    container.classList.remove('pointer-events-none');

    // Esconder depois de 5 segundos
    setTimeout(() => {
        container.classList.add('opacity-0');
        container.classList.remove('opacity-100', 'pointer-events-auto');
        container.classList.add('pointer-events-none');
    }, 5000);
}

