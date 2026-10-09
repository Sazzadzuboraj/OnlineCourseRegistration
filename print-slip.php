<?php
include('includes/config.php');

if (empty($_SESSION['login'])) {
    header('Location: index.php');
    exit;
}

$regno = $_SESSION['login'];
$id = (int)($_GET['id'] ?? 0);

$row = mysqli_fetch_assoc(mysqli_query($bd, "SELECT ce.*, c.courseName, c.courseCode, c.courseUnit,
    s.session as sesName, d.department as deptName, l.level as levelName, sem.semester as semName,
    st.studentName, st.email, st.StudentRegno
    FROM courseenrolls ce
    JOIN course c ON ce.course = c.id
    JOIN students st ON ce.studentRegno = st.StudentRegno
    LEFT JOIN session s ON ce.session = s.id
    LEFT JOIN department d ON ce.department = d.id
    LEFT JOIN level l ON ce.level = l.id
    LEFT JOIN semester sem ON ce.semester = sem.id
    WHERE ce.id = $id AND ce.studentRegno = '$regno'"));

if (!$row) {
    setFlash('danger', 'Enrollment record not found.');
    header('Location: enroll-history.php');
    exit;
}

$slipNo = 'SLIP-' . str_pad($row['id'], 6, '0', STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Slip - <?= htmlspecialchars($row['courseCode']) ?></title>
    <script>(function(){var t=localStorage.getItem('coursehub-theme')||'light';document.documentElement.setAttribute('data-theme',t);})();</script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="bg-animation no-print">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>
<canvas id="particle-canvas" class="no-print"></canvas>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="enroll-history.php" class="btn btn-outline-modern"><i class="fas fa-arrow-left me-1"></i> Back</a>
        <div class="d-flex gap-2">
            <button type="button" class="theme-toggle" title="Toggle dark mode"><i class="fas fa-moon"></i></button>
            <button onclick="window.print()" class="btn btn-primary-modern"><i class="fas fa-print me-2"></i> Print Slip</button>
        </div>
    </div>

    <div class="print-area">
        <div class="print-slip">
            <div class="print-slip-header">
                <div style="font-size:2rem;margin-bottom:0.4rem;"><i class="fas fa-graduation-cap"></i></div>
                <h2 style="margin:0;font-weight:800;color:white;">CourseHub</h2>
                <p style="margin:0.3rem 0 0;opacity:0.95;">Official Course Enrollment Slip</p>
            </div>
            <div class="print-slip-body">
                <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <strong style="color:#4f46e5;">Slip No:</strong> <?= $slipNo ?>
                    </div>
                    <div>
                        <strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($row['enrollDate'])) ?>
                    </div>
                </div>
                <table>
                    <tr>
                        <th>Student Name</th>
                        <td><?= htmlspecialchars($row['studentName']) ?></td>
                    </tr>
                    <tr>
                        <th>Registration No</th>
                        <td><?= htmlspecialchars($row['StudentRegno']) ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($row['email'] ?? '—') ?></td>
                    </tr>
                    <tr>
                        <th>Course Code</th>
                        <td><strong><?= htmlspecialchars($row['courseCode']) ?></strong></td>
                    </tr>
                    <tr>
                        <th>Course Name</th>
                        <td><?= htmlspecialchars($row['courseName']) ?></td>
                    </tr>
                    <tr>
                        <th>Credits</th>
                        <td><?= (int)$row['courseUnit'] ?></td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td><?= htmlspecialchars($row['deptName'] ?? '—') ?></td>
                    </tr>
                    <tr>
                        <th>Session</th>
                        <td><?= htmlspecialchars($row['sesName'] ?? '—') ?></td>
                    </tr>
                    <tr>
                        <th>Semester</th>
                        <td><?= htmlspecialchars($row['semName'] ?? '—') ?></td>
                    </tr>
                    <tr>
                        <th>Level</th>
                        <td><?= htmlspecialchars($row['levelName'] ?? '—') ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><strong style="color:#059669;"><?= htmlspecialchars($row['status']) ?></strong></td>
                    </tr>
                    <tr>
                        <th>Pincode Used</th>
                        <td><?= htmlspecialchars($row['pincode']) ?></td>
                    </tr>
                </table>
                <p class="mt-3 mb-0 small text-muted">
                    This is a computer-generated enrollment slip. Please keep it for your records.
                    For verification, contact the academic office.
                </p>
            </div>
            <div class="print-slip-footer">
                <div>
                    <div style="border-top:1px solid #cbd5e1;width:140px;margin-bottom:0.3rem;"></div>
                    Student Signature
                </div>
                <div class="text-end">
                    <div style="border-top:1px solid #cbd5e1;width:140px;margin-left:auto;margin-bottom:0.3rem;"></div>
                    Authorized Signature
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
