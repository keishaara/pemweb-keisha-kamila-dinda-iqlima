const loginForm = document.getElementById('loginForm');
const clientError = document.getElementById('clientError');

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

    if (!identifier) {
        showClientError('Email wajib diisi.');
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

    if (identifier.includes('@')) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(identifier)) {
            showClientError('Format email tidak valid.');
            event.preventDefault();
            return;
        }
    }
});
