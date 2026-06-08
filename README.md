# Rental Mobil — Kelompok 4
## Aplikasi CRUD MongoDB + PHP + Bootstrap 5

---

## Struktur File

```
RentalMobil_Kelompok4/
├── vendor/              ← hasil: composer require mongodb/mongodb
├── css/
│   └── style.css        ← custom styling
├── koneksi.php          ← koneksi ke MongoDB
├── index.php            ← Dashboard (daftar mobil + search)
├── tambah.php           ← form tambah mobil baru
├── simpan.php           ← proses insert ke MongoDB
├── detail.php           ← detail mobil + form edit (Update)
├── update.php           ← proses update ke MongoDB
├── delete.php           ← proses hapus dari MongoDB
└── README.md            ← panduan ini
```

---

## Langkah Instalasi

### 1. Install & jalankan XAMPP
- Download di https://www.apachefriends.org
- Start **Apache** di XAMPP Control Panel

### 2. Install & jalankan MongoDB
- Download Community Server di https://www.mongodb.com/try/download/community
- Jalankan MongoDB sebagai service (otomatis saat install)
- Cek dengan: buka cmd → ketik `mongosh`

### 3. Install MongoDB PHP Driver
1. Cek versi PHP kamu: buka XAMPP Shell → `php -v`
2. Download `.dll` sesuai versi PHP:
   https://github.com/mongodb/mongo-php-driver/releases
3. Copy `php_mongodb.dll` ke `C:\xampp\php\ext\`
4. Buka `C:\xampp\php\php.ini`, tambahkan:
   ```
   extension=mongodb
   ```
5. Restart Apache di XAMPP

### 4. Install package via Composer
```bash
cd C:\xampp\htdocs\RentalMobil_Kelompok4
composer require mongodb/mongodb
```

### 5. Jalankan aplikasi
Buka browser:
```
http://localhost/RentalMobil_Kelompok4/
```

---

## Konfigurasi Database

File: `koneksi.php`
- Host   : `localhost`
- Port   : `27017`
- Database   : `RentalMobil`
- Collection : `RentalMobil_Kelompok4`

---

## Fitur Aplikasi

| Fitur | File | Keterangan |
|---|---|---|
| Beranda / Dashboard | index.php | Tampil semua data mobil |
| Search / Cari | index.php | Filter nama, merek, plat |
| Tambah Data | tambah.php | Form input mobil baru |
| Simpan Data | simpan.php | Insert ke MongoDB |
| Detail & Edit | detail.php | Lihat detail + form edit |
| Update Data | update.php | Perbarui data di MongoDB |
| Hapus Data | delete.php | Hapus dokumen dari MongoDB |

---

Dibuat untuk tugas mata kuliah — Kelompok 4
