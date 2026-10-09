<?php
include('includes/config.php');
$pageTitle = 'Course Catalog';
$activePage = 'courses';
include('includes/header.php');

$search = isset($_GET['q']) ? clean($_GET['q']) : '';
$deptFilter = isset($_GET['dept']) ? (int)$_GET['dept'] : 0;

$sql = "SELECT c.*, d.department as deptName,
        (SELECT ROUND(AVG(rating),1) FROM ratings WHERE courseId = c.id) as avgRating,
        (SELECT COUNT(*) FROM ratings WHERE courseId = c.id) as ratingCount
        FROM course c 
        LEFT JOIN department d ON c.departmentId = d.id WHERE 1=1";
if ($search) $sql .= " AND (c.courseName LIKE '%$search%' OR c.courseCode LIKE '%$search%')";
if ($deptFilter) $sql .= " AND c.departmentId = $deptFilter";
$sql .= " ORDER BY c.courseCode";

$courses = mysqli_query($bd, $sql);
$departments = mysqli_query($bd, "SELECT * FROM department ORDER BY department");
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 fade-in">
    <div>
        <h1 class="page-title">Course Catalog</h1>
        <p class="page-subtitle">Browse courses, ratings & available seats</p>
    </div>
    <form class="d-flex gap-2 flex-wrap" method="GET">
        <input type="text" name="q" class="form-control form-control-modern" placeholder="Search courses..." value="<?= htmlspecialchars($search) ?>" style="min-width:180px;">
        <select name="dept" class="form-select form-control-modern" style="min-width:150px;">
            <option value="0">All Departments</option>
            <?php while ($d = mysqli_fetch_assoc($departments)): ?>
                <option value="<?= $d['id'] ?>" <?= $deptFilter == $d['id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['department']) ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn btn-primary-modern"><i class="fas fa-search"></i></button>
    </form>
</div>

<div class="row g-4 stagger-children">
    <?php if (mysqli_num_rows($courses) > 0): ?>
        <?php while ($c = mysqli_fetch_assoc($courses)): 
            $enrolledCount = mysqli_fetch_assoc(mysqli_query($bd, "SELECT COUNT(*) as cnt FROM courseenrolls WHERE course={$c['id']}"))['cnt'];
            $seatsLeft = $c['noofSeats'] - $enrolledCount;
            $avg = $c['avgRating'] ? (float)$c['avgRating'] : 0;
            $rCount = (int)$c['ratingCount'];
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="course-card">
                <div class="course-card-header"></div>
                <div class="course-card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="course-code"><?= htmlspecialchars($c['courseCode']) ?></div>
                        <?php if ($rCount > 0): ?>
                        <div class="star-rating star-rating-display" title="<?= $avg ?> / 5 (<?= $rCount ?> reviews)">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star star <?= $i <= round($avg) ? 'filled' : '' ?>"></i>
                            <?php endfor; ?>
                            <span class="rating-value"><?= number_format($avg, 1) ?></span>
                        </div>
                        <?php else: ?>
                        <span class="rating-value">No ratings</span>
                        <?php endif; ?>
                    </div>
                    <div class="course-title"><?= htmlspecialchars($c['courseName']) ?></div>
                    <p class="text-muted small mb-2" style="color:var(--text-muted)!important"><?= htmlspecialchars($c['description'] ?? 'No description available.') ?></p>
                    <div class="d-flex gap-2 mb-2 flex-wrap">
                        <span class="badge badge-modern badge-info"><?= (int)$c['courseUnit'] ?> Credits</span>
                        <span class="badge badge-modern badge-primary"><?= htmlspecialchars($c['deptName'] ?? 'General') ?></span>
                    </div>
                    <div class="course-meta">
                        <span><i class="fas fa-chair me-1"></i> <?= $seatsLeft ?> seats left</span>
                        <span><i class="fas fa-users me-1"></i> <?= $enrolledCount ?> enrolled</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="empty-state card-modern">
                <i class="fas fa-search"></i>
                <p>No courses found matching your criteria.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
