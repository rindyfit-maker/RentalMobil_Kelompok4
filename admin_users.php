<?php
require_once 'koneksi.php';
require_once 'auth.php';
requireAdmin('login.php');

// Handle actions
$pesan = $_GET['pesan'] ?? '';

// Delete user
if (!empty($_GET['hapus'])) {
    try {
        $uid = new MongoDB\BSON\ObjectId($_GET['hapus']);
        // Don't delete self
        $self = getSessionUser();
        if ($_GET['hapus'] !== $self['id']) {
            $colUsers->deleteOne(['_id' => $uid]);
        }
        header('Location: admin_users.php?pesan=hapus');
        exit;
    } catch (Exception $e) {}
}

$users = $colUsers->find([], ['sort' => ['_id' => -1]]);
$self  = getSessionUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola User — Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .role-admin { background:#fee2e2; color:#dc2626; padding:3px 10px; border-radius:999px; font-size:12px; font-weight:700; }
        .role-user  { background:#dbeafe; color:#2563eb; padding:3px 10px; border-radius:999px; font-size:12px; font-weight:700; }
    </style>
</head>
<body>

<?php include 'navbar_admin.php'; ?>

<div class="container fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">Kelola Pengguna</h2>
            <p class="text-muted mb-0">Daftar semua akun yang terdaftar</p>
        </div>
        <a href="admin_tambah_user.php" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>Tambah User
        </a>
    </div>

    <?php if ($pesan == 'hapus'): ?>
        <div class="alert alert-danger"><i class="bi bi-trash me-2"></i>User berhasil dihapus.</div>
    <?php elseif ($pesan == 'tambah'): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>User berhasil ditambahkan.</div>
    <?php elseif ($pesan == 'update'): ?>
        <div class="alert alert-info"><i class="bi bi-pencil me-2"></i>User berhasil diperbarui.</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Role</th>
                            <th>Terdaftar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; foreach ($users as $u): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-semibold">
                                <?= htmlspecialchars($u['nama']) ?>
                                <?php if ((string)$u['_id'] === $self['id']): ?>
                                    <span class="badge bg-secondary ms-1" style="font-size:10px;">Anda</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= htmlspecialchars($u['telepon'] ?? '-') ?></td>
                            <td>
                                <span class="role-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span>
                            </td>
                            <td class="text-muted small">
                                <?php
                                if (!empty($u['created_at'])) {
                                    $dt = $u['created_at']->toDateTime();
                                    $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                    echo $dt->format('d M Y');
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="admin_edit_user.php?id=<?= $u['_id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ((string)$u['_id'] !== $self['id']): ?>
                                        <a href="admin_users.php?hapus=<?= $u['_id'] ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Hapus user <?= htmlspecialchars($u['nama']) ?>?')"
                                           title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($no == 1): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada pengguna.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p class="footer-text">Rental Mobil &copy; <?= date('Y') ?> — Kelompok 4</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
