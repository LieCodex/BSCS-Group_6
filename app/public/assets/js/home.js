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

setTimeout(() => {
    const msg = document.getElementById('successmsg');
    if (msg) {
        msg.style.transition = "opacity 0.5s ease";
        msg.style.opacity = "0";
        setTimeout(() => msg.remove(), 500);
    }
}, 1000);


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


