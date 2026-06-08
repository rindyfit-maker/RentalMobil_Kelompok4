<?php
// Tidak perlu koneksi di halaman form ini, hanya tampilkan form
$errors = [];

// Jika ada error dari redirect
if (isset($_GET['error'])) {
    $errors[] = 'Semua field wajib diisi dengan benar.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Mobil — Rental Mobil Kelompok 4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #ffffff 0%, #002d6c 50%, #000015 100%);">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="admindasboard.php">
            <i class="bi bi-car-front-fill me-2"></i>Rental Mobil
        </a>
        <span class="navbar-text text-white-50 small">Kelompok 4</span>
    </div>
</nav>

<div class="container mt-4 fade-in" style="max-width:680px">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="admindasboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Mobil</li>
        </ol>
    </nav>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $errors[0] ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-success text-white">
            <i class="bi bi-plus-circle me-2"></i>Form Tambah Mobil Baru
        </div>
        <div class="card-body p-4">
            <form action="simpan.php" method="POST" novalidate>

                <div class="row g-3">

                    <!-- Nama Mobil -->
                    <div class="col-12">
                        <label class="form-label">Nama Mobil <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mobil" class="form-control"
                               placeholder="Contoh: Avanza 1.3 G MT"
                               required maxlength="100">
                    </div>

                    <!-- Merek & Tahun -->
                    <div class="col-sm-7">
                        <label class="form-label">Merek <span class="text-danger">*</span></label>
                        <select name="merek" class="form-select" required>
                            <option value="">-- Pilih Merek --</option>
                            <option>Toyota</option>
                            <option>Honda</option>
                            <option>Mitsubishi</option>
                            <option>Daihatsu</option>
                            <option>Suzuki</option>
                            <option>Nissan</option>
                            <option>Hyundai</option>
                            <option>Wuling</option>
                            <option>Ford</option>
                            <option>BMW</option>
                            <option>Mercedes-Benz</option>
                            <option>Lainnya</option>
                        </select>
                    </div>

                    <div class="col-sm-5">
                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" class="form-control"
                               placeholder="Contoh: 2022"
                               min="1990" max="<?= date('Y') ?>" required>
                    </div>

                    <!-- Warna & Plat -->
                    <div class="col-sm-5">
                        <label class="form-label">Warna <span class="text-danger">*</span></label>
                        <input type="text" name="warna" class="form-control"
                               placeholder="Contoh: Putih" required maxlength="30">
                    </div>

                    <div class="col-sm-7">
                        <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                        <input type="text" name="plat_nomor" class="form-control"
                               placeholder="Contoh: B 1234 ABC"
                               required maxlength="12"
                               style="text-transform:uppercase">
                    </div>

                    <!-- Harga per Hari -->
                    <div class="col-12">
                        <label class="form-label">Harga Sewa per Hari (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_per_hari" class="form-control"
                                   placeholder="Contoh: 350000"
                                   min="50000" required>
                        </div>
                        <div class="form-text">Masukkan harga dalam satuan rupiah (tanpa titik/koma).</div>
                    </div>

                    <!-- Status -->
                    <div class="col-12">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status"
                                       id="st-tersedia" value="Tersedia" checked>
                                <label class="form-check-label" for="st-tersedia">
                                    <span class="badge-tersedia"><i class="bi bi-check-circle me-1"></i>Tersedia</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status"
                                       id="st-disewa" value="Disewa">
                                <label class="form-check-label" for="st-disewa">
                                    <span class="badge-disewa"><i class="bi bi-clock me-1"></i>Disewa</span>
                                </label>
                            </div>
                        </div>
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

                    <!-- Keterangan (opsional) -->
                    <div class="col-12">
                        <label class="form-label">Keterangan <span class="text-muted small">(opsional)</span></label>
                        <textarea name="keterangan" class="form-control" rows="2"
                                  placeholder="Catatan tambahan tentang mobil ini..."></textarea>
                    </div>

                </div><!-- /row -->

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-floppy me-2"></i>Simpan Data
                    </button>
                    <a href="admindasboard.php" class="btn btn-secondary px-4">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

    <p class="footer-text">Rental Mobil &copy; <?= date('Y') ?> — Kelompok 4</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto uppercase plat nomor
document.querySelector('[name="plat_nomor"]').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
</body>
</html>
