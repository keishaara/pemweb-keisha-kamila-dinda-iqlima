function tolakDenganAlasan(id) {
    let alasan = prompt("Masukkan alasan penolakan acara ini:");
    if (alasan !== null && alasan.trim() !== "") {
        window.location.href = "index.php?module=admin&action=verifikasi&act=tolak&id=" + id + "&alasan=" + encodeURIComponent(alasan);
    } else if (alasan !== null) {
        alert("Alasan harus diisi!");
    }
}
