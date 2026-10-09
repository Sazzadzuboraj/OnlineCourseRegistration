<?php
include('includes/config.php');
$pageTitle = 'My Enrollments';
$activePage = 'history';
include('includes/header.php');

$regno = $_SESSION['login'];

// Handle rating submit
if (isset($_POST['submit_rating'])) {
    $courseId = (int)$_POST['courseId'];
    $rating = (int)$_POST['rating'];
    $review = clean($_POST['review'] ?? '');
    if ($rating >= 1 && $rating <= 5) {
        // Check enrolled
        $enrolled = mysqli_fetch_assoc(mysqli_query($bd, "SELECT id FROM courseenrolls WHERE studentRegno='$regno' AND course=$courseId"));
        if ($enrolled) {
            $exists = mysqli_fetch_assoc(mysqli_query($bd, "SELECT id FROM ratings WHERE studentRegno='$regno' AND courseId=$courseId"));
            if ($exists) {
                mysqli_query($bd, "UPDATE ratings SET rating=$rating, review='$review' WHERE id={$exists['id']}");
                setFlash('success', 'Your rating has been updated!');
            } else {
                mysqli_query($bd, "INSERT INTO ratings (studentRegno, courseId, rating, review) VALUES ('$regno', $courseId, $rating, '$review')");
                setFlash('success', 'Thank you for rating this course!');
            }
        }
    }
    header('Location: enroll-history.php');
    exit;
}

$history = mysqli_query($bd, "SELECT ce.*, c.courseName, c.courseCode, c.courseUnit, c.id as courseId,
    s.session as sesName, d.department as deptName, l.level as levelName, sem.semester as semName,
    (SELECT rating FROM ratings WHERE studentRegno='$regno' AND courseId=c.id) as myRating
    FROM courseenrolls ce
    JOIN course c ON ce.course = c.id
    LEFT JOIN session s ON ce.session = s.id
    LEFT JOIN department d ON ce.department = d.id
    LEFT JOIN level l ON ce.level = l.id
    LEFT JOIN semester sem ON ce.semester = sem.id
    WHERE ce.studentRegno = '$regno'
    ORDER BY ce.enrollDate DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4 fade-in flex-wrap gap-2">
    <div>
        <h1 class="page-title">My Enrollments</h1>
        <p class="page-subtitle">History, ratings & printable slips</p>
    </div>
    <a href="enroll.php" class="btn btn-primary-modern">
        <i class="fas fa-plus me-1"></i> New Enrollment
    </a>
</div>

<div class="card-modern overflow-hidden fade-in">
    <?php if (mysqli_num_rows($history) > 0): ?>
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Course</th>
                    <th>Code</th>
                    <th>Credits</th>
                    <th>Session</th>
                    <th>Date</th>
                    <th>My Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; while ($row = mysqli_fetch_assoc($history)): ?>
                <tr data-searchable>
                    <td><?= $i++ ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($row['courseName']) ?></td>
                    <td><span class="badge badge-modern badge-primary"><?= htmlspecialchars($row['courseCode']) ?></span></td>
                    <td><?= $row['courseUnit'] ?></td>
                    <td><?= htmlspecialchars($row['sesName'] ?? '-') ?></td>
                    <td><?= date('d M Y', strtotime($row['enrollDate'])) ?></td>
                    <td>
                        <?php if ($row['myRating']): ?>
                        <div class="star-rating star-rating-display">
                            <?php for ($s=1; $s<=5; $s++): ?>
                                <i class="fas fa-star star <?= $s <= $row['myRating'] ? 'filled' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <?php else: ?>
                        <span class="text-muted small">Not rated</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="print-slip.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-modern" title="Print Slip" target="_blank">
                                <i class="fas fa-print"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-secondary-modern" data-bs-toggle="modal" data-bs-target="#rateModal<?= $row['id'] ?>" title="Rate Course">
                                <i class="fas fa-star"></i>
                            </button>
                        </div>

                        <!-- Rate Modal -->
                        <div class="modal fade" id="rateModal<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius:16px;border:none;">
                                    <div class="modal-header border-0" style="background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:white;">
                                        <h5 class="modal-title fw-bold">Rate: <?= htmlspecialchars($row['courseCode']) ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body p-4">
                                            <input type="hidden" name="courseId" value="<?= $row['courseId'] ?>">
                                            <p class="mb-3"><?= htmlspecialchars($row['courseName']) ?></p>
                                            <label class="form-label-modern">Your Rating</label>
                                            <div class="star-rating star-rating-input mb-3" style="font-size:1.5rem;">
                                                <input type="hidden" name="rating" value="<?= $row['myRating'] ?: 5 ?>" required>
                                                <?php for ($s=1; $s<=5; $s++): ?>
                                                    <i class="fas fa-star star <?= $s <= ($row['myRating'] ?: 5) ? 'filled' : '' ?>" data-value="<?= $s ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <label class="form-label-modern">Review (optional)</label>
                                            <textarea name="review" class="form-control form-control-modern" rows="3" placeholder="Share your experience..."></textarea>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="submit_rating" class="btn btn-primary-modern">Submit Rating</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-clipboard-list"></i>
        <p class="mb-2">You haven't enrolled in any courses yet.</p>
        <a href="enroll.php" class="btn btn-primary-modern btn-sm">Start Enrolling</a>
    </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
