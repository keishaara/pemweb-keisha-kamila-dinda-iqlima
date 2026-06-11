function pilihMetode(el, tipe) {
    document.querySelectorAll('.method-item').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input').checked = true;

    const detailCard = document.getElementById('detail-pembayaran-card');
    const rekBox = document.getElementById('rekening-box');
    const qrisBox = document.getElementById('qris-box');
    const uploadBox = document.getElementById('upload-box');
    const detailTitle = document.getElementById('detail-title');

    if (tipe === 'tunai') {
        detailCard.style.display = 'none';
    } else {
        detailCard.style.display = 'block';
        if (tipe === 'transfer') {
            detailTitle.textContent = 'Detail Transfer';
            rekBox.style.display = 'block';
            qrisBox.style.display = 'none';
        } else if (tipe === 'qris') {
            detailTitle.textContent = 'Detail QRIS';
            rekBox.style.display = 'none';
            qrisBox.style.display = 'block';
        }
    }
}

function bukaLightboxQris() {
    const lb = document.getElementById('qrisLightbox');
    lb.classList.add('aktif');
    document.body.style.overflow = 'hidden';
}

function tutupLightboxQris(e) {
    if (e && e.target !== document.getElementById('qrisLightbox')) return;
    const lb = document.getElementById('qrisLightbox');
    lb.classList.remove('aktif');
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.qris-lightbox-close')?.addEventListener('click', (e) => {
        e.stopPropagation();
        const lb = document.getElementById('qrisLightbox');
        lb.classList.remove('aktif');
        document.body.style.overflow = '';
    });
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const lb = document.getElementById('qrisLightbox');
        lb.classList.remove('aktif');
        document.body.style.overflow = '';
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const checkedInput = document.querySelector('input[name="metode_pembayaran"]:checked');
    if(checkedInput) {
        const val = checkedInput.value;
        if (val === 'Tunai') pilihMetode(checkedInput.closest('label'), 'tunai');
        else if (val === 'Transfer Bank') pilihMetode(checkedInput.closest('label'), 'transfer');
        else if (val === 'QRIS') pilihMetode(checkedInput.closest('label'), 'qris');
    }
});
