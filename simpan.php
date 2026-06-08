<?php

require_once 'koneksi.php';

use MongoDB\BSON\UTCDateTime;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {

        $data = [

            'nama_mobil'     => $_POST['nama_mobil'],
            'merek'          => $_POST['merek'],
            'tahun'          => $_POST['tahun'],
            'warna'          => $_POST['warna'],
            'plat_nomor'     => strtoupper($_POST['plat_nomor']),
            'harga_per_hari' => (int)$_POST['harga_per_hari'],
            'status'         => $_POST['status'],
            'foto_url'       => $_POST['foto_url'] ?? '',
            'keterangan'     => $_POST['keterangan'] ?? '',
            'created_at'     => new UTCDateTime(),
            'updated_at'     => new UTCDateTime()

        ];

        // Simpan ke MongoDB
        $collection->insertOne($data);

        // Redirect
        header('Location:admindasboard.php?pesan=tambah');

        exit;

    } catch (Exception $e) {

        echo "Error: " . $e->getMessage();

    }

} else {

    header('Location:admindasboard.php');

    exit;

}