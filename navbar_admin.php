<?php
// navbar_admin.php — include di setiap halaman admin
require_once __DIR__ . '/auth.php';
$user = getSessionUser();
?>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="admindasboard.php">
            <i class="bi bi-car-front-fill me-2"></i>Rental Mobil
            <span class="badge bg-danger ms-2" style="font-size:10px;vertical-align:middle;">ADMIN</span>
        </a>
        <div class="d-flex align-items-center gap-3 ms-auto">
            <span class="d-none d-md-inline text-muted small">
                <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($user['nama']) ?>
            </span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </a>
        </div>
    </div>
</nav>
