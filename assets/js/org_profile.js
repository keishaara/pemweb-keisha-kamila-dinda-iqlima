document.getElementById('inputFoto').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('previewImg');
            const defaultIcon = document.getElementById('defaultIcon');
            
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            if (defaultIcon) {
                defaultIcon.style.display = 'none';
            }
        }
        reader.readAsDataURL(file);
    }
});
