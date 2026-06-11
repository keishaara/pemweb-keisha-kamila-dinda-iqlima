history.pushState(null, null, window.location.href);

window.addEventListener('popstate', function (event) {
    const yakinLogout = confirm("Apakah Anda ingin logout?");
    
    if (yakinLogout) {
        window.location.href = 'index.php?module=auth&action=logout'; 
    } else {
        history.pushState(null, null, window.location.href);
    }
});
