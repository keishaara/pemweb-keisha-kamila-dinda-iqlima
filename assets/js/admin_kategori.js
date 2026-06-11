function bukaModal() { document.getElementById('modalTambah').style.display = 'flex'; }
function tutupModal() { document.getElementById('modalTambah').style.display = 'none'; }
function bukaModalEdit(id, nama, deskripsi) {
    document.getElementById('edit_id_kategori').value = id;
    document.getElementById('edit_nama_kategori').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi;
    document.getElementById('modalEdit').style.display = 'flex';
}
function tutupModalEdit() { document.getElementById('modalEdit').style.display = 'none'; }
