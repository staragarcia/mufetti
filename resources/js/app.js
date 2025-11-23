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
                    // counts
                    const postCard = this.closest('.bg-white');
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
        const postCard = element.closest('.bg-white');
        
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
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeReactions();
});

