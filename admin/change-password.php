<?php
include('includes/config.php');
$pageTitle = 'Change Password';
include('includes/header.php');

if (isset($_POST['change'])) {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];
    $admin = mysqli_fetch_assoc(mysqli_query($bd, "SELECT password FROM admin WHERE id=".$_SESSION['aid']));
    if (!password_verify($current, $admin['password'])) {
        setFlash('danger', 'Current password is incorrect.');
    } elseif (strlen($new) < 6) {
        setFlash('danger', 'Password must be at least 6 characters.');
    } elseif ($new !== $confirm) {
        setFlash('danger', 'Passwords do not match.');
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        mysqli_query($bd, "UPDATE admin SET password='$hash' WHERE id=".$_SESSION['aid']);
        setFlash('success', 'Password updated successfully.');
    }
    header('Location: change-password.php');
    exit;
}
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card-modern p-4 scale-in">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label-modern">Current Password</label>
                    <input type="password" name="current" class="form-control form-control-modern" required>
                </div>
                <div class="mb-3">
                    <label class="form-label-modern">New Password</label>
                    <input type="password" name="new" class="form-control form-control-modern" required minlength="6">
                </div>
                <div class="mb-4">
                    <label class="form-label-modern">Confirm Password</label>
                    <input type="password" name="confirm" class="form-control form-control-modern" required>
                </div>
                <button type="submit" name="change" class="btn btn-primary-modern w-100">Update Password</button>
            </form>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
