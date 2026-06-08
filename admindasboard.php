<?php
session_start();

if (($_SESSION['role'] ?? '') != 'admin') {
    header('Location: catalog.php');
    exit;
}

require_once 'koneksi.php';
require 'vendor/autoload.php';

use MongoDB\BSON\Regex;

// Search
$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $filter = [
        '$or' => [
            ['nama_mobil' => new Regex($search, 'i')],
            ['merek' => new Regex($search, 'i')],
            ['plat_nomor' => new Regex($search, 'i')],
        ]
    ];
} else {
    $filter = [];
}

// Data mobil
$options = [
    'sort' => ['_id' => -1]
];

$mobils = $collection->find($filter, $options);

// Statistik
$totalSemua = $collection->countDocuments([]);

$totalTersedia = $collection->countDocuments([
    'status' => 'Tersedia'
]);

$totalDisewa = $collection->countDocuments([
    'status' => 'Disewa'
]);

$pesan = $_GET['pesan'] ?? '';

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Rental Mobil</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
    
    <style>
        /* Gaya tambahan agar tabel dengan gambar terlihat sangat rapi */
        .table img {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .img-fallback {
            width: 80px;
            height: 50px;
            background: #f3f4f6;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 20px;
        }
    </style>
</head>

<body style="background: linear-gradient(135deg, #ffffff 0%, #002d6c 50%, #000015 100%);">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-car-front-fill me-2 text-primary"></i>
            Rental Mobil <span class="text-primary">Admin</span>
        </a>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-danger rounded-4 px-3 btn-sm">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container fade-in py-5">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Rental Mobil</h2>
            <p class="text-muted mb-0">Sistem manajemen rental mobil modern</p>
        </div>
        <a href="tambah.php" class="btn btn-primary rounded-3 px-4">
            <i class="bi bi-plus-lg me-2"></i> Tambah Mobil
        </a>
    </div>

    <?php if($pesan == 'tambah'): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> Data berhasil ditambahkan
        </div>
    <?php endif; ?>

    <?php if($pesan == 'update'): ?>
        <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-pencil-square me-2"></i> Data berhasil diperbarui
        </div>
    <?php endif; ?>

    <?php if($pesan == 'hapus'): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-trash-fill me-2"></i> Data berhasil dihapus
        </div>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card p-4 bg-white border rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label text-muted small fw-bold text-uppercase">Total Mobil</div>
                        <div class="stat-value fs-2 fw-bold mt-1"><?= $totalSemua ?></div>
                    </div>
                    <i class="bi bi-car-front fs-2 text-secondary bg-light p-2 rounded-3"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card p-4 bg-white border rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label text-muted small fw-bold text-uppercase">Tersedia</div>
                        <div class="stat-value fs-2 fw-bold mt-1 text-success"><?= $totalTersedia ?></div>
                    </div>
                    <i class="bi bi-check-circle fs-2 text-success bg-success-subtle p-2 rounded-3"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card p-4 bg-white border rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label text-muted small fw-bold text-uppercase">Disewa</div>
                        <div class="stat-value fs-2 fw-bold mt-1 text-danger"><?= $totalDisewa ?></div>
                    </div>
                    <i class="bi bi-clock-history fs-2 text-danger bg-danger-subtle p-2 rounded-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET">
                <div class="input-group">
                    <input
                        type="text"
                        name="search"
                        class="form-control border-0 bg-light"
                        placeholder="Cari nama mobil, merek, atau plat nomor..."
                        value="<?= htmlspecialchars($search) ?>"
                    >
                    <button class="btn btn-primary px-4 rounded-3">
                        <i class="bi bi-search me-2"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light text-uppercase font-monospace small">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Foto</th> <th>Mobil</th>
                            <th>Merek</th>
                            <th>Tahun</th>
                            <th>Plat</th>
                            <th>Harga (Per Hari)</th>
                            <th>Status</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                    $no = 1;
                    foreach($mobils as $m):
                        $foto = $m['foto_url'] ?? '';
                    ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= $no++ ?></td>
                            
                            <td>
                                <?php if (!empty($foto) && file_exists('uploads/' . $foto)): ?>
                                    <img src="uploads/<?= htmlspecialchars($foto) ?>" alt="Foto <?= htmlspecialchars($m['nama_mobil'] ?? 'Mobil') ?>">
                                <?php else: ?>
                                    <div class="img-fallback">
                                        <i class="bi bi-car-front"></i>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td class="fw-bold text-dark">
                                <?= htmlspecialchars($m['nama_mobil'] ?? '-') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($m['merek'] ?? '-') ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?= htmlspecialchars($m['tahun'] ?? '-') ?></span>
                            </td>
                            <td>
                                <code class="text-primary fw-bold">
                                    <?= htmlspecialchars($m['plat_nomor'] ?? '-') ?>
                                </code>
                            </td>
                            <td class="fw-semibold text-dark">
                                Rp <?= number_format((int)($m['harga_per_hari'] ?? 0), 0, ',', '.') ?>
                            </td>
                            <td>
                                <?php if(($m['status'] ?? '') == 'Tersedia'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                        Tersedia
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                                        Disewa
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4">
                                <div class="d-flex gap-2 justify-content-center">

                                    <a
                                        href="detail.php?id=<?= $m['_id'] ?>"
                                        class="btn btn-info btn-sm"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>

                                    <a
                                        href="delete.php?id=<?= $m['_id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                                    >

                                        <i class="bi bi-trash"></i>

                                    </a>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if($no == 1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-database-x fs-1 d-block mb-3 text-secondary"></i>
                                Data mobil tidak ditemukan
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p class="text-center text-muted small mt-5">
        Rental Mobil &copy; <?= date('Y') ?> — Kelompok 4
    </p>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>