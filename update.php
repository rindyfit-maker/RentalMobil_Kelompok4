<?php

session_start();
require_once 'koneksi.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        // Ambil ID
        $id = new ObjectId($_POST['id']);

        // 1. Ambil data mobil lama untuk mengetahui nama foto sebelumnya
        $mobilLama = $collection->findOne(['_id' => $id]);
        $nama_file_foto = $mobilLama['foto_url'] ?? ''; 

        // 2. Logika Penanganan Upload File Baru
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            
            $file_tmp = $_FILES['foto']['tmp_name'];
            $file_name = $_FILES['foto']['name'];
            
            // Ambil ekstensi file (jpg, png, dll)
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Validasi format yang diperbolehkan
            $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($file_ext, $ekstensi_boleh)) {
                
                // Buat nama unik baru supaya tidak bentrok di folder uploads
                $nama_file_baru = uniqid('mobil_', true) . '.' . $file_ext;
                $folder_tujuan = 'uploads/' . $nama_file_baru;

                // Memindahkan file dari temporary server ke folder uploads/
                if (move_uploaded_file($file_tmp, $folder_tujuan)) {
                    
                    // Hapus foto lama di folder uploads jika ada
                    if (!empty($nama_file_foto) && file_exists('uploads/' . $nama_file_foto)) {
                        unlink('uploads/' . $nama_file_foto);
                    }
                    
                    // Update variabel dengan nama file yang baru berhasil diupload
                    $nama_file_foto = $nama_file_baru;
                }
            }
        }

        // 3. Data update
        $updateData = [
            'nama_mobil'     => $_POST['nama_mobil'],
            'merek'          => $_POST['merek'],
            'tahun'          => $_POST['tahun'],
            'warna'          => $_POST['warna'],
            'plat_nomor'     => strtoupper($_POST['plat_nomor']),
            'harga_per_hari' => (int)$_POST['harga_per_hari'],
            'status'         => $_POST['status'],
            'foto_url'       => $nama_file_foto, // Menggunakan nama file yang sudah diproses di atas
            'keterangan'     => $_POST['keterangan'],
            'updated_at'     => new UTCDateTime()
        ];

        // 4. Update database
        $collection->updateOne(
            [
                '_id' => $id
            ],
            [
                '$set' => $updateData
            ]
        );

        // 5. Redirect kembali ke halaman detail
        header('Location: detail.php?id=' . $_POST['id'] . '&pesan=update');
        exit;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

} else {
    header('Location: admindasboard.php');
    exit;
}