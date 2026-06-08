<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'koneksi.php';

if (!isset($_GET['id'])) {
    die("ID mobil tidak ditemukan");
}

$id = $_GET['id'];

try {
    $mobil = $collection->findOne([
        '_id' => new MongoDB\BSON\ObjectId($id)
    ]);
} catch (Exception $e) {
    die("Format ID Mobil tidak valid: " . $e->getMessage());
}

if (!$mobil) {
    die("Mobil tidak ditemukan");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $tgl_mulai = $_POST['tgl_mulai'];
        $jumlah_hari = (int)$_POST['jumlah_hari'];

        $harga = (int)$mobil['harga_per_hari'];
        $total = $harga * $jumlah_hari;

        $tgl_selesai = date(
            'Y-m-d',
            strtotime($tgl_mulai . " +$jumlah_hari days")
        );

        // Deteksi otomatis nama field foto di koleksi mobil Anda (bisa foto, gambar, atau foto_url)
        $foto_mobil = $mobil['foto'] ?? $mobil['gambar'] ?? $mobil['foto_url'] ?? '';

        // Simpan rental ke collection rentals
        $colRental->insertOne([
            'user_id' => $_SESSION['user_id'] ?? null,
            'nama_user' => $_SESSION['username'],

            'mobil_id' => (string)$mobil['_id'],
            'nama_mobil' => $mobil['nama_mobil'],
            'merek' => $mobil['merek'],
            'plat_nomor' => $mobil['plat_nomor'],
            'foto_mobil' => $foto_mobil, // Menyimpan nama file foto agar bisa dipanggil di riwayat

            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
            'jumlah_hari' => $jumlah_hari,

            'harga_per_hari' => $harga,
            'total_harga' => $total,

            'status_rental' => 'Aktif',
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);

        // Update status mobil menjadi Disewa
        $collection->updateOne(
            [
                '_id' => new MongoDB\BSON\ObjectId($id)
            ],
            [
                '$set' => [
                    'status' => 'Disewa'
                ]
            ]
        );

        // Langsung lempar ke halaman rental saya dengan pesan sukses
        header('Location: my_rental.php?pesan=rental_berhasil');
        exit;

    } catch (Exception $e) {
        die("Gagal memproses rental: " . $e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background: linear-gradient(135deg, #ffffff 0%, #002d6c 50%, #000015 100%);">

<div class="container py-5">

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <h2 class="fw-bold mb-4">Rental Mobil</h2>

            <div class="mb-4">
                <h4><?= htmlspecialchars($mobil['nama_mobil']) ?></h4>
                <div class="text-muted"><?= htmlspecialchars($mobil['merek']) ?></div>
                <div class="mt-2">
                    Rp <?= number_format((int)$mobil['harga_per_hari'],0,',','.') ?> / hari
                </div>
            </div>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Jumlah Hari</label>
                    <input type="number" name="jumlah_hari" class="form-control" min="1" required>
                </div>

                <button type="submit" class="btn btn-dark">
                    Sewa Sekarang
                </button>

                <a href="catalog.php" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>