<?php

session_start();

require_once 'koneksi.php';

// Kalau admin masuk catalog → balik ke dashboard admin
if ($_SESSION['role'] == 'admin') {
    header('Location: admindasboard.php');
    exit;
}

require 'vendor/autoload.php';

use MongoDB\BSON\Regex;

// Search
$search = trim($_GET['search'] ?? '');
$filter_status = $_GET['status'] ?? '';
$filter = [];

if ($search !== '') {
    $filter['$or'] = [
        ['nama_mobil' => new Regex($search, 'i')],
        ['merek' => new Regex($search, 'i')]
    ];
}

// Filter tersedia
if ($filter_status === 'tersedia') {
    $filter['status'] = 'Tersedia';
}

// Ambil data mobil
$mobils = $collection->find(
    $filter,
    [
        'sort' => ['_id' => -1]
    ]
);

// Statistik
$totalSemua = $collection->countDocuments([]);
$totalTersedia = $collection->countDocuments([
    'status' => 'Tersedia'
]);

$pesan = $_GET['pesan'] ?? '';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Mobil</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #ffffff 0%, #002d6c 50%, #000015 100%);
        }


        .navbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 18px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 24px;
            color: #111827 !important;
        }

        .welcome-box {
            background: #111827;
            color: white;
            border-radius: 30px;
            padding: 40px;
            margin-bottom: 35px;
        }

        .welcome-box h2 {
            font-weight: 800;
            margin-bottom: 10px;
        }

        .welcome-box p {
            opacity: .8;
            margin: 0;
        }

        .stats-box {
            background: rgba(255,255,255,.08);
            padding: 20px;
            border-radius: 20px;
            min-width: 180px;
        }

        .stats-value {
            font-size: 34px;
            font-weight: 800;
        }

        .card-search {
            background: white;
            border: none;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .form-control, .form-select {
            height: 52px;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: none;
            border-color: #111827;
        }

        .btn-dark-modern {
            background: #111827;
            border: none;
            border-radius: 14px;
            padding: 0 24px;
            color: white;
            font-weight: 600;
        }

        .car-card {
            background: white;
            border: none;
            border-radius: 28px;
            overflow: hidden;
            height: 100%;
            transition: .25s;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .car-card:hover {
            transform: translateY(-5px);
        }

        .car-top {
            background: #f9fafb;
            height: 220px; /* Tinggi gambar diseragamkan */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .car-top i {
            font-size: 70px;
            color: #111827;
        }

        .car-body {
            padding: 26px;
        }

        .car-name {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .car-brand {
            color: #6b7280;
            margin-bottom: 18px;
        }

        .tag-wrap {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .car-tag {
            background: #f3f4f6;
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 13px;
            color: #374151;
        }

        .price {
            font-size: 28px;
            font-weight: 800;
            color: #16a34a;
        }

        .price small {
            font-size: 13px;
            color: #9ca3af;
            font-weight: 500;
        }

        .badge-ready {
            background: #dcfce7;
            color: #166534;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        .badge-rent {
            background: #fee2e2;
            color: #991b1b;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        .btn-detail {
            background: #f3f4f6;
            border: none;
            border-radius: 14px;
            padding: 10px 18px;
            text-decoration: none;
            color: #111827;
            font-weight: 600;
        }

        .btn-rent {
            background: #111827;
            border: none;
            border-radius: 14px;
            padding: 10px 18px;
            text-decoration: none;
            color: white;
            font-weight: 600;
        }

        .footer-text {
            text-align: center;
            color: #9ca3af;
            padding: 40px 0 20px;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="catalog.php">
            <i class="bi bi-car-front-fill me-2"></i> Rental Mobil
        </a>
        <div class="d-flex gap-2">
            <a href="my_rental.php" class="btn btn-outline-dark rounded-4">Rental Saya</a>
            <a href="logout.php" class="btn btn-danger rounded-4">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5">

    <?php if($pesan == 'rental_berhasil'): ?>
        <div class="alert alert-success rounded-4 border-0 mb-4">
            Rental berhasil dibuat
        </div>
    <?php endif; ?>

    <div class="welcome-box">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
            <div>
                <h2>Halo, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h2>
                <p>Temukan mobil terbaik untuk perjalanan Anda</p>
            </div>
            <div class="stats-box">
                <div class="small opacity-75 mb-1">Mobil Tersedia</div>
                <div class="stats-value"><?= $totalTersedia ?></div>
            </div>
        </div>
    </div>

    <div class="card-search">
        <form method="GET">
            <div class="row g-3">
                <div class="col-md-7">
                    <input type="text" name="search" class="form-control" placeholder="Cari mobil atau merek..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="tersedia" <?= $filter_status == 'tersedia' ? 'selected' : '' ?>>Tersedia Saja</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn-dark-modern">Cari</button>
                </div>
            </div>
        </form>
    </div>

    <div class="row g-4">
        <?php
        $ada = false;
        foreach($mobils as $m):
        $ada = true;
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="car-card">
                <div class="car-top">
                    <?php 
                    // Tentukan path folder upload dan ambil nama file dari DB (bisa field 'foto_url' atau 'foto')
                    $nama_file = $m['foto_url'] ?? $m['foto'] ?? '';
                    $path_gambar = 'uploads/' . $nama_file;
                    
                    // Cek apakah data nama file ada DAN file fisiknya benar-benar ada di folder uploads/
                    if(!empty($nama_file) && file_exists($path_gambar)): 
                    ?>
                        <img src="<?= htmlspecialchars($path_gambar) ?>" 
                             alt="<?= htmlspecialchars($m['nama_mobil']) ?>"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <i class="bi bi-car-front"></i>
                    <?php endif; ?>
                </div>

                <div class="car-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="car-name">
                                <?= htmlspecialchars($m['nama_mobil']) ?>
                            </div>
                            <div class="car-brand">
                                <?= htmlspecialchars($m['merek']) ?>
                            </div>
                        </div>
                        <?php if(($m['status'] ?? '') == 'Tersedia'): ?>
                            <span class="badge-ready">Tersedia</span>
                        <?php else: ?>
                            <span class="badge-rent">Disewa</span>
                        <?php endif; ?>
                    </div>

                    <div class="tag-wrap">
                        <div class="car-tag">
                            <?= htmlspecialchars($m['tahun']) ?>
                        </div>
                        <div class="car-tag">
                            <?= htmlspecialchars($m['warna']) ?>
                        </div>
                        <div class="car-tag">
                            <?= htmlspecialchars($m['plat_nomor']) ?>
                        </div>
                    </div>

                    <div class="price mb-4">
                        Rp <?= number_format((int)$m['harga_per_hari'],0,',','.') ?>
                        <small>/ hari</small>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="detail.php?id=<?= urlencode((string)$m['_id']) ?>" class="btn-detail">
                            Detail
                        </a>
                        <?php if(($m['status'] ?? '') == 'Tersedia'): ?>
                            <a href="rental_form.php?id=<?= urlencode((string)$m['_id']) ?>" class="btn-rent">
                                Sewa
                            </a>
                        <?php else: ?>
                            <button class="btn-detail" disabled>
                                Tidak Tersedia
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if(!$ada): ?>
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-car-front fs-1 d-block mb-3"></i>
                    Mobil tidak ditemukan
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer-text">
        Rental Mobil © <?= date('Y') ?> — Kelompok 4
    </div>

</div>

</body>
</html>