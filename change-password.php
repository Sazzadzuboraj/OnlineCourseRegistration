<?php
include('includes/config.php');
$pageTitle = 'Change Password';
$activePage = 'password';
include('includes/header.php');

$regno = $_SESSION['login'];

if (isset($_POST['change'])) {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    $student = mysqli_fetch_assoc(mysqli_query($bd, "SELECT password FROM students WHERE StudentRegno='$regno'"));

    if (!password_verify($current, $student['password'])) {
        setFlash('danger', 'Current password is incorrect.');
    } elseif (strlen($new) < 6) {
        setFlash('danger', 'New password must be at least 6 characters.');
    } elseif ($new !== $confirm) {
        setFlash('danger', 'New passwords do not match.');
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        mysqli_query($bd, "UPDATE students SET password='$hash' WHERE StudentRegno='$regno'");
        setFlash('success', 'Password changed successfully!');
        header('Location: change-password.php');
        exit;
    }
    header('Location: change-password.php');
    exit;
}
?>

<div class="mb-4 fade-in">
    <h1 class="page-title">Change Password</h1>
    <p class="page-subtitle">Keep your account secure</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card-modern p-4 p-md-5 scale-in">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label-modern">Current Password</label>
                    <div class="input-group-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="current" class="form-control form-control-modern" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-modern">New Password</label>
                    <div class="input-group-icon">
                        <i class="fas fa-key"></i>
                        <input type="password" name="new" class="form-control form-control-modern" required minlength="6">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label-modern">Confirm New Password</label>
                    <div class="input-group-icon">
                        <i class="fas fa-check-double"></i>
                        <input type="password" name="confirm" class="form-control form-control-modern" required>
                    </div>
                </div>
                <button type="submit" name="change" class="btn btn-primary-modern w-100">
                    <i class="fas fa-shield-alt me-2"></i> Update Password
                </button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
