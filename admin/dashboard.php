<?php
include('includes/config.php');
$pageTitle = 'Dashboard';
include('includes/header.php');

$totalStudents = mysqli_fetch_assoc(mysqli_query($bd, "SELECT COUNT(*) as c FROM students"))['c'];
$totalCourses = mysqli_fetch_assoc(mysqli_query($bd, "SELECT COUNT(*) as c FROM course"))['c'];
$totalEnrolls = mysqli_fetch_assoc(mysqli_query($bd, "SELECT COUNT(*) as c FROM courseenrolls"))['c'];
$totalDepts = mysqli_fetch_assoc(mysqli_query($bd, "SELECT COUNT(*) as c FROM department"))['c'];

$recentEnrolls = mysqli_query($bd, "SELECT ce.*, c.courseName, c.courseCode, s.studentName 
    FROM courseenrolls ce 
    JOIN course c ON ce.course = c.id 
    JOIN students s ON ce.studentRegno = s.StudentRegno 
    ORDER BY ce.enrollDate DESC LIMIT 8");

$recentLogs = mysqli_query($bd, "SELECT * FROM userlog ORDER BY id DESC LIMIT 5");
?>

<div class="row g-4 mb-4 stagger-children">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="stat-label">Total Students</div>
                    <div class="stat-value" data-counter="<?= $totalStudents ?>"><?= $totalStudents ?></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#4f46e5,#6366f1);"><i class="fas fa-user-graduate"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="border-left-color:#0d9488;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="stat-label">Courses</div>
                    <div class="stat-value" data-counter="<?= $totalCourses ?>"><?= $totalCourses ?></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#0d9488,#14b8a6);"><i class="fas fa-book"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="border-left-color:#f59e0b;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="stat-label">Enrollments</div>
                    <div class="stat-value" data-counter="<?= $totalEnrolls ?>"><?= $totalEnrolls ?></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);"><i class="fas fa-clipboard-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="border-left-color:#3b82f6;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="stat-label">Departments</div>
                    <div class="stat-value" data-counter="<?= $totalDepts ?>"><?= $totalDepts ?></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);"><i class="fas fa-building"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-modern p-4 fade-in">
            <h5 class="fw-bold mb-3"><i class="fas fa-list me-2 text-primary"></i> Recent Enrollments</h5>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr><th>Student</th><th>Course</th><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($recentEnrolls)): while ($r = mysqli_fetch_assoc($recentEnrolls)): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($r['studentName']) ?></td>
                            <td><?= htmlspecialchars($r['courseCode']) ?></td>
                            <td><?= date('d M Y', strtotime($r['enrollDate'])) ?></td>
                            <td><span class="badge badge-modern badge-success"><?= $r['status'] ?></span></td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">No enrollments yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-modern p-4 fade-in">
            <h5 class="fw-bold mb-3"><i class="fas fa-sign-in-alt me-2 text-primary"></i> Recent Logins</h5>
            <?php if (mysqli_num_rows($recentLogs)): while ($log = mysqli_fetch_assoc($recentLogs)): ?>
            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                <div class="stat-icon" style="width:40px;height:40px;font-size:0.9rem;background:linear-gradient(135deg,#4f46e5,#818cf8);">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="fw-semibold small"><?= htmlspecialchars($log['studentRegno']) ?></div>
                    <div class="text-muted" style="font-size:0.8rem;"><?= date('d M, H:i', strtotime($log['loginTime'])) ?> · <?= $log['userip'] ?></div>
                </div>
            </div>
            <?php endwhile; else: ?>
            <p class="text-muted text-center py-3">No login records</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
