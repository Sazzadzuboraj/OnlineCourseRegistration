<?php
include('includes/config.php');
$pageTitle = 'Manage Students';
include('includes/header.php');

// Add student
if (isset($_POST['add'])) {
    $name = clean($_POST['studentName']);
    $regno = clean($_POST['StudentRegno']);
    $email = clean($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $pincode = rand(100000, 999999);
    $session = (int)$_POST['session'];
    $department = (int)$_POST['department'];
    $level = (int)$_POST['level'];
    $semester = (int)$_POST['semester'];

    $check = mysqli_query($bd, "SELECT StudentRegno FROM students WHERE StudentRegno='$regno'");
    if (mysqli_num_rows($check) > 0) {
        setFlash('danger', 'Registration number already exists.');
    } else {
        $sql = "INSERT INTO students (StudentRegno, studentName, email, password, pincode, session, department, level, semester) 
                VALUES ('$regno', '$name', '$email', '$password', '$pincode', $session, $department, $level, $semester)";
        if (mysqli_query($bd, $sql)) {
            setFlash('success', "Student registered! Pincode: $pincode");
        } else {
            setFlash('danger', 'Registration failed.');
        }
    }
    header('Location: students.php');
    exit;
}

// Delete
if (isset($_GET['delete'])) {
    $id = clean($_GET['delete']);
    mysqli_query($bd, "DELETE FROM students WHERE StudentRegno='$id'");
    setFlash('success', 'Student deleted.');
    header('Location: students.php');
    exit;
}

$students = mysqli_query($bd, "SELECT s.*, d.department as deptName, l.level as levelName 
    FROM students s 
    LEFT JOIN department d ON s.department=d.id 
    LEFT JOIN level l ON s.level=l.id 
    ORDER BY s.creationdate DESC");
$sessions = mysqli_query($bd, "SELECT * FROM session");
$departments = mysqli_query($bd, "SELECT * FROM department");
$levels = mysqli_query($bd, "SELECT * FROM level");
$semesters = mysqli_query($bd, "SELECT * FROM semester");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="page-subtitle mb-0">Register and manage student accounts</p>
    <button class="btn btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus me-1"></i> Add Student
    </button>
</div>

<div class="card-modern overflow-hidden fade-in">
    <div class="p-3 border-bottom">
        <input type="text" id="liveSearch" class="form-control form-control-modern" placeholder="Search students...">
    </div>
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>Reg No</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Level</th>
                    <th>CGPA</th>
                    <th>Pincode</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($s = mysqli_fetch_assoc($students)): ?>
                <tr data-searchable>
                    <td class="fw-semibold"><?= htmlspecialchars($s['StudentRegno']) ?></td>
                    <td><?= htmlspecialchars($s['studentName']) ?></td>
                    <td><?= htmlspecialchars($s['deptName'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($s['levelName'] ?? '-') ?></td>
                    <td><?= number_format($s['cgpa'], 2) ?></td>
                    <td><code><?= $s['pincode'] ?></code></td>
                    <td>
                        <a href="?delete=<?= urlencode($s['StudentRegno']) ?>" class="btn btn-sm btn-danger-modern" data-confirm="Delete this student?">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#4f46e5,#3730a3);color:white;">
                <h5 class="modal-title fw-bold">Register New Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-modern">Full Name</label>
                            <input type="text" name="studentName" class="form-control form-control-modern" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modern">Registration No</label>
                            <input type="text" name="StudentRegno" class="form-control form-control-modern" required placeholder="2025-CSE-002">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modern">Email</label>
                            <input type="email" name="email" class="form-control form-control-modern">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modern">Password</label>
                            <input type="password" name="password" class="form-control form-control-modern" required minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modern">Session</label>
                            <select name="session" class="form-select form-control-modern">
                                <?php while ($s = mysqli_fetch_assoc($sessions)): ?>
                                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['session']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modern">Department</label>
                            <select name="department" class="form-select form-control-modern">
                                <?php while ($d = mysqli_fetch_assoc($departments)): ?>
                                    <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['department']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modern">Level</label>
                            <select name="level" class="form-select form-control-modern">
                                <?php while ($l = mysqli_fetch_assoc($levels)): ?>
                                    <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['level']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-modern">Semester</label>
                            <select name="semester" class="form-select form-control-modern">
                                <?php while ($sem = mysqli_fetch_assoc($semesters)): ?>
                                    <option value="<?= $sem['id'] ?>"><?= htmlspecialchars($sem['semester']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add" class="btn btn-primary-modern">Register Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
