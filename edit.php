<?php
// Memulai session di baris paling pertama agar $_SESSION dikenali di seluruh halaman admin
session_start();

require_once 'koneksi.php';

use MongoDB\BSON\ObjectId;

if(empty($_GET['id'])){
    header('Location: admindasboard.php');
    exit;
}

try{
    $id = new ObjectId($_GET['id']);

    $mobil = $collection->findOne([
        '_id' => $id
    ]);
}catch(Exception $e){
    die($e->getMessage());
}

if(!$mobil){
    header('Location: admindasboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mobil — <?= htmlspecialchars($mobil['nama_mobil']) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *{
            font-family:'Plus Jakarta Sans',sans-serif;
        }

        body{
            background:#f5f7fb;
        }

        .card-edit{
            border:none;
            border-radius:28px;
            box-shadow:0 10px 30px rgba(0,0,0,0.05);
        }

        .form-control,
        .form-select{
            border-radius:14px;
            padding:14px;
        }

        /* Styling khusus untuk input file agar serasi */
        .form-control[type="file"] {
            padding: 11px 14px;
        }

        .btn-save{
            background:#111827;
            color:white;
            border:none;
            border-radius:14px;
            padding:12px 24px;
            font-weight:600;
        }
        
        .img-preview-container {
            background: #f9fafb;
            border: 1px dashed #e5e7eb;
            border-radius: 14px;
            padding: 15px;
        }
    </style>
</head>

<body>

<div class="container py-5" style="max-width:850px">
    <div class="card card-edit">
        <div class="card-body p-5">

            <h2 class="fw-bold mb-4">
                <i class="bi bi-pencil-square me-2"></i> Edit Mobil
            </h2>

            <form action="update.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?= (string)$mobil['_id'] ?>">

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Nama Mobil</label>
                        <input type="text" name="nama_mobil" class="form-control" required value="<?= htmlspecialchars($mobil['nama_mobil']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">Merek</label>
                        <input type="text" name="merek" class="form-control" required value="<?= htmlspecialchars($mobil['merek']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium">Tahun</label>
                        <input type="number" name="tahun" class="form-control" required value="<?= htmlspecialchars($mobil['tahun']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium">Warna</label>
                        <input type="text" name="warna" class="form-control" required value="<?= htmlspecialchars($mobil['warna']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium">Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control" required value="<?= htmlspecialchars($mobil['plat_nomor']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">Harga per Hari</label>
                        <input type="number" name="harga_per_hari" class="form-control" required value="<?= (int)$mobil['harga_per_hari'] ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Tersedia" <?= $mobil['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                            <option value="Disewa" <?= $mobil['status'] == 'Disewa' ? 'selected' : '' ?>>Disewa</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium">Foto Mobil</label>
                        
                        <?php 
                        $nama_file = $mobil['foto_url'] ?? $mobil['foto'] ?? '';
                        $path_gambar = 'uploads/' . $nama_file;
                        if(!empty($nama_file) && file_exists($path_gambar)): 
                        ?>
                            <div class="mb-3 img-preview-container d-inline-block">
                                <p class="small text-muted mb-2"><i class="bi bi-image me-1"></i> Foto Saat Ini:</p>
                                <img src="<?= htmlspecialchars($path_gambar) ?>" alt="Foto Mobil Saat Ini" class="img-fluid rounded-3" style="max-height: 140px; object-fit: cover; display: block;">
                            </div>
                        <?php endif; ?>

                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text text-muted">Pilih file baru jika ingin mengganti foto. Format yang didukung: JPG, JPEG, PNG.</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="4"><?= htmlspecialchars($mobil['keterangan'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="mt-5 d-flex gap-3">
                    <button type="submit" class="btn btn-save shadow-sm">
                        <i class="bi bi-check-lg me-2"></i> Simpan Perubahan
                    </button>
                    <a href="detail.php?id=<?= (string)$mobil['_id'] ?>" class="btn btn-light border rounded-4 px-4 py-2 text-secondary font-weight-medium">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>