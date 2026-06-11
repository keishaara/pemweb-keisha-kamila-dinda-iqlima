document.getElementById('inputFoto').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('previewImg');
            const defaultAvatar = document.getElementById('defaultAvatar');
            
            previewImg.src = e.target.result;
            previewImg.style.display = 'block'; 
            if (defaultAvatar) {
                defaultAvatar.style.display = 'none'; 
            }
        }
        reader.readAsDataURL(file);
    }
});
