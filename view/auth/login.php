<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="split-screen">
        <div class="form-side">
            <a href="index.html" class="logo"><img src="assets/img/icon.png" alt="Evently"> Evently</a>
            <h2>Selamat Datang Kembali</h2>
            <p class="text-muted mb-3">Masuk ke akun kamu untuk menemukan kegiatan kampus terbaru.</p>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Email atau NPM</label>
                    <input type="text" name="email" class="form-control" placeholder="npm@unila.ac.id / NPM" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Masuk sebagai</label>
                    <select name="role" class="form-control" required>
                        <option value="" selected disabled>Pilih role</option>
                        <option value="admin">Admin</option>
                        <option value="organisasi">Organisasi</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="auth-remember">
                    <label class="d-flex align-center gap-1" style="cursor:pointer;">
                        <input type="checkbox"> Ingat Saya
                    </label>
                    <a href="#" class="auth-forgot">Lupa kata sandi?</a>
                </div>

                <div class="form-group" style="display: grid; gap: 10px; margin-top: 10px;">
                    <button type="submit" formaction="admin_dashboard.html" formmethod="get" class="btn btn-primary btn-block">Masuk ke Admin</button>
                    <button type="submit" formaction="org_dashboard.html" formmethod="get" class="btn btn-primary btn-block">Masuk ke Organisasi</button>
                    <button type="submit" formaction="user_dashboard.html" formmethod="get" class="btn btn-primary btn-block">Masuk ke User</button>
                </div>

                <p class="auth-footer">Belum punya akun? <a href="register.html">Daftar gratis</a></p>
            </form>
        </div>
        <div class="img-side"></div>
    </div>
</body>
</html>
