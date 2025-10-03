const imageInput = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');

let filesArray = [];

// Handle file selection
imageInput.addEventListener('change', function(e) {
    for (const file of e.target.files) {
        filesArray.push(file);
    }
    renderPreviews();
    imageInput.value = ''; // Reset input
});

// Render image previews
function renderPreviews() {
    imagePreview.innerHTML = '';
    
    if (filesArray.length > 0) {
        imagePreview.classList.remove('hidden');
        
        filesArray.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create container
                const div = document.createElement('div');
                div.className = "relative w-20 h-20";
                
                // Create image
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = "w-full h-full object-cover rounded border border-gray-400";
                
                // Create remove button
                const btn = document.createElement('button');
                btn.type = "button";
                btn.className = "absolute -top-2 -right-2 bg-gray-900 hover:bg-gray-800 rounded-full w-6 h-6 flex items-center justify-center text-white text-sm font-bold shadow-lg";
                btn.innerHTML = "&times;";
                btn.onclick = () => removeImage(idx);
                
                div.appendChild(img);
                div.appendChild(btn);
                imagePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        
        // Add "+" button to add more images
        const addBtn = document.createElement('label');
        addBtn.setAttribute('for', 'imageInput');
        addBtn.className = "w-20 h-20 flex items-center justify-center bg-gray-700 hover:bg-gray-600 rounded border border-gray-400 cursor-pointer";
        addBtn.innerHTML = '<span class="text-white text-3xl">+</span>';
        imagePreview.appendChild(addBtn);
    } else {
        imagePreview.classList.add('hidden');
    }
}

// Remove image from array
function removeImage(idx) {
    filesArray.splice(idx, 1);
    renderPreviews();
    updateFormFiles();
}

// Update the actual file input with current files
function updateFormFiles() {
    const dt = new DataTransfer();
    filesArray.forEach(file => dt.items.add(file));
    imageInput.files = dt.files;
}

// Update files on form submit
const form = document.querySelector('form[action="/create-post"]');
if (form) {
    form.addEventListener('submit', function(e) {
        updateFormFiles();
    });
}