<?php
requireAdmin();
$aname = $_SESSION['aname'] ?? 'Admin';
$current = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> | <?= APP_NAME ?></title>
    <script>(function(){var t=localStorage.getItem('coursehub-theme')||'light';document.documentElement.setAttribute('data-theme',t);})();</script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="bg-animation no-print"><div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div></div>
<canvas id="particle-canvas" class="no-print"></canvas>
<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-graduation-cap"></i>
        <span><?= APP_NAME ?></span>
    </div>
    <ul class="sidebar-menu">
        <li><a href="dashboard.php" class="<?= $current==='dashboard'?'active':'' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="students.php" class="<?= $current==='students'?'active':'' ?>"><i class="fas fa-user-graduate"></i> Students</a></li>
        <li><a href="courses.php" class="<?= in_array($current,['courses','edit-course'])?'active':'' ?>"><i class="fas fa-book"></i> Courses</a></li>
        <li><a href="departments.php" class="<?= $current==='departments'?'active':'' ?>"><i class="fas fa-building"></i> Departments</a></li>
        <li><a href="sessions.php" class="<?= $current==='sessions'?'active':'' ?>"><i class="fas fa-calendar"></i> Sessions</a></li>
        <li><a href="semesters.php" class="<?= $current==='semesters'?'active':'' ?>"><i class="fas fa-calendar-alt"></i> Semesters</a></li>
        <li><a href="levels.php" class="<?= $current==='levels'?'active':'' ?>"><i class="fas fa-layer-group"></i> Levels</a></li>
        <li><a href="enrollments.php" class="<?= $current==='enrollments'?'active':'' ?>"><i class="fas fa-clipboard-list"></i> Enrollments</a></li>
        <li><a href="user-logs.php" class="<?= $current==='user-logs'?'active':'' ?>"><i class="fas fa-history"></i> User Logs</a></li>
        <li><a href="change-password.php" class="<?= $current==='change-password'?'active':'' ?>"><i class="fas fa-key"></i> Change Password</a></li>
        <li class="mt-3"><a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <button class="btn btn-sm d-lg-none me-2" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <h1 class="page-title d-inline"><?= $pageTitle ?? 'Dashboard' ?></h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="theme-toggle" title="Toggle dark mode"><i class="fas fa-moon"></i></button>
            <span class="text-muted small d-none d-md-inline">Welcome,</span>
            <span class="fw-semibold"><?= htmlspecialchars($aname) ?></span>
            <div class="stat-icon" style="width:36px;height:36px;font-size:0.9rem;background:linear-gradient(135deg,#4f46e5,#6366f1);">
                <i class="fas fa-user-shield"></i>
            </div>
        </div>
    </div>
<?php
$flash = getFlash();
if ($flash):
?>
    <div class="alert-modern alert-<?= $flash['type'] ?>-modern mb-4" data-auto-hide>
        <i class="fas fa-<?= $flash['type']==='success'?'check-circle':'exclamation-circle' ?>"></i>
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>
