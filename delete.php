<?php

require_once 'koneksi.php';

use MongoDB\BSON\ObjectId;

if (empty($_GET['id'])) {
    header('Location: admindasboard.php');
    exit;
}

try {

    $id = new ObjectId($_GET['id']);

    // HAPUS DATA
    $collection->deleteOne([
        '_id' => $id
    ]);

    // redirect
    header('Location: admindasboard.php?pesan=hapus');
    exit;

} catch (Exception $e) {

    echo "Error: " . $e->getMessage();

}