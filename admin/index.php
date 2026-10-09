<?php
include('includes/config.php');

if (!empty($_SESSION['alogin'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    $username = clean($_POST['username']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($bd, "SELECT * FROM admin WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['alogin'] = $row['username'];
            $_SESSION['aid'] = $row['id'];
            $_SESSION['aname'] = $row['fullName'];
            header('Location: dashboard.php');
            exit;
        }
    }
    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?= APP_NAME ?></title>
    <script>(function(){var t=localStorage.getItem('coursehub-theme')||'light';document.documentElement.setAttribute('data-theme',t);})();</script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="bg-animation"><div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div></div>
<canvas id="particle-canvas"></canvas>
<div class="position-fixed top-0 end-0 p-3" style="z-index:1000;">
  <button type="button" class="theme-toggle" title="Toggle dark mode"><i class="fas fa-moon"></i></button>
</div>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header" style="background:linear-gradient(135deg,#0f172a,#1e293b);">
            <div style="font-size:2.5rem;margin-bottom:0.5rem;"><i class="fas fa-shield-alt"></i></div>
            <h2>Admin Panel</h2>
            <p><?= APP_NAME ?> Management Console</p>
        </div>
        <div class="auth-body">
            <?php if ($error): ?>
                <div class="alert-modern alert-danger-modern mb-3">
                    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label-modern">Username</label>
                    <div class="input-group-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" class="form-control form-control-modern" placeholder="admin" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label-modern">Password</label>
                    <div class="input-group-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" class="form-control form-control-modern" required>
                    </div>
                </div>
                <button type="submit" name="login" class="btn btn-primary-modern w-100 py-2">
                    <i class="fas fa-sign-in-alt me-2"></i> Sign In as Admin
                </button>
            </form>
            <div class="text-center mt-3">
                <small class="text-muted">Student? <a href="../" class="fw-semibold" style="color:var(--primary);">Login here</a></small>
            </div>
            <div class="mt-4 p-3 rounded-3" style="background:rgba(15,23,42,0.06);font-size:0.85rem;">
                <strong class="d-block mb-1"><i class="fas fa-info-circle me-1"></i> Demo Credentials</strong>
                Username: <code>admin</code><br>
                Password: <code>Admin@123</code>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/app.js"></script>
</body>
</html>
