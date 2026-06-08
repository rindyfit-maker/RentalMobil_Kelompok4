<?php
require_once 'koneksi.php';
require_once 'auth.php';
requireAdmin('login.php');

// Update status rental
$pesan = $_GET['pesan'] ?? '';

if (!empty($_GET['selesai'])) {
    try {
        $rid = new MongoDB\BSON\ObjectId($_GET['selesai']);
        $r   = $colRental->findOne(['_id' => $rid]);
        if ($r) {
            $colRental->updateOne(['_id' => $rid], ['$set' => ['status_rental' => 'Selesai', 'updated_at' => new MongoDB\BSON\UTCDateTime()]]);
            // Kembalikan status mobil ke Tersedia
            $collection->updateOne(['_id' => $r['mobil_id']], ['$set' => ['status' => 'Tersedia']]);
        }
        header('Location: admin_rental.php?pesan=selesai');
        exit;
    } catch (Exception $e) {}
}

$rentals = $colRental->find([], ['sort' => ['_id' => -1]]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Rental — Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .badge-aktif   { background:#fef9c3;color:#854d0e;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700; }
        .badge-selesai { background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700; }
        .badge-batal   { background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700; }
    </style>
</head>
<body>
<?php include 'navbar_admin.php'; ?>
<div class="container fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">Data Rental</h2>
            <p class="text-muted mb-0">Semua transaksi penyewaan mobil</p>
        </div>
        <a href="admindasboard.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <?php if ($pesan == 'selesai'): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>Rental ditandai selesai. Mobil kembali tersedia.</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Penyewa</th>
                            <th>Mobil</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Hari</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; foreach ($rentals as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($r['nama_penyewa'] ?? '-') ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($r['email_penyewa'] ?? '') ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($r['nama_mobil'] ?? '-') ?></div>
                                <code class="small"><?= htmlspecialchars($r['plat_nomor'] ?? '') ?></code>
                            </td>
                            <td><?= htmlspecialchars($r['tgl_mulai'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($r['tgl_selesai'] ?? '-') ?></td>
                            <td class="fw-bold"><?= (int)($r['jumlah_hari'] ?? 0) ?></td>
                            <td class="fw-bold text-success">
                                Rp <?= number_format((int)($r['total_harga'] ?? 0), 0, ',', '.') ?>
                            </td>
                            <td>
                                <?php $st = $r['status_rental'] ?? 'Aktif'; ?>
                                <span class="badge-<?= strtolower($st) ?>"><?= htmlspecialchars($st) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if (($r['status_rental'] ?? '') === 'Aktif'): ?>
                                    <a href="admin_rental.php?selesai=<?= $r['_id'] ?>"
                                       class="btn btn-success btn-sm"
                                       onclick="return confirm('Tandai rental ini sebagai selesai?')">
                                        <i class="bi bi-check-lg me-1"></i>Selesai
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($no == 1): ?>
                        <tr><td colspan="9" class="text-center py-5 text-muted">Belum ada data rental.</td></tr>
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
