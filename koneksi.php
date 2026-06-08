<?php

require 'vendor/autoload.php';

try {

    $client = new MongoDB\Client("mongodb+srv://rindyfit_db_user:rindi1@cluster0.x0uijgs.mongodb.net/");

    $db = $client->RentalMobil_Kelompok4;

    // Collection mobil
    $collection = $db->mobil;

    // Collection users
    $colUsers = $db->users;
    $colUser = $colUsers; // backward-compatible alias for existing pages

    // Collection rental
    $colRental = $db->rentals;

} catch (Exception $e) {

    die("Koneksi gagal: " . $e->getMessage());

}

?>