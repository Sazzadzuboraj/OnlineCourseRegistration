<?php
include('includes/config.php');
$pageTitle = 'Manage Courses';
include('includes/header.php');

if (isset($_POST['add'])) {
    $code = clean($_POST['courseCode']);
    $name = clean($_POST['courseName']);
    $unit = (int)$_POST['courseUnit'];
    $seats = (int)$_POST['noofSeats'];
    $dept = (int)$_POST['departmentId'];
    $desc = clean($_POST['description']);
    mysqli_query($bd, "INSERT INTO course (courseCode, courseName, courseUnit, noofSeats, departmentId, description) 
        VALUES ('$code', '$name', $unit, $seats, $dept, '$desc')");
    setFlash('success', 'Course added successfully.');
    header('Location: courses.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($bd, "DELETE FROM course WHERE id=$id");
    setFlash('success', 'Course deleted.');
    header('Location: courses.php');
    exit;
}

$courses = mysqli_query($bd, "SELECT c.*, d.department as deptName FROM course c LEFT JOIN department d ON c.departmentId=d.id ORDER BY c.courseCode");
$departments = mysqli_query($bd, "SELECT * FROM department");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="page-subtitle mb-0">Add and manage courses</p>
    <button class="btn btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus me-1"></i> Add Course</button>
</div>

<div class="card-modern overflow-hidden fade-in">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr><th>Code</th><th>Name</th><th>Credits</th><th>Seats</th><th>Department</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while ($c = mysqli_fetch_assoc($courses)): ?>
                <tr>
                    <td><span class="badge badge-modern badge-primary"><?= htmlspecialchars($c['courseCode']) ?></span></td>
                    <td class="fw-semibold"><?= htmlspecialchars($c['courseName']) ?></td>
                    <td><?= $c['courseUnit'] ?></td>
                    <td><?= $c['noofSeats'] ?></td>
                    <td><?= htmlspecialchars($c['deptName'] ?? '-') ?></td>
                    <td>
                        <a href="edit-course.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-modern me-1"><i class="fas fa-edit"></i></a>
                        <a href="?delete=<?= $c['id'] ?>" class="btn btn-sm btn-danger-modern" data-confirm="Delete this course?"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#4f46e5,#3730a3);color:white;">
                <h5 class="modal-title fw-bold">Add New Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label-modern">Course Code</label>
                        <input type="text" name="courseCode" class="form-control form-control-modern" required placeholder="CSE-4101">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Course Name</label>
                        <input type="text" name="courseName" class="form-control form-control-modern" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label-modern">Credits</label>
                            <input type="number" name="courseUnit" class="form-control form-control-modern" value="3" min="1" max="6">
                        </div>
                        <div class="col-6">
                            <label class="form-label-modern">Seats</label>
                            <input type="number" name="noofSeats" class="form-control form-control-modern" value="40" min="1">
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label-modern">Department</label>
                        <select name="departmentId" class="form-select form-control-modern">
                            <option value="0">General</option>
                            <?php while ($d = mysqli_fetch_assoc($departments)): ?>
                                <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['department']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Description</label>
                        <textarea name="description" class="form-control form-control-modern" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add" class="btn btn-primary-modern">Add Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
