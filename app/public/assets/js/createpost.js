document.addEventListener('alpine:init', () => {
    const textarea = document.getElementById('postBody');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const openDraftsBtn = document.getElementById('openDraftsBtn');

    // Auto-expand textarea
    if (textarea) {
        textarea.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';

            const modalCard = this.closest('.modal-card');
            if (modalCard) {
                const newHeight = Math.min(this.scrollHeight + 300, window.innerHeight - 100);
                modalCard.style.minHeight = newHeight + 'px';
            }
        });
    }

    // Image preview
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', (e) => {
            imagePreview.innerHTML = '';
            const files = e.target.files;
            if (files.length > 0) {
                imagePreview.classList.remove('hidden');
                for (const file of files) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const img = document.createElement('img');
                        img.src = event.target.result;
                        img.classList = 'w-20 h-20 object-cover rounded-lg border border-gray-700';
                        imagePreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                imagePreview.classList.add('hidden');
            }
        });
    }

    // Drafts button logic (optional popup or redirect)
    if (openDraftsBtn) {
        openDraftsBtn.addEventListener('click', (e) => {
            e.preventDefault();
            alert('Drafts feature coming soon!'); // Replace with modal or route later
        });
    }
});
