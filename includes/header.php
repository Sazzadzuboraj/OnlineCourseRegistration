<?php
if (empty($_SESSION['login'])) {
    header('Location: index.php');
    exit;
}
$photo = $_SESSION['photo'] ?? 'noimage.png';
$sname = $_SESSION['sname'] ?? 'Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> | <?= APP_NAME ?></title>
    <script>
      (function(){var t=localStorage.getItem('coursehub-theme')||'light';document.documentElement.setAttribute('data-theme',t);})();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<!-- Live animated background -->
<div class="bg-animation no-print">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>
<canvas id="particle-canvas" class="no-print"></canvas>

<nav class="navbar navbar-expand-lg navbar-modern no-print">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            <i class="fas fa-graduation-cap"></i> <?= APP_NAME ?>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <i class="fas fa-bars" style="color:var(--text)"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link nav-link-modern <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>" href="dashboard.php">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-modern <?= ($activePage ?? '') === 'courses' ? 'active' : '' ?>" href="courses.php">
                        <i class="fas fa-book-open me-1"></i> Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-modern <?= ($activePage ?? '') === 'enroll' ? 'active' : '' ?>" href="enroll.php">
                        <i class="fas fa-plus-circle me-1"></i> Enroll
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-modern <?= ($activePage ?? '') === 'history' ? 'active' : '' ?>" href="enroll-history.php">
                        <i class="fas fa-history me-1"></i> My Enrollments
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="theme-toggle" title="Toggle dark mode" aria-label="Toggle theme">
                    <i class="fas fa-moon"></i>
                </button>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" style="color:var(--text)">
                        <img src="studentphoto/<?= htmlspecialchars($photo) ?>" alt="Avatar" class="user-avatar" onerror="this.src='studentphoto/noimage.png'">
                        <span class="fw-semibold d-none d-md-inline"><?= htmlspecialchars($sname) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px;min-width:200px;">
                        <li><a class="dropdown-item py-2" href="my-profile.php"><i class="fas fa-user me-2 text-primary"></i> My Profile</a></li>
                        <li><a class="dropdown-item py-2" href="change-password.php"><i class="fas fa-key me-2 text-primary"></i> Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<main class="container py-4">
<?php
$flash = getFlash();
if ($flash):
?>
    <div class="alert-modern alert-<?= $flash['type'] ?>-modern mb-4 no-print" data-auto-hide>
        <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>
