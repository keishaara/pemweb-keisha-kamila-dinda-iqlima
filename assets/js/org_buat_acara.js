document.addEventListener('DOMContentLoaded', function() {
    const posterInput = document.getElementById('posterInput');
    const posterPreview = document.getElementById('posterPreview');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');

    if (posterInput && posterPreview) {
        posterInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    posterPreview.src = e.target.result;
                    posterPreview.style.display = 'block';
                    if (uploadPlaceholder) uploadPlaceholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                posterPreview.src = '#';
                posterPreview.style.display = 'none';
                if (uploadPlaceholder) uploadPlaceholder.style.display = 'block';
            }
        });
    }
});
