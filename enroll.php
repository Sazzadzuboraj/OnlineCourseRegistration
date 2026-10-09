<?php
include('includes/config.php');
$pageTitle = 'Enroll Course';
$activePage = 'enroll';
include('includes/header.php');

$regno = $_SESSION['login'];
$student = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM students WHERE StudentRegno='$regno'"));

$sessions = mysqli_query($bd, "SELECT * FROM session ORDER BY id DESC");
$departments = mysqli_query($bd, "SELECT * FROM department ORDER BY department");
$levels = mysqli_query($bd, "SELECT * FROM level");
$semesters = mysqli_query($bd, "SELECT * FROM semester ORDER BY id DESC");
$courses = mysqli_query($bd, "SELECT * FROM course ORDER BY courseCode");

if (isset($_POST['enroll'])) {
    $pincode = clean($_POST['pincode']);
    $session = (int)$_POST['session'];
    $department = (int)$_POST['department'];
    $level = (int)$_POST['level'];
    $semester = (int)$_POST['semester'];
    $course = (int)$_POST['course'];

    // Verify pincode
    if ($pincode !== $student['pincode']) {
        setFlash('danger', 'Invalid pincode. Please check your registration pincode.');
        header('Location: enroll.php');
        exit;
    }

    // Check seats
    $courseInfo = mysqli_fetch_assoc(mysqli_query($bd, "SELECT noofSeats FROM course WHERE id=$course"));
    $enrolledCount = mysqli_fetch_assoc(mysqli_query($bd, "SELECT COUNT(*) as c FROM courseenrolls WHERE course=$course"))['c'];
    if ($enrolledCount >= $courseInfo['noofSeats']) {
        setFlash('danger', 'Sorry, this course is full. No seats available.');
        header('Location: enroll.php');
        exit;
    }

    // Check if already enrolled
    $exists = mysqli_fetch_assoc(mysqli_query($bd, "SELECT id FROM courseenrolls WHERE studentRegno='$regno' AND course=$course AND session=$session"));
    if ($exists) {
        setFlash('danger', 'You are already enrolled in this course for the selected session.');
        header('Location: enroll.php');
        exit;
    }

    $sql = "INSERT INTO courseenrolls (studentRegno, pincode, session, department, level, semester, course) 
            VALUES ('$regno', '$pincode', $session, $department, $level, $semester, $course)";
    if (mysqli_query($bd, $sql)) {
        // Create notification
        $courseName = mysqli_fetch_assoc(mysqli_query($bd, "SELECT courseName FROM course WHERE id=$course"))['courseName'];
        mysqli_query($bd, "INSERT INTO notifications (userType, userId, title, message) VALUES ('student', '$regno', 'Enrollment Successful', 'You have successfully enrolled in $courseName')");
        setFlash('success', 'Successfully enrolled in the course!');
        header('Location: enroll-history.php');
        exit;
    } else {
        setFlash('danger', 'Enrollment failed. Please try again.');
        header('Location: enroll.php');
        exit;
    }
}
?>

<div class="mb-4 fade-in">
    <h1 class="page-title">Enroll in a Course</h1>
    <p class="page-subtitle">Select your details and choose a course to register</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-modern p-4 p-md-5 scale-in">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-modern">Session</label>
                        <select name="session" class="form-select form-control-modern" required>
                            <option value="">Select Session</option>
                            <?php while ($s = mysqli_fetch_assoc($sessions)): ?>
                                <option value="<?= $s['id'] ?>" <?= $student['session'] == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['session']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modern">Department</label>
                        <select name="department" class="form-select form-control-modern" required>
                            <option value="">Select Department</option>
                            <?php mysqli_data_seek($departments, 0); while ($d = mysqli_fetch_assoc($departments)): ?>
                                <option value="<?= $d['id'] ?>" <?= $student['department'] == $d['id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['department']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modern">Level</label>
                        <select name="level" class="form-select form-control-modern" required>
                            <option value="">Select Level</option>
                            <?php while ($l = mysqli_fetch_assoc($levels)): ?>
                                <option value="<?= $l['id'] ?>" <?= $student['level'] == $l['id'] ? 'selected' : '' ?>><?= htmlspecialchars($l['level']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modern">Semester</label>
                        <select name="semester" class="form-select form-control-modern" required>
                            <option value="">Select Semester</option>
                            <?php while ($sem = mysqli_fetch_assoc($semesters)): ?>
                                <option value="<?= $sem['id'] ?>" <?= $student['semester'] == $sem['id'] ? 'selected' : '' ?>><?= htmlspecialchars($sem['semester']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label-modern">Course</label>
                        <select name="course" class="form-select form-control-modern" required>
                            <option value="">Select Course</option>
                            <?php while ($c = mysqli_fetch_assoc($courses)): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['courseCode'] . ' — ' . $c['courseName'] . ' (' . $c['courseUnit'] . ' credits)') ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-modern">Your Pincode <small class="text-muted">(from registration)</small></label>
                        <div class="input-group-icon">
                            <i class="fas fa-shield-alt"></i>
                            <input type="text" name="pincode" class="form-control form-control-modern" placeholder="6-digit pincode" required maxlength="6">
                        </div>
                    </div>
                </div>
                <div class="mt-4 d-grid">
                    <button type="submit" name="enroll" class="btn btn-primary-modern py-2">
                        <i class="fas fa-check-circle me-2"></i> Confirm Enrollment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
