<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'koneksi.php';

// Cek role user
if (($_SESSION['role'] ?? '') == 'admin') {
    header('Location: admindasboard.php');
    exit;
}

$username = $_SESSION['username'] ?? '';

if (!$username) {
    header('Location: login.php');
    exit;
}

try {
    // Ambil data berdasarkan nama_user
    $rentals = $colRental->find(
        [
            '$or' => [
                ['nama_user' => $username],
                ['user_id' => $username],
                ['username' => $username]
            ]
        ],
        [
            'sort' => ['_id' => -1]
        ]
    );

    $rentalArray = iterator_to_array($rentals);
} catch (Exception $e) {
    die("Gagal mengambil data database: " . $e->getMessage());
}

$pesan = $_GET['pesan'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Saya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *{ font-family:'Plus Jakarta Sans',sans-serif; }
        body {
            background: linear-gradient(135deg, #ffffff 0%, #002d6c 50%, #000015 100%);
        }

        .navbar{ background:white; border-bottom:1px solid #e5e7eb; padding:16px 0; }
        .navbar-brand{ font-weight:800; font-size:24px; color:#111827 !important; }
        .page-title{ font-size:34px; font-weight:800; margin-bottom:5px; }
        .subtitle{ color:#6b7280; }
        .rental-card{ background:white; border-radius:24px; padding:28px; box-shadow:0 10px 30px rgba(0,0,0,0.05); margin-bottom:20px; transition:.25s; }
        .rental-card:hover{ transform:translateY(-4px); }
        
        /* Modifikasi car-icon agar gambar masuk dengan rapi */
        .car-icon{ 
            width:85px; 
            height:75px; 
            border-radius:16px; 
            background:#eff6ff; 
            color:#2563eb; 
            display:flex; 
            align-items:center; 
            justify-content:center; 
            font-size:32px;
            overflow:hidden;
        }
        .car-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .car-name{ font-size:22px; font-weight:700; margin-bottom:3px; }
        .car-plat{ background:#f3f4f6; padding:5px 12px; border-radius:10px; font-size:13px; display:inline-block; margin-top:8px; }
        .info-box{ background:#f9fafb; border-radius:18px; padding:18px; margin-top:20px; }
        .info-label{ font-size:13px; color:#6b7280; margin-bottom:5px; }
        .info-value{ font-weight:700; font-size:17px; }
        .price{ font-size:28px; font-weight:800; color:#16a34a; }
        .badge-status{ padding:8px 16px; border-radius:999px; font-size:13px; font-weight:700; }
        .aktif{ background:#fef9c3; color:#854d0e; }
        .selesai{ background:#dcfce7; color:#166534; }
        .batal{ background:#fee2e2; color:#dc2626; }
        .empty-box{ background:white; border-radius:24px; padding:70px 30px; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,0.05); }
        .footer-text{ text-align:center; color:#9ca3af; margin-top:35px; font-size:14px; }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="catalog.php">
            <i class="bi bi-car-front-fill me-2"></i> Rental Mobil
        </a>

        <div class="d-flex gap-2 ms-auto">
            <a href="catalog.php" class="btn btn-outline-dark">
                <i class="bi bi-grid me-2"></i> Katalog
            </a>
            <a href="logout.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container py-5">

    <div class="mb-4">
        <div class="page-title">Rental Saya</div>
        <div class="subtitle">Halo, <?= htmlspecialchars($username) ?></div>
    </div>

    <?php if($pesan == 'rental_berhasil'): ?>
        <div class="alert alert-success rounded-4 border-0 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> Rental mobil baru berhasil dibuat!
        </div>
    <?php elseif($pesan == 'batal'): ?>
        <div class="alert alert-warning rounded-4 border-0 mb-4">
            Rental berhasil dibatalkan
        </div>
    <?php endif; ?>

    <?php
    $count = count($rentalArray);
    foreach($rentalArray as $r):
        $status = strtolower($r['status_rental'] ?? 'aktif');
    ?>

    <div class="rental-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex gap-3">
                    
                    <div class="car-icon">
                        <?php 
                        $foto = $r['foto_mobil'] ?? '';
                        if (!empty($foto) && file_exists('uploads/' . $foto)): 
                        ?>
                            <img src="uploads/<?= htmlspecialchars($foto) ?>" alt="Foto Mobil">
                        <?php else: ?>
                            <i class="bi bi-car-front"></i>
                        <?php endif; ?>
                    </div>

                    <div>
                        <div class="car-name">
                            <?= htmlspecialchars($r['nama_mobil'] ?? '-') ?>
                        </div>
                        <div class="text-muted">
                            <?= htmlspecialchars($r['merek'] ?? '-') ?>
                        </div>
                        <div class="car-plat">
                            <?= htmlspecialchars($r['plat_nomor'] ?? '-') ?>
                        </div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-label">Tanggal Rental</div>
                            <div class="info-value"><?= htmlspecialchars($r['tgl_mulai'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Sampai</div>
                            <div class="info-value"><?= htmlspecialchars($r['tgl_selesai'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-label">Durasi</div>
                            <div class="info-value"><?= (int)($r['jumlah_hari'] ?? 0) ?> Hari</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <div class="mb-3">
                    <span class="badge-status <?= $status ?>">
                        <?= ucfirst($status) ?>
                    </span>
                </div>
                <div class="price">
                    Rp <?= number_format((int)($r['total_harga'] ?? 0), 0, ',', '.') ?>
                </div>
                <div class="text-muted">
                    Rp <?= number_format((int)($r['harga_per_hari'] ?? 0), 0, ',', '.') ?>/hari
                </div>
            </div>
        </div>
    </div>

    <?php endforeach; ?>

    <?php if($count == 0): ?>
        <div class="empty-box">
            <i class="bi bi-receipt text-secondary" style="font-size:70px;"></i>
            <h3 class="fw-bold mt-4">Belum Ada Rental</h3>
            <p class="text-muted">Kamu belum pernah menyewa mobil</p>
            <a href="catalog.php" class="btn btn-dark px-4 mt-2">
                <i class="bi bi-car-front me-2"></i> Lihat Katalog
            </a>
        </div>
    <?php endif; ?>

    <div class="footer-text">
        Rental Mobil © <?= date('Y') ?> — Kelompok 4
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>