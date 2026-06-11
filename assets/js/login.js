const loginForm = document.getElementById('loginForm');
const clientError = document.getElementById('clientError');

const togglePassword = document.querySelector('#togglePassword');
const passwordField = document.querySelector('#passwordField');

if (togglePassword && passwordField) {
    togglePassword.addEventListener('click', function (e) {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
}

function showClientError(message) {
    clientError.textContent = message;
    clientError.style.display = 'block';
}

function clearClientError() {
    clientError.textContent = '';
    clientError.style.display = 'none';
}

loginForm.addEventListener('submit', function (event) {
    clearClientError();

    const identifier = loginForm.elements['identifier'].value.trim();
    const password = loginForm.elements['password'].value;
    const role = loginForm.elements['role'].value;

    if (!identifier) {
        showClientError('Email atau NPM wajib diisi.');
        event.preventDefault();
        return;
    }

    if (!password) {
        showClientError('Kata sandi wajib diisi.');
        event.preventDefault();
        return;
    }

    if (password.length < 8) {
        showClientError('Kata sandi minimal 8 karakter.');
        event.preventDefault();
        return;
    }

    if (!role) {
        showClientError('Pilih role terlebih dahulu.');
        event.preventDefault();
        return;
    }

    if (identifier.includes('@')) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(identifier)) {
            showClientError('Format email tidak valid.');
            event.preventDefault();
            return;
        }
    } else {
        const npmPattern = /^[A-Za-z0-9]+$/;
        if (!npmPattern.test(identifier)) {
            showClientError('NPM harus diisi tanpa spasi atau karakter khusus.');
            event.preventDefault();
            return;
        }
    }
});
