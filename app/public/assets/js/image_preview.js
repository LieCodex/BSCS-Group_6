document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("imageInput");

    if (input) {
        input.addEventListener("change", function () {
            // detect which container exists (create or edit)
            const previewContainer = document.getElementById("previewContainer") || document.getElementById("editPreviewContainer");
            if (!previewContainer) return;

            // clear old previews
            previewContainer.innerHTML = "";

            Array.from(this.files).forEach(file => {
                if (!file.type.startsWith("image/")) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.classList.add("rounded", "m-2");
                    img.style.width = "100px";
                    img.style.height = "100px";
                    img.style.objectFit = "cover";
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    }
});
