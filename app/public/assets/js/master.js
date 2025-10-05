document.querySelectorAll('.like-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const postId = form.getAttribute('data-post-id');
        const methodInput = form.querySelector('input[name="_method"]');
        const method = methodInput ? methodInput.value : 'POST';
        const url = form.action;
        const data = new FormData(form);

        // Ensure CSRF token
        if (!data.has('_token')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                data.append('_token', csrfToken.content);
            }
        }

        const icon = document.getElementById('like-icon-' + postId);
        const countSpan = document.getElementById('like-count-' + postId);
        let currentLikes = parseInt(countSpan.innerText);
        let isLiked = method === 'DELETE'; // if DELETE, user already liked

        //(instant UI change)
        if (isLiked) {
            // user is unliking
            icon.setAttribute('fill', 'none');
            icon.setAttribute('stroke', 'white');
            countSpan.classList.remove('text-orange-400');
            countSpan.classList.add('text-white');
            form.classList.remove('text-orange-400');
            countSpan.innerText = currentLikes - 1;

            // reset to like action
            if (form.querySelector('input[name="_method"]')) {
                form.querySelector('input[name="_method"]').remove();
            }
        } else {
            // user is liking
            icon.setAttribute('fill', 'orange');
            icon.setAttribute('stroke', 'orange');
            countSpan.classList.remove('text-white');
            countSpan.classList.add('text-orange-400');
            form.classList.add('text-orange-400');
            countSpan.innerText = currentLikes + 1;

            // set up for unlike
            if (!form.querySelector('input[name="_method"]')) {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
            }
        }

        // --- Backend request ---
        let config = {};
        if (method === 'DELETE') {
            config.headers = { 'X-HTTP-Method-Override': 'DELETE' };
        }

        axios.post(url, data, config)
            .then(response => {
                // Trust backend final state (in case of race conditions)
                countSpan.innerText = response.data.likes;
                form.action = response.data.liked 
                    ? response.data.unlike_url 
                    : response.data.like_url;
            })
            .catch(error => {
                if (error.response) {
                    console.error('Backend responded with error:', error.response.status, error.response.data);
                } else {
                    console.error('Request failed:', error.message);
                }
                alert('Error liking/unliking'); // optional, can be removed later
            });

    });
});

    function toggleMenu(postId) {
        const menu = document.getElementById(`menu-${postId}`);

        // Close other open menus first (so only one stays open)
        document.querySelectorAll('[id^="menu-"]').forEach(m => {
            if (m.id !== `menu-${postId}`) {
                m.classList.add('hidden');
            }
        });

        // Toggle the clicked menu
        menu.classList.toggle('hidden');
    }

    // Optional: close menus when clicking outside
    document.addEventListener('click', function(event) {
        const isMenuButton = event.target.closest('button[onclick^="toggleMenu"]');
        const isMenu = event.target.closest('[id^="menu-"]');

        if (!isMenu && !isMenuButton) {
            document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
        }
    });
