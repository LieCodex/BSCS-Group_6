
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