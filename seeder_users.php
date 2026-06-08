<?php
/**
 * seeder_users.php
 * Jalankan untuk membuat akun admin & user demo.
 * Akses: http://localhost/RentalMobil_Kelompok4/seeder_users.php
 */

require_once 'koneksi.php';

$accounts = [
    // ── ADMIN ──────────────────────────────────────────────
    [
        'nama'     => 'Administrator',
        'email'    => 'admin@rental.com',
        'password' => 'admin123',
        'telepon'  => '081234567890',
        'role'     => 'admin',
    ],
    [
        'nama'     => 'Manager Rental',
        'email'    => 'manager@rental.com',
        'password' => 'manager123',
        'telepon'  => '081298765432',
        'role'     => 'admin',
    ],
    [
        'nama'     => 'Supervisor Operasional',
        'email'    => 'supervisor@rental.com',
        'password' => 'super123',
        'telepon'  => '085612345678',
        'role'     => 'admin',
    ],

    // ── USER / PELANGGAN ───────────────────────────────────
    [
        'nama'     => 'User Demo',
        'email'    => 'user@rental.com',
        'password' => 'user123',
        'telepon'  => '089876543210',
        'role'     => 'user',
    ],
    [
        'nama'     => 'Budi Santoso',
        'email'    => 'budi@gmail.com',
        'password' => 'budi1234',
        'telepon'  => '082211223344',
        'role'     => 'user',
    ],
    [
        'nama'     => 'Siti Rahayu',
        'email'    => 'siti@gmail.com',
        'password' => 'siti1234',
        'telepon'  => '087755443322',
        'role'     => 'user',
    ],
    [
        'nama'     => 'Agus Prasetyo',
        'email'    => 'agus@gmail.com',
        'password' => 'agus1234',
        'telepon'  => '081399887766',
        'role'     => 'user',
    ],
    [
        'nama'     => 'Dewi Lestari',
        'email'    => 'dewi@gmail.com',
        'password' => 'dewi1234',
        'telepon'  => '085600112233',
        'role'     => 'user',
    ],
];

$berhasil = 0;
$dilewati = 0;

foreach ($accounts as $acc) {
    $existing = $colUser->findOne(['email' => $acc['email']]);
    if ($existing) {
        echo "⚠️ Dilewati (sudah ada): <b>{$acc['email']}</b><br>";
        $dilewati++;
        continue;
    }

    $colUser->insertOne([
        'nama'       => $acc['nama'],
        'email'      => $acc['email'],
        'password'   => password_hash($acc['password'], PASSWORD_DEFAULT),
        'telepon'    => $acc['telepon'],
        'role'       => $acc['role'],
        'created_at' => new MongoDB\BSON\UTCDateTime(),
    ]);

    $badge = $acc['role'] === 'admin'
        ? "<span style='color:red'>[ADMIN]</span>"
        : "<span style='color:#0d6efd'>[USER]</span>";
    echo "✅ Berhasil: <b>{$acc['nama']}</b> {$badge} — {$acc['email']}<br>";
    $berhasil++;
}

echo "<hr>";
echo "<b>Selesai!</b> Berhasil: <b style='color:green'>$berhasil</b>, Dilewati: <b style='color:orange'>$dilewati</b><br><br>";

echo "<table border='1' cellpadding='10' style='border-collapse:collapse;font-family:monospace;font-size:13px'>";
echo "<tr style='background:#f0f0f0'><th>Role</th><th>Nama</th><th>Email</th><th>Password</th><th>Telepon</th></tr>";
foreach ($accounts as $a) {
    $bg = $a['role'] === 'admin' ? '#fff3f3' : '#f3f8ff';
    echo "<tr style='background:{$bg}'>";
    echo "<td><b>{$a['role']}</b></td>";
    echo "<td>{$a['nama']}</td>";
    echo "<td>{$a['email']}</td>";
    echo "<td>{$a['password']}</td>";
    echo "<td>{$a['telepon']}</td>";
    echo "</tr>";
}
echo "</table><br>";
echo "<a href='login.php' style='background:#0d6efd;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;'>→ Ke Halaman Login</a>";
?>
