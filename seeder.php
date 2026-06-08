<?php
require_once 'koneksi.php';

/**
 * seeder.php — Data mobil + foto_url berdasarkan merek
 * Jalankan sekali: http://localhost/RentalMobil_Kelompok4/seeder.php
 */

$dataMobil = [
    [
        'nama_mobil'     => 'Innova Reborn 2.0 G MT',
        'merek'          => 'Toyota',
        'tahun'          => 2021,
        'warna'          => 'Hitam',
        'plat_nomor'     => 'H 5678 BCD',
        'harga_per_hari' => 550000,
        'status'         => 'Tersedia',
        'keterangan'     => 'AC dingin, kursi 7, bagasi luas',
        'foto_url'       => 'https://i.pinimg.com/1200x/1b/d5/d6/1bd5d6e4d1295dd7677f8a7a355a26e8.jpg',
    ],
    [
        'nama_mobil'     => 'Brio Satya E CVT',
        'merek'          => 'Honda',
        'tahun'          => 2022,
        'warna'          => 'Putih',
        'plat_nomor'     => 'B 9012 EFG',
        'harga_per_hari' => 300000,
        'status'         => 'Disewa',
        'keterangan'     => 'Irit BBM, cocok untuk dalam kota',
        'foto_url'       => 'https://i.pinimg.com/736x/98/32/af/9832af2910dd56a08790f91870c099d0.jpg',
    ],
    [
        'nama_mobil'     => 'Xpander Cross 1.5 AT',
        'merek'          => 'Mitsubishi',
        'tahun'          => 2023,
        'warna'          => 'Silver',
        'plat_nomor'     => 'K 3456 HIJ',
        'harga_per_hari' => 600000,
        'status'         => 'Tersedia',
        'keterangan'     => 'MPV premium, 7 penumpang, fitur lengkap',
        'foto_url'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/2022_Mitsubishi_Xpander_Cross_%28facelift%2C_Indonesia%29%2C_front_8.22.23.jpg/1280px-2022_Mitsubishi_Xpander_Cross_%28facelift%2C_Indonesia%29%2C_front_8.22.23.jpg',
    ],
    [
        'nama_mobil'     => 'Sigra 1.2 R MT',
        'merek'          => 'Daihatsu',
        'tahun'          => 2020,
        'warna'          => 'Merah',
        'plat_nomor'     => 'AD 7890 KLM',
        'harga_per_hari' => 250000,
        'status'         => 'Tersedia',
        'keterangan'     => 'Hemat, cocok untuk keluarga kecil',
        'foto_url'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/76/2019_Daihatsu_Sigra_1.2_R_MT_%28Indonesia%29%2C_front_8.22.23.jpg/1280px-2019_Daihatsu_Sigra_1.2_R_MT_%28Indonesia%29%2C_front_8.22.23.jpg',
    ],
    [
        'nama_mobil'     => 'Ertiga GX MT',
        'merek'          => 'Suzuki',
        'tahun'          => 2021,
        'warna'          => 'Abu-abu',
        'plat_nomor'     => 'B 2345 NOP',
        'harga_per_hari' => 420000,
        'status'         => 'Disewa',
        'keterangan'     => 'Keluarga 7 seat, nyaman untuk perjalanan jauh',
        'foto_url'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/29/2022_Suzuki_Ertiga_GX_%28Indonesia%29%2C_front_8.22.23.jpg/1280px-2022_Suzuki_Ertiga_GX_%28Indonesia%29%2C_front_8.22.23.jpg',
    ],
    [
        'nama_mobil'     => 'HR-V 1.5 E CVT',
        'merek'          => 'Honda',
        'tahun'          => 2022,
        'warna'          => 'Biru',
        'plat_nomor'     => 'D 6789 QRS',
        'harga_per_hari' => 500000,
        'status'         => 'Tersedia',
        'keterangan'     => 'SUV stylish, fitur Honda Sensing',
        'foto_url'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/dc/2022_Honda_HR-V_1.5_SE_%28RS%29_CVT_%28Indonesia%29%2C_front_8.22.23.jpg/1280px-2022_Honda_HR-V_1.5_SE_%28RS%29_CVT_%28Indonesia%29%2C_front_8.22.23.jpg',
    ],
    [
        'nama_mobil'     => 'Rush 1.5 S AT',
        'merek'          => 'Toyota',
        'tahun'          => 2020,
        'warna'          => 'Putih',
        'plat_nomor'     => 'B 1111 TUV',
        'harga_per_hari' => 450000,
        'status'         => 'Tersedia',
        'keterangan'     => 'SUV tangguh, ground clearance tinggi',
        'foto_url'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/34/2018_Toyota_Rush_1.5_S_A_T_%28Indonesia%29.jpg/1280px-2018_Toyota_Rush_1.5_S_A_T_%28Indonesia%29.jpg',
    ],
    [
        'nama_mobil'     => 'Terios R A/T',
        'merek'          => 'Daihatsu',
        'tahun'          => 2019,
        'warna'          => 'Hitam',
        'plat_nomor'     => 'H 2222 WXY',
        'harga_per_hari' => 380000,
        'status'         => 'Disewa',
        'keterangan'     => 'SUV 7 penumpang, cocok medan pegunungan',
        'foto_url'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b0/2018_Daihatsu_Terios_R_AT_%28Indonesia%29%2C_front_8.22.23.jpg/1280px-2018_Daihatsu_Terios_R_AT_%28Indonesia%29%2C_front_8.22.23.jpg',
    ],
    [
        'nama_mobil'     => 'Stargazer Prime CVT',
        'merek'          => 'Hyundai',
        'tahun'          => 2023,
        'warna'          => 'Putih Mutiara',
        'plat_nomor'     => 'B 3333 ZAB',
        'harga_per_hari' => 650000,
        'status'         => 'Tersedia',
        'keterangan'     => 'MPV modern, layar 10 inci, panoramic sunroof',
        'foto_url'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/2022_Hyundai_Stargazer_Prime_CVT_%28Indonesia%29%2C_front_8.22.23.jpg/1280px-2022_Hyundai_Stargazer_Prime_CVT_%28Indonesia%29%2C_front_8.22.23.jpg',
    ],
    [
        'nama_mobil'     => 'Almaz RS Pro',
        'merek'          => 'Wuling',
        'tahun'          => 2022,
        'warna'          => 'Coklat',
        'plat_nomor'     => 'B 4444 CDE',
        'harga_per_hari' => 480000,
        'status'         => 'Tersedia',
        'keterangan'     => 'SUV voice command, sunroof, kursi captain',
        'foto_url'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/Wuling_Almaz_RS_Pro_2022_Indonesia.jpg/1280px-Wuling_Almaz_RS_Pro_2022_Indonesia.jpg',
    ],
];

$berhasil = 0;
$diperbarui = 0;
$gagal    = 0;

foreach ($dataMobil as $data) {
    try {
        $existing = $collection->findOne(['plat_nomor' => $data['plat_nomor']]);

        if ($existing) {
            // Update foto_url jika sudah ada datanya
            $collection->updateOne(
                ['plat_nomor' => $data['plat_nomor']],
                ['$set' => [
                    'foto_url'   => $data['foto_url'],
                    'updated_at' => new MongoDB\BSON\UTCDateTime(),
                ]]
            );
            echo "🔄 Diperbarui foto: <b>{$data['nama_mobil']}</b> [{$data['plat_nomor']}]<br>";
            $diperbarui++;
            continue;
        }

        $collection->insertOne(array_merge($data, [
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime(),
        ]));

        echo "✅ Berhasil ditambahkan: <b>{$data['nama_mobil']}</b><br>";
        $berhasil++;

    } catch (Exception $e) {
        echo "❌ Gagal: {$data['nama_mobil']} — " . $e->getMessage() . "<br>";
        $gagal++;
    }
}

echo "<hr>";
echo "<b>Selesai!</b> ";
echo "Baru: <b style='color:green'>$berhasil</b> | ";
echo "Diperbarui: <b style='color:blue'>$diperbarui</b> | ";
echo "Gagal: <b style='color:red'>$gagal</b><br><br>";
echo "<a href='admindasboard.php' style='background:#0d6efd;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;'>← Kembali ke Dashboard</a>";
?>
