<?php
include('includes/config.php');

// Redirect if already logged in
if (!empty($_SESSION['login'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    $regno = clean($_POST['regno']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($bd, "SELECT * FROM students WHERE StudentRegno = ? AND status = 1");
    mysqli_stmt_bind_param($stmt, "s", $regno);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = $row['StudentRegno'];
            $_SESSION['sname'] = $row['studentName'];
            $_SESSION['photo'] = $row['studentPhoto'] ?: 'noimage.png';

            // Log login
            $ip = $_SERVER['REMOTE_ADDR'];
            mysqli_query($bd, "INSERT INTO userlog (studentRegno, userip, status) VALUES ('$regno', '$ip', 1)");

            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid registration number or password.';
        }
    } else {
        $error = 'Invalid registration number or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | <?= APP_NAME ?></title>
    <script>(function(){var t=localStorage.getItem('coursehub-theme')||'light';document.documentElement.setAttribute('data-theme',t);})();</script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="bg-animation"><div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div></div>
<canvas id="particle-canvas"></canvas>
<div class="position-fixed top-0 end-0 p-3" style="z-index:1000;">
  <button type="button" class="theme-toggle" title="Toggle dark mode"><i class="fas fa-moon"></i></button>
</div>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div style="font-size:2.5rem;margin-bottom:0.5rem;"><i class="fas fa-graduation-cap"></i></div>
            <h2><?= APP_NAME ?></h2>
            <p><?= APP_TAGLINE ?></p>
        </div>
        <div class="auth-body">
            <?php if ($error): ?>
                <div class="alert-modern alert-danger-modern mb-3">
                    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label-modern">Registration Number</label>
                    <div class="input-group-icon">
                        <i class="fas fa-id-card"></i>
                        <input type="text" name="regno" class="form-control form-control-modern" placeholder="e.g. 2025-CSE-001" required value="<?= isset($_POST['regno']) ? htmlspecialchars($_POST['regno']) : '' ?>">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label-modern">Password</label>
                    <div class="input-group-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" class="form-control form-control-modern" placeholder="Enter your password" required>
                        <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-2 p-0" data-toggle-password="#password" style="z-index:6;color:var(--gray);">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" name="login" class="btn btn-primary-modern w-100 py-2 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Sign In
                </button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">Admin? <a href="admin/" class="fw-semibold" style="color:var(--primary);">Login here</a></small>
            </div>

            <div class="mt-4 p-3 rounded-3" style="background:rgba(79,70,229,0.06);font-size:0.85rem;">
                <strong class="d-block mb-1"><i class="fas fa-info-circle me-1"></i> Demo Credentials</strong>
                Reg No: <code>2025-CSE-001</code><br>
                Password: <code>Student@123</code>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
