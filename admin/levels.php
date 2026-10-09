<?php
include('includes/config.php');
$pageTitle = 'Levels';
include('includes/header.php');

if (isset($_POST['add'])) {
    $name = clean($_POST['level']);
    mysqli_query($bd, "INSERT INTO level (level) VALUES ('$name')");
    setFlash('success', 'Level added.');
    header('Location: levels.php'); exit;
}
if (isset($_GET['delete'])) {
    mysqli_query($bd, "DELETE FROM level WHERE id=".(int)$_GET['delete']);
    setFlash('success', 'Level deleted.');
    header('Location: levels.php'); exit;
}
$rows = mysqli_query($bd, "SELECT * FROM level ORDER BY id");
?>
<div class="d-flex justify-content-between mb-4">
    <p class="page-subtitle mb-0">Academic levels</p>
    <button class="btn btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus me-1"></i> Add</button>
</div>
<div class="card-modern overflow-hidden">
    <table class="table table-modern mb-0">
        <thead><tr><th>#</th><th>Level</th><th>Created</th><th>Action</th></tr></thead>
        <tbody>
            <?php $i=1; while ($r = mysqli_fetch_assoc($rows)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td class="fw-semibold"><?= htmlspecialchars($r['level']) ?></td>
                <td><?= date('d M Y', strtotime($r['creationDate'])) ?></td>
                <td><a href="?delete=<?= $r['id'] ?>" class="btn btn-sm btn-danger-modern" data-confirm="Delete?"><i class="fas fa-trash"></i></a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#4f46e5,#3730a3);color:white;">
                <h5 class="modal-title">Add Level</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <label class="form-label-modern">Level Name</label>
                    <input type="text" name="level" class="form-control form-control-modern" required placeholder="Level 5">
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" name="add" class="btn btn-primary-modern">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
