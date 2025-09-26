
function toggleMenu(postId) {
    const menu = document.getElementById('menu-' + postId);
    if (menu) {
        menu.classList.toggle('hidden');
        // Optional: Hide other open menus
        document.querySelectorAll('[id^="menu-"]').forEach(m => {
            if (m !== menu) m.classList.add('hidden');
        });
    }
}
// Optional: Hide menu when clicking outside
document.addEventListener('click', function(event) {
    document.querySelectorAll('[id^="menu-"]').forEach(menu => {
        if (!menu.classList.contains('hidden') && !menu.contains(event.target) && !event.target.matches('button[onclick^="toggleMenu"]')) {
            menu.classList.add('hidden');
        }
    });
});
// Hide the success message after 2 seconds
setTimeout(function() {
    var msg = document.getElementById('successmsg');
    if (msg) {
        msg.style.display = 'none';
    }
    }, 2000); 

    const imageInput = document.getElementById("imageInput");
const imagePreview = document.getElementById("imagePreview");
let selectedFiles = [];

imageInput.addEventListener("change", function () {
    selectedFiles = Array.from(this.files); // store files
    updatePreview();
});

function updatePreview() {
    imagePreview.innerHTML = "";
    
    if (selectedFiles.length > 0) {
        imagePreview.classList.remove("hidden");

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                // wrapper div for image + X
                const wrapper = document.createElement("div");
                wrapper.className = "relative lg:w-32 lg:h-32 sm:w-64 sm:h-64";

                const img = document.createElement("img");
                img.src = e.target.result;
                img.className = "lg:w-32 lg:h-32 sm:w-64 sm:h-64 object-cover rounded-lg border border-gray-700";

                const btn = document.createElement("button");
                btn.innerHTML = "✕";
                btn.type = "button";
                btn.className = "absolute top-0 right-0 bg-black bg-opacity-50 text-white rounded-full lg:w-6 lg:h-6 sm:w-12 sm:h-12 flex items-center justify-center lg:text-xs sm:text-3xl";
                btn.onclick = () => {
                    selectedFiles.splice(index, 1);
                    updatePreview();
                };

                wrapper.appendChild(img);
                wrapper.appendChild(btn);
                imagePreview.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });

        // Rebuild FileList for input
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(f => dataTransfer.items.add(f));
        imageInput.files = dataTransfer.files;
    } else {
        imagePreview.classList.add("hidden");
        imageInput.value = ""; // reset input
    }
}

const modal = document.getElementById('imageModal');
const modalImg = document.getElementById('modalImage');
const closeModalBtn = document.getElementById('closeModal');
const zoomInBtn = document.getElementById('zoomIn');
const zoomOutBtn = document.getElementById('zoomOut');

let currentScale = 1;
let currentX = 0;
let currentY = 0;
const SCALE_STEP = 0.2;
const MAX_SCALE = 3;
const MIN_SCALE = 0.5;

// Drag variables
let isDragging = false;
let startX = 0;
let startY = 0;

// Open modal
function openModal(src) {
    modalImg.src = src;
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Reset zoom and position
    currentScale = 1;
    currentX = 0;
    currentY = 0;
    updateTransform();
}

// Close modal
function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentScale = 1;
    currentX = 0;
    currentY = 0;
    updateTransform();
}

// Update transform for scale + translation
function updateTransform() {
    modalImg.style.transform = `scale(${currentScale}) translate(${currentX}px, ${currentY}px)`;
}

// Zoom handlers
function zoomIn() {
    currentScale = Math.min(currentScale + SCALE_STEP, MAX_SCALE);
    updateTransform();
}

function zoomOut() {
    currentScale = Math.max(currentScale - SCALE_STEP, MIN_SCALE);
    updateTransform();
}

// Event listeners
closeModalBtn.addEventListener('click', closeModal);
zoomInBtn.addEventListener('click', zoomIn);
zoomOutBtn.addEventListener('click', zoomOut);

// Close modal if clicking outside image
modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
});

// Mouse wheel zoom
modalImg.addEventListener('wheel', (e) => {
    e.preventDefault();
    if (e.deltaY < 0) zoomIn();
    else zoomOut();
});

// Drag-to-pan
modalImg.addEventListener('mousedown', (e) => {
    if (currentScale <= 1) return; // no drag if not zoomed
    isDragging = true;
    startX = e.clientX - currentX;
    startY = e.clientY - currentY;
    modalImg.style.cursor = 'grabbing';
});

document.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    currentX = e.clientX - startX;
    currentY = e.clientY - startY;
    updateTransform();
});

document.addEventListener('mouseup', () => {
    if (!isDragging) return;
    isDragging = false;
    modalImg.style.cursor = 'grab';
});

