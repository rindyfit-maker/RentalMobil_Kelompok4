<?php
// PERBAIKAN 1: Memulai session di baris paling pertama agar $_SESSION dikenali
session_start();

require_once 'koneksi.php';

// Validasi ID
if (empty($_GET['id'])) {
    header('Location: admindasboard.php');
    exit;
}

try {

    $id = new MongoDB\BSON\ObjectId($_GET['id']);

    // PERBAIKAN DI SINI
    $mobil = $collection->findOne([
        '_id' => $id
    ]);

} catch (Exception $e) {

    header('Location: admindasboard.php');
    exit;

}

if (!$mobil) {
    header('Location: admindasboard.php');
    exit;
}

// Format tanggal
function fmtDate($bsonDate) {

    if (!$bsonDate) return '-';

    $ts = $bsonDate->toDateTime();

    $ts->setTimezone(new DateTimeZone('Asia/Jakarta'));

    return $ts->format('d M Y, H:i') . ' WIB';
}

$pesan = $_GET['pesan'] ?? '';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        Detail — <?= htmlspecialchars($mobil['nama_mobil']) ?>
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

        *{
            font-family:'Plus Jakarta Sans',sans-serif;
        }

        body{
background: linear-gradient(135deg, #ffffff 0%, #002d6c 50%, #000015 100%);
        }

        .navbar{
            background:white;
            border-bottom:1px solid #e5e7eb;
            padding:18px 0;
        }

        .navbar-brand{
            font-weight:800;
            font-size:24px;
            color:#111827 !important;
        }

        .card-custom{
            background:white;
            border:none;
            border-radius:28px;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(0,0,0,0.05);
        }

        .card-header-custom{
            background:#111827;
            color:white;
            padding:30px;
        }

        .status-badge{
            padding:10px 18px;
            border-radius:999px;
            font-size:13px;
            font-weight:700;
        }

        .status-tersedia{
            background:#22c55e;
            color:white;
        }

        .status-disewa{
            background:#ef4444;
            color:white;
        }

        .section-box{
            background:#f9fafb;
            border-radius:22px;
            padding:28px;
            height:100%;
        }

        .section-title{
            font-size:20px;
            font-weight:700;
            margin-bottom:25px;
        }

        .info-item{
            margin-bottom:22px;
        }

        .info-label{
            font-size:13px;
            color:#6b7280;
            margin-bottom:6px;
            display:block;
        }

        .info-value{
            font-size:17px;
            font-weight:600;
            color:#111827;
        }

        .price{
            color:#16a34a;
            font-size:28px;
            font-weight:800;
        }

        .btn-modern{
            border:none;
            border-radius:14px;
            padding:12px 24px;
            font-weight:600;
        }

        .btn-edit{
            background:#facc15;
            color:#111827;
        }

        .btn-delete{
            background:#ef4444;
            color:white;
        }

        .btn-back{
            background:#111827;
            color:white;
        }

        .footer-text{
            text-align:center;
            color:#9ca3af;
            margin-top:35px;
            font-size:14px;
        }

    </style>

</head>

<body>

<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a class="navbar-brand" href="admindasboard.php">

            <i class="bi bi-car-front-fill me-2"></i>

            Rental Mobil

        </a>

    </div>

</nav>

<div class="container py-5">

    <nav class="mb-4">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="admindasboard.php">
                    Dashboard
                </a>
            </li>

            <li class="breadcrumb-item active">
                Detail Mobil
            </li>

        </ol>

    </nav>

    <?php if($pesan === 'update'): ?>

        <div class="alert alert-success rounded-4 border-0 shadow-sm">

            Data berhasil diperbarui

        </div>

    <?php endif; ?>

    <div class="card-custom">

        <div class="card-header-custom">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h2 class="fw-bold mb-1">

                        <?= htmlspecialchars($mobil['nama_mobil']) ?>

                    </h2>

                    <div class="opacity-75">

                        <?= htmlspecialchars($mobil['merek']) ?>

                    </div>

                </div>

                <?php if($mobil['status'] === 'Tersedia'): ?>

                    <span class="status-badge status-tersedia">

                        Tersedia

                    </span>

                <?php else: ?>

                    <span class="status-badge status-disewa">

                        Disewa

                    </span>

                <?php endif; ?>

            </div>

        </div>

        <div class="p-4 p-md-5">

            <div class="row g-4 mb-4">
                <div class="col-12">
                    <?php if(!empty($mobil['foto_url'])): ?>
                        <div style="background: linear-gradient(135deg, #ffffff 0%, #002d6c 50%, #000015 100%); border-radius: 22px; overflow: hidden;">
                            <img src="<?= htmlspecialchars($mobil['foto_url']) ?>" 
                                 alt="<?= htmlspecialchars($mobil['nama_mobil']) ?>"
                                 style="width: 100%; max-height: 400px; object-fit: cover; display: block;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="section-box">

                        <div class="section-title">

                            Informasi Mobil

                        </div>

                        <div class="info-item">

                            <span class="info-label">
                                Nama Mobil
                            </span>

                            <div class="info-value">

                                <?= htmlspecialchars($mobil['nama_mobil']) ?>

                            </div>

                        </div>

                        <div class="info-item">

                            <span class="info-label">
                                Merek
                            </span>

                            <div class="info-value">

                                <?= htmlspecialchars($mobil['merek']) ?>

                            </div>

                        </div>

                        <div class="info-item">

                            <span class="info-label">
                                Tahun
                            </span>

                            <div class="info-value">

                                <?= htmlspecialchars($mobil['tahun']) ?>

                            </div>

                        </div>

                        <div class="info-item">

                            <span class="info-label">
                                Warna
                            </span>

                            <div class="info-value">

                                <?= htmlspecialchars($mobil['warna']) ?>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="section-box">

                        <div class="section-title">

                            Detail Rental

                        </div>

                        <div class="info-item">

                            <span class="info-label">
                                Plat Nomor
                            </span>

                            <div class="info-value">

                                <?= htmlspecialchars($mobil['plat_nomor']) ?>

                            </div>

                        </div>

                        <div class="info-item">

                            <span class="info-label">
                                Harga / Hari
                            </span>

                            <div class="price">

                                Rp <?= number_format((int)$mobil['harga_per_hari'],0,',','.') ?>

                            </div>

                        </div>

                        <div class="info-item">

                            <span class="info-label">
                                Keterangan
                            </span>

                            <div class="info-value">

                                <?= nl2br(htmlspecialchars($mobil['keterangan'] ?? '-')) ?>

                            </div>

                        </div>

                        <div class="info-item">

                            <span class="info-label">
                                Dibuat
                            </span>

                            <div class="info-value">

                                <?= fmtDate($mobil['created_at'] ?? null) ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="p-4 border-top d-flex gap-2 flex-wrap">

    <?php if(($_SESSION['role'] ?? '') == 'admin'): ?>

        <a
            href="edit.php?id=<?= (string)$mobil['_id'] ?>"
            class="btn btn-modern btn-edit"
        >
            <i class="bi bi-pencil me-2"></i>
            Edit
        </a>

        <a
            href="delete.php?id=<?= (string)$mobil['_id'] ?>"
            class="btn btn-modern btn-delete"
            onclick="return confirm('Yakin ingin menghapus data?')"
        >
            <i class="bi bi-trash me-2"></i>
            Hapus
        </a>

    <?php endif; ?>

    <a
        href="admindasboard.php"
        class="btn btn-modern btn-back ms-auto"
    >
        <i class="bi bi-arrow-left me-2"></i>
        Kembali
    </a>

</div>

    <div class="footer-text">

        Rental Mobil © <?= date('Y') ?> — Kelompok 4

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>