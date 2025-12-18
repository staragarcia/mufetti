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

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeReactions();
    initializePostTruncation();
});