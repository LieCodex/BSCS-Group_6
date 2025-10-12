document.getElementById('editImageInput')?.addEventListener('change', function(e) {
    let previewContainer = document.getElementById('editPreviewContainer');
    previewContainer.innerHTML = ''; // reset
    Array.from(e.target.files).forEach(file => {
        let reader = new FileReader();
        reader.onload = e => {
            let img = document.createElement('img');
            img.src = e.target.result;
            img.className = "w-24 h-24 object-cover rounded-lg";
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});