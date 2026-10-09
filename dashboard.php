<?php
include('includes/config.php');
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
include('includes/header.php');

$regno = $_SESSION['login'];

// Stats
$totalEnrolled = mysqli_fetch_assoc(mysqli_query($bd, "SELECT COUNT(*) as c FROM courseenrolls WHERE studentRegno='$regno'"))['c'];
$student = mysqli_fetch_assoc(mysqli_query($bd, "SELECT s.*, d.department as deptName, l.level as levelName, sem.semester as semName, ses.session as sesName 
    FROM students s 
    LEFT JOIN department d ON s.department = d.id 
    LEFT JOIN level l ON s.level = l.id 
    LEFT JOIN semester sem ON s.semester = sem.id 
    LEFT JOIN session ses ON s.session = ses.id 
    WHERE s.StudentRegno='$regno'"));

$recent = mysqli_query($bd, "SELECT ce.*, c.courseName, c.courseCode 
    FROM courseenrolls ce 
    JOIN course c ON ce.course = c.id 
    WHERE ce.studentRegno='$regno' 
    ORDER BY ce.enrollDate DESC LIMIT 5");
?>

<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h1 class="page-title">Welcome back, <?= htmlspecialchars(explode(' ', $student['studentName'])[0]) ?>! 👋</h1>
        <p class="page-subtitle">Here's what's happening with your courses today.</p>
    </div>
    <a href="enroll.php" class="btn btn-primary-modern d-none d-md-inline-flex">
        <i class="fas fa-plus me-2"></i> Enroll New Course
    </a>
</div>

<div class="row g-4 mb-4 stagger-children">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Enrolled Courses</div>
                    <div class="stat-value" data-counter="<?= $totalEnrolled ?>"><?= $totalEnrolled ?></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#4f46e5,#6366f1);">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="border-left-color:#0d9488;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">CGPA</div>
                    <div class="stat-value"><?= number_format($student['cgpa'], 2) ?></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#0d9488,#14b8a6);">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="border-left-color:#f59e0b;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Level</div>
                    <div class="stat-value" style="font-size:1.4rem;"><?= htmlspecialchars($student['levelName'] ?? 'N/A') ?></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="border-left-color:#3b82f6;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Department</div>
                    <div class="stat-value" style="font-size:1.1rem;"><?= htmlspecialchars($student['deptName'] ?? 'N/A') ?></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-modern p-4 fade-in">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-primary"></i> Recent Enrollments</h5>
                <a href="enroll-history.php" class="btn btn-sm btn-outline-modern">View All</a>
            </div>
            <?php if (mysqli_num_rows($recent) > 0): ?>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Code</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($r = mysqli_fetch_assoc($recent)): ?>
                        <tr data-searchable>
                            <td class="fw-semibold"><?= htmlspecialchars($r['courseName']) ?></td>
                            <td><span class="badge badge-modern badge-primary"><?= htmlspecialchars($r['courseCode']) ?></span></td>
                            <td><?= date('d M Y', strtotime($r['enrollDate'])) ?></td>
                            <td><span class="badge badge-modern badge-success"><?= $r['status'] ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p class="mb-2">No enrollments yet</p>
                <a href="enroll.php" class="btn btn-primary-modern btn-sm">Enroll Now</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-modern overflow-hidden fade-in">
            <div class="profile-cover"></div>
            <div class="text-center" style="padding-top:70px;padding-bottom:1.5rem;">
                <img src="studentphoto/<?= htmlspecialchars($student['studentPhoto'] ?: 'noimage.png') ?>" 
                     class="profile-avatar-lg" 
                     onerror="this.src='studentphoto/noimage.png'"
                     alt="Profile">
                <h5 class="mt-2 mb-0 fw-bold"><?= htmlspecialchars($student['studentName']) ?></h5>
                <p class="text-muted mb-1"><?= htmlspecialchars($student['StudentRegno']) ?></p>
                <p class="small text-muted mb-3"><?= htmlspecialchars($student['email'] ?? '') ?></p>
                <div class="d-flex justify-content-center gap-2 flex-wrap px-3">
                    <span class="badge badge-modern badge-info"><?= htmlspecialchars($student['sesName'] ?? 'Session N/A') ?></span>
                    <span class="badge badge-modern badge-primary"><?= htmlspecialchars($student['semName'] ?? 'Semester N/A') ?></span>
                </div>
                <a href="my-profile.php" class="btn btn-outline-modern btn-sm mt-3">
                    <i class="fas fa-edit me-1"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
