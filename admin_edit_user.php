<?php
require_once 'koneksi.php';
require_once 'auth.php';
requireAdmin('login.php');

if (empty($_GET['id'])) { header('Location: admin_users.php'); exit; }

try {
    $uid  = new MongoDB\BSON\ObjectId($_GET['id']);
    $user = $colUsers->findOne(['_id' => $uid]);
} catch (Exception $e) { header('Location: admin_users.php'); exit; }

if (!$user) { header('Location: admin_users.php'); exit; }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = trim($_POST['nama']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $role    = $_POST['role'] ?? 'user';
    $pw      = trim($_POST['password'] ?? '');

    if (!$nama || !$email) { $error = 'Nama dan email wajib diisi.'; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $error = 'Format email tidak valid.'; }
    else {
        try {
            // cek email duplikat (exclude diri sendiri)
            $dup = $colUsers->findOne(['email' => $email, '_id' => ['$ne' => $uid]]);
            if ($dup) { $error = 'Email sudah dipakai akun lain.'; }
            else {
                $set = [
                    'nama'    => $nama,
                    'email'   => $email,
                    'telepon' => $telepon,
                    'role'    => in_array($role, ['admin','user']) ? $role : 'user',
                ];
                if ($pw !== '') {
                    if (strlen($pw) < 6) { $error = 'Password minimal 6 karakter.'; goto end; }
                    $set['password'] = password_hash($pw, PASSWORD_DEFAULT);
                }
                $colUsers->updateOne(['_id' => $uid], ['$set' => $set]);
                header('Location: admin_users.php?pesan=update');
                exit;
            }
        } catch (Exception $e) { $error = 'Terjadi kesalahan.'; }
    }
    end:
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User — Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'navbar_admin.php'; ?>
<div class="container fade-in" style="max-width:600px">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="admindasboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="admin_users.php">Kelola User</a></li>
            <li class="breadcrumb-item active">Edit User</li>
        </ol>
    </nav>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Edit Data Pengguna</div>
        <div class="card-body p-4">
            <form method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= htmlspecialchars($_POST['nama'] ?? $user['nama']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="telepon" class="form-control"
                           value="<?= htmlspecialchars($_POST['telepon'] ?? $user['telepon'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Baru <span class="text-muted small">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter">
                </div>
                <div class="mb-4">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="user"  <?= (($_POST['role'] ?? $user['role']) === 'user')  ? 'selected' : '' ?>>User (Penyewa)</option>
                        <option value="admin" <?= (($_POST['role'] ?? $user['role']) === 'admin') ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="bi bi-floppy me-2"></i>Simpan Perubahan
                    </button>
                    <a href="admin_users.php" class="btn btn-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
    <p class="footer-text">Rental Mobil &copy; <?= date('Y') ?> — Kelompok 4</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
