<?php
include('includes/config.php');
$pageTitle = 'Enrollments';
include('includes/header.php');

if (isset($_GET['delete'])) {
    mysqli_query($bd, "DELETE FROM courseenrolls WHERE id=".(int)$_GET['delete']);
    setFlash('success', 'Enrollment removed.');
    header('Location: enrollments.php'); exit;
}

$rows = mysqli_query($bd, "SELECT ce.*, c.courseName, c.courseCode, s.studentName, ses.session as sesName
    FROM courseenrolls ce
    JOIN course c ON ce.course = c.id
    JOIN students s ON ce.studentRegno = s.StudentRegno
    LEFT JOIN session ses ON ce.session = ses.id
    ORDER BY ce.enrollDate DESC");
?>
<div class="mb-4">
    <p class="page-subtitle">All course enrollment records</p>
</div>
<div class="card-modern overflow-hidden fade-in">
    <div class="p-3 border-bottom">
        <input type="text" id="liveSearch" class="form-control form-control-modern" placeholder="Search enrollments...">
    </div>
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr><th>#</th><th>Student</th><th>Reg No</th><th>Course</th><th>Session</th><th>Date</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php $i=1; while ($r = mysqli_fetch_assoc($rows)): ?>
                <tr data-searchable>
                    <td><?= $i++ ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['studentName']) ?></td>
                    <td><?= htmlspecialchars($r['studentRegno']) ?></td>
                    <td><?= htmlspecialchars($r['courseCode']) ?></td>
                    <td><?= htmlspecialchars($r['sesName'] ?? '-') ?></td>
                    <td><?= date('d M Y', strtotime($r['enrollDate'])) ?></td>
                    <td><span class="badge badge-modern badge-success"><?= $r['status'] ?></span></td>
                    <td><a href="?delete=<?= $r['id'] ?>" class="btn btn-sm btn-danger-modern" data-confirm="Remove this enrollment?"><i class="fas fa-trash"></i></a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include('includes/footer.php'); ?>
