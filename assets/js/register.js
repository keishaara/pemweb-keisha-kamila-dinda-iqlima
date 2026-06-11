const toggleRegPassword = document.querySelector('#toggleRegPassword');
const regPasswordField = document.querySelector('#regPasswordField');
if (toggleRegPassword && regPasswordField) {
    toggleRegPassword.addEventListener('click', function (e) {
        const type = regPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
        regPasswordField.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
}

const toggleRegConfirm = document.querySelector('#toggleRegConfirm');
const regConfirmField = document.querySelector('#regConfirmField');
if (toggleRegConfirm && regConfirmField) {
    toggleRegConfirm.addEventListener('click', function (e) {
        const type = regConfirmField.getAttribute('type') === 'password' ? 'text' : 'password';
        regConfirmField.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
}

document.getElementById('registerForm').addEventListener('submit', function(event) {
    var form = event.target;
    var nama = form.nama.value.trim();
    var npm = form.npm.value.trim();
    var email = form.email.value.trim();
    var password = form.password.value;
    var confirmPassword = form.konfirmasi_password.value;
    var wa = form.wa.value.trim();
    var errorMessage = '';

    if (!nama || !npm || !email || !password || !confirmPassword) {
        errorMessage = 'Data bertanda (*) wajib diisi.';
    } else if (!/^\S+@\S+\.\S+$/.test(email)) {
        errorMessage = 'Format email tidak valid.';
    } else if (password.length < 8) {
        errorMessage = 'Kata sandi harus minimal 8 karakter.';
    } else if (password !== confirmPassword) {
        errorMessage = 'Konfirmasi kata sandi tidak cocok.';
    } else if (wa && !/^\d{10,15}$/.test(wa)) {
        errorMessage = 'Nomor Whatsapp tidak valid.';
    }
    if (errorMessage) {
        event.preventDefault();
        alert(errorMessage);
    }
});
