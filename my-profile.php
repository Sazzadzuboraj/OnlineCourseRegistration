<?php
include('includes/config.php');
$pageTitle = 'My Profile';
$activePage = 'profile';
include('includes/header.php');

$regno = $_SESSION['login'];
$student = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM students WHERE StudentRegno='$regno'"));

$sessions = mysqli_query($bd, "SELECT * FROM session");
$departments = mysqli_query($bd, "SELECT * FROM department");
$levels = mysqli_query($bd, "SELECT * FROM level");
$semesters = mysqli_query($bd, "SELECT * FROM semester");

if (isset($_POST['update'])) {
    $name = clean($_POST['studentName']);
    $email = clean($_POST['email']);
    $phone = clean($_POST['phone']);
    $cgpa = (float)$_POST['cgpa'];
    $session = (int)$_POST['session'];
    $department = (int)$_POST['department'];
    $level = (int)$_POST['level'];
    $semester = (int)$_POST['semester'];

    $photo = $student['studentPhoto'];
    if (!empty($_FILES['photo']['name'])) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (in_array($ext, $allowed) && $_FILES['photo']['size'] < 2*1024*1024) {
            $photo = $regno . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], 'studentphoto/' . $photo);
        }
    }

    $sql = "UPDATE students SET studentName='$name', email='$email', phone='$phone', cgpa=$cgpa, 
            session=$session, department=$department, level=$level, semester=$semester, studentPhoto='$photo' 
            WHERE StudentRegno='$regno'";
    if (mysqli_query($bd, $sql)) {
        $_SESSION['sname'] = $name;
        $_SESSION['photo'] = $photo;
        setFlash('success', 'Profile updated successfully!');
        header('Location: my-profile.php');
        exit;
    } else {
        setFlash('danger', 'Update failed. Please try again.');
    }
}
?>

<div class="mb-4 fade-in">
    <h1 class="page-title">My Profile</h1>
    <p class="page-subtitle">Manage your personal information</p>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-modern overflow-hidden text-center fade-in">
            <div class="profile-cover"></div>
            <div style="padding-top:70px;padding-bottom:1.5rem;">
                <img src="studentphoto/<?= htmlspecialchars($student['studentPhoto'] ?: 'noimage.png') ?>" 
                     class="profile-avatar-lg" onerror="this.src='studentphoto/noimage.png'" alt="Profile">
                <h5 class="mt-2 fw-bold"><?= htmlspecialchars($student['studentName']) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($student['StudentRegno']) ?></p>
                <p class="small"><i class="fas fa-key me-1"></i> Pincode: <strong><?= $student['pincode'] ?></strong></p>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card-modern p-4 scale-in">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-modern">Full Name</label>
                        <input type="text" name="studentName" class="form-control form-control-modern" value="<?= htmlspecialchars($student['studentName']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modern">Email</label>
                        <input type="email" name="email" class="form-control form-control-modern" value="<?= htmlspecialchars($student['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modern">Phone</label>
                        <input type="text" name="phone" class="form-control form-control-modern" value="<?= htmlspecialchars($student['phone'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modern">CGPA</label>
                        <input type="number" step="0.01" min="0" max="4" name="cgpa" class="form-control form-control-modern" value="<?= $student['cgpa'] ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modern">Session</label>
                        <select name="session" class="form-select form-control-modern">
                            <option value="0">Select</option>
                            <?php while ($s = mysqli_fetch_assoc($sessions)): ?>
                                <option value="<?= $s['id'] ?>" <?= $student['session']==$s['id']?'selected':'' ?>><?= htmlspecialchars($s['session']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modern">Department</label>
                        <select name="department" class="form-select form-control-modern">
                            <option value="0">Select</option>
                            <?php while ($d = mysqli_fetch_assoc($departments)): ?>
                                <option value="<?= $d['id'] ?>" <?= $student['department']==$d['id']?'selected':'' ?>><?= htmlspecialchars($d['department']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modern">Level</label>
                        <select name="level" class="form-select form-control-modern">
                            <option value="0">Select</option>
                            <?php while ($l = mysqli_fetch_assoc($levels)): ?>
                                <option value="<?= $l['id'] ?>" <?= $student['level']==$l['id']?'selected':'' ?>><?= htmlspecialchars($l['level']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modern">Semester</label>
                        <select name="semester" class="form-select form-control-modern">
                            <option value="0">Select</option>
                            <?php while ($sem = mysqli_fetch_assoc($semesters)): ?>
                                <option value="<?= $sem['id'] ?>" <?= $student['semester']==$sem['id']?'selected':'' ?>><?= htmlspecialchars($sem['semester']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label-modern">Profile Photo</label>
                        <input type="file" name="photo" class="form-control form-control-modern" accept="image/*">
                        <small class="text-muted">Max 2MB (JPG, PNG, GIF, WEBP)</small>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" name="update" class="btn btn-primary-modern">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
