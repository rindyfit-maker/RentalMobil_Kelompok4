<?php
session_start();
require 'koneksi.php';

// Cek apakah sudah login
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') header("Location: admindasboard.php");
    else header("Location: catalog.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputUsername = $_POST['username']; 
    $inputPassword = $_POST['password'];

    // Cari di collection 'users' berdasarkan field 'nama'
    $user = $colUser->findOne(['nama' => $inputUsername]);

    // Verifikasi password (teks biasa sesuai database kamu)
    if ($user && $inputPassword == $user['password']) {
        $_SESSION['user_id'] = (string)$user['_id'];
        $_SESSION['username'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = $user['nama'];

        if ($user['role'] == 'admin') {
            header("Location: admindasboard.php");
        } else {
            header("Location: catalog.php");
        }
        exit;
    } else {
        $error = 'Username atau Password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Rental Mobil</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>

        *{
            font-family:'Plus Jakarta Sans',sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #ffffff 0%, #002d6c 50%, #000015 100%);
            display: flex; align-items: center; justify-content: center; padding: 30px 0;
        }

        .login-card{
            width:100%;
            max-width:420px;
            background:white;
            border-radius:30px;
            padding:40px;
            box-shadow:0 10px 40px rgba(0,0,0,0.08);
        }

        .logo{
            width:80px;
            height:80px;
            background:#111827;
            border-radius:24px;
            display:flex;
            align-items:center;
            justify-content:center;
            margin:auto;
            margin-bottom:25px;
        }

        .logo i{
            color:white;
            font-size:36px;
        }

        h2{
            font-weight:800;
            color:#111827;
        }

        .subtitle{
            color:#6b7280;
            margin-bottom:30px;
        }

        .form-control{
            height:55px;
            border-radius:16px;
            border:1px solid #e5e7eb;
            padding-left:18px;
        }

        .form-control:focus{
            box-shadow:none;
            border-color:#111827;
        }

        .btn-login{
            height:55px;
            border:none;
            border-radius:16px;
            background:#111827;
            color:white;
            font-weight:700;
        }

        .demo-box{
            background:#f9fafb;
            border-radius:18px;
            padding:18px;
            margin-top:25px;
            font-size:14px;
        }

    </style>

</head>

<body>

<div class="login-card">

    <div class="logo">

        <i class="bi bi-car-front-fill"></i>

    </div>

    <div class="text-center mb-4">

        <h2>Rental Mobil</h2>

        <div class="subtitle">

            Login Sistem Rental Premium

        </div>

    </div>

    <?php if($error): ?>

        <div class="alert alert-danger rounded-4 border-0">

            <?= $error ?>

        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">

            <label class="form-label fw-semibold">

                Username

            </label>

            <input
                type="text"
                name="username"
                class="form-control"
                required
            >

        </div>

        <div class="mb-4">

            <label class="form-label fw-semibold">

                Password

            </label>

            <input
                type="password"
                name="password"
                class="form-control"
                required
            >

        </div>

        <button class="btn btn-login w-100">

            Login

        </button>
           <p class="text-center text-secondary small mb-0">
                            Belum punya akun? <a href="register.php">Daftar Sekarang</a>
             </p>
    </form>

    <div class="demo-box">


    </div>

</div>

</body>
</html>