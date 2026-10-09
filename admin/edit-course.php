<?php
include('includes/config.php');
$pageTitle = 'Edit Course';
include('includes/header.php');

$id = (int)($_GET['id'] ?? 0);
$course = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM course WHERE id=$id"));
if (!$course) {
    setFlash('danger', 'Course not found.');
    header('Location: courses.php');
    exit;
}

if (isset($_POST['update'])) {
    $code = clean($_POST['courseCode']);
    $name = clean($_POST['courseName']);
    $unit = (int)$_POST['courseUnit'];
    $seats = (int)$_POST['noofSeats'];
    $dept = (int)$_POST['departmentId'];
    $desc = clean($_POST['description']);
    mysqli_query($bd, "UPDATE course SET courseCode='$code', courseName='$name', courseUnit=$unit, noofSeats=$seats, departmentId=$dept, description='$desc' WHERE id=$id");
    setFlash('success', 'Course updated.');
    header('Location: courses.php');
    exit;
}

$departments = mysqli_query($bd, "SELECT * FROM department");
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card-modern p-4 scale-in">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label-modern">Course Code</label>
                    <input type="text" name="courseCode" class="form-control form-control-modern" value="<?= htmlspecialchars($course['courseCode']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label-modern">Course Name</label>
                    <input type="text" name="courseName" class="form-control form-control-modern" value="<?= htmlspecialchars($course['courseName']) ?>" required>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label-modern">Credits</label>
                        <input type="number" name="courseUnit" class="form-control form-control-modern" value="<?= $course['courseUnit'] ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label-modern">Seats</label>
                        <input type="number" name="noofSeats" class="form-control form-control-modern" value="<?= $course['noofSeats'] ?>">
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <label class="form-label-modern">Department</label>
                    <select name="departmentId" class="form-select form-control-modern">
                        <option value="0">General</option>
                        <?php while ($d = mysqli_fetch_assoc($departments)): ?>
                            <option value="<?= $d['id'] ?>" <?= $course['departmentId']==$d['id']?'selected':'' ?>><?= htmlspecialchars($d['department']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label-modern">Description</label>
                    <textarea name="description" class="form-control form-control-modern" rows="3"><?= htmlspecialchars($course['description'] ?? '') ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="update" class="btn btn-primary-modern">Save Changes</button>
                    <a href="courses.php" class="btn btn-outline-modern">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
