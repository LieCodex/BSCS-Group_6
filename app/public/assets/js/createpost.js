// createpost.js - add button hidden by default until first image is added
document.addEventListener('DOMContentLoaded', () => {
    const textarea = document.getElementById('postBody');
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

    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const form = document.getElementById('createPostForm');

    let filesArray = [];
    let uniqueIdCounter = 0;

    function createAddButton() {
        const addBtn = document.createElement('label');
        addBtn.setAttribute('for', 'imageInput');
        addBtn.className = "w-20 h-20 flex items-center justify-center bg-gray-700 hover:bg-gray-600 rounded border border-gray-400 cursor-pointer add-btn";
        addBtn.innerHTML = '<span class="text-white text-3xl">+</span>';
        addBtn.style.order = '0';
        addBtn.title = "Add images";
        return addBtn;
    }

    function renderPreviews() {
        imagePreview.innerHTML = '';

        // If there are no files, hide the whole preview row (so no empty + box)
        if (filesArray.length === 0) {
            imagePreview.classList.add('hidden');
            return;
        }

        // When we have at least one file, render add button first
        imagePreview.classList.remove('hidden');
        const addBtn = createAddButton();
        imagePreview.appendChild(addBtn);

        // Render previews after the add button
        filesArray.forEach(item => {
            const file = item.file;
            const reader = new FileReader();

            const container = document.createElement('div');
            container.className = "relative w-20 h-20";
            container.style.order = '1';

            const placeholder = document.createElement('div');
            placeholder.className = "w-full h-full bg-gray-800 rounded border border-gray-700 flex items-center justify-center text-gray-500";
            placeholder.innerText = "…";
            container.appendChild(placeholder);

            const btn = document.createElement('button');
            btn.type = "button";
            btn.className = "absolute -top-2 -right-2 bg-gray-900 hover:bg-gray-800 rounded-full w-6 h-6 flex items-center justify-center text-white text-sm font-bold shadow-lg";
            btn.innerHTML = "&times;";
            btn.title = "Remove image";
            btn.onclick = () => removeImageById(item.id);
            container.appendChild(btn);

            reader.onload = function(e) {
                container.removeChild(placeholder);
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = file.name;
                img.className = "w-full h-full object-cover rounded border border-gray-400";
                container.appendChild(img);
            };

            reader.readAsDataURL(file);
            imagePreview.appendChild(container);
        });
    }

    function removeImageById(id) {
        const idx = filesArray.findIndex(item => item.id === id);
        if (idx !== -1) {
            filesArray.splice(idx, 1);
            renderPreviews();
            updateFormFiles();
        }
    }

    function updateFormFiles() {
        const dt = new DataTransfer();
        filesArray.forEach(item => dt.items.add(item.file));
        if (imageInput) imageInput.files = dt.files;
    }

    function isDuplicateFile(file) {
        return filesArray.some(item => item.file.name === file.name && item.file.size === file.size);
    }

    if (imageInput) {
        imageInput.addEventListener('change', (e) => {
            const chosen = Array.from(e.target.files);
            let added = false;
            for (const file of chosen) {
                if (isDuplicateFile(file)) continue;
                filesArray.push({ id: Date.now() + (uniqueIdCounter++), file });
                added = true;
            }
            if (added) {
                renderPreviews();
                updateFormFiles();
            }
            imageInput.value = '';
        });
    }

    if (form) {
        form.addEventListener('submit', () => {
            updateFormFiles();
        });
    }

    const openDraftsBtn = document.getElementById('openDraftsBtn');
    if (openDraftsBtn) {
        openDraftsBtn.addEventListener('click', (ev) => {
            ev.preventDefault();
            alert('Drafts feature coming soon!');
        });
    }

    // initial render (will hide preview area because filesArray is empty)
    renderPreviews();
});
