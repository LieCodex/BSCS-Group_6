document.querySelectorAll('.like-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const postId = form.getAttribute('data-post-id');
        const methodInput = form.querySelector('input[name="_method"]');
        const method = methodInput ? methodInput.value : 'POST';
        const url = form.action;
        const data = new FormData(form);

        let config = {};
        if (method === 'DELETE') {
            config.headers = { 'X-HTTP-Method-Override': 'DELETE' };
        }

        axios.post(url, data, config)
            .then(response => {
                // Update like count
                document.getElementById('like-count-' + postId).innerText = response.data.likes;
                // Update icon color/fill
                const icon = document.getElementById('like-icon-' + postId);
                const countSpan = document.getElementById('like-count-' + postId);
                if (response.data.liked) {
                    icon.setAttribute('fill', 'orange');
                    icon.setAttribute('stroke', 'orange');
                    countSpan.classList.remove('text-white');
                    countSpan.classList.add('text-orange-400');
                    form.classList.add('text-orange-400');
                } else {
                    icon.setAttribute('fill', 'none');
                    icon.setAttribute('stroke', 'white');
                    countSpan.classList.remove('text-orange-400');
                    countSpan.classList.add('text-white');
                    form.classList.remove('text-orange-400');
                }
                // Toggle method for like/unlike
                if (response.data.liked) {
                    if (!form.querySelector('input[name="_method"]')) {
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);
                        form.action = response.data.unlike_url;
                    }
                } else {
                    if (form.querySelector('input[name="_method"]')) {
                        form.querySelector('input[name="_method"]').remove();
                        form.action = response.data.like_url;
                    }
                }
            })
            .catch(error => {
                alert('Error liking/unliking');
            });
    });
});