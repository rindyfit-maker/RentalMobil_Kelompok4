<?php
require_once 'koneksi.php';
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? 'admindasboard.php' : 'catalog.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $konfirm  = trim($_POST['konfirm']  ?? '');
    $telepon  = trim($_POST['telepon']  ?? '');

    if (!$nama || !$email || !$password || !$konfirm) {
        $error = 'Semua field wajib diisi (kecuali No. Telepon).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $konfirm) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        try {
            // Validasi ganda: cek email dan username apakah sudah ada di database
            $existingEmail = $colUsers->findOne(['email' => $email]);
            $existingUser  = $colUsers->findOne(['nama' => $nama]);

            if ($existingEmail) {
                $error = 'Email sudah terdaftar. Gunakan email lain.';
            } elseif ($existingUser) {
                $error = 'Nama sudah digunakan. Pilih nama lain.';
            } else {
                $colUsers->insertOne([
                    'nama'       => $nama,
                    'email'      => $email,
                    'password'   => $password,
                    'telepon'    => $telepon,
                    'role'       => 'customer',
                    'created_at' => new MongoDB\BSON\UTCDateTime(),
                ]);

                // Berhasil register langsung redirect ke halaman login.php
                header('Location: login.php?status=success');
                exit;
            }
        } catch (Exception $e) {
            $error = 'Terjadi kesalahan. Coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar — Rental Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #ffffff 0%, #002d6c 50%, #000015 100%);
            display: flex; align-items: center; justify-content: center; padding: 30px 0;
        }
        .wrap { width: 100%; max-width: 480px; padding: 20px; }
        .card {
            background: white; border-radius: 28px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3); overflow: hidden;
        }
        .card-head {
            background: linear-gradient(135deg, #06001e, #00043e);
            padding: 32px 40px 28px; text-align: center; color: white;
        }
        .card-head .icon { font-size: 36px; margin-bottom: 10px; }
        .card-head h1 { font-size: 22px; font-weight: 800; margin: 0 0 4px; }
        .card-head p  { margin: 0; opacity: .75; font-size: 13px; }
        .card-body-inner { padding: 32px 40px 36px; }
        .form-label { font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px; }
        .form-control {
            border-radius: 12px; padding: 11px 15px;
            border: 1.5px solid #e5e7eb; font-size: 14px;
            transition: border-color .2s;
        }
        .form-control:focus { border-color: #053896; box-shadow: 0 0 0 3px rgba(5,150,105,.1); }
        .btn-reg {
            background: linear-gradient(135deg, #06001e, #00043e);
            color: white; border: none; border-radius: 12px;
            padding: 13px; font-weight: 700; font-size: 15px;
            width: 100%; cursor: pointer;
        }
        .btn-reg:hover { opacity: .9; }
        .divider { border-top: 1px solid #f1f5f9; margin: 22px 0; }
        .login-link { text-align: center; font-size: 14px; color: #6b7280; }
        .login-link a { color: #053896; font-weight: 600; text-decoration: none; }
        .alert-err {
            background:#fef2f2; border:1px solid #fecaca; color:#dc2626;
            border-radius:12px; padding:11px 15px; font-size:14px;
            margin-bottom:18px; display:flex; gap:8px; align-items:center;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="card-head">
            <div class="icon"><i class="bi bi-person-plus-fill"></i></div>
            <h1>Buat Akun Baru</h1>
            <p>Daftarkan diri Anda untuk mulai rental</p>
        </div>
        <div class="card-body-inner">
            <?php if ($error): ?>
                <div class="alert-err"><i class="bi bi-exclamation-circle-fill"></i><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Nama lengkap Anda"
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="email@example.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx"
                           value="<?= htmlspecialchars($_POST['telepon'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="konfirm" class="form-control" placeholder="Ulangi password" required>
                </div>
                <button type="submit" class="btn-reg">
                    <i class="bi bi-person-check me-2"></i>Daftar Sekarang
                </button>
            </form>
            <div class="divider"></div>
            <div class="login-link">Sudah punya akun? <a href="login.php">Masuk di sini</a></div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>