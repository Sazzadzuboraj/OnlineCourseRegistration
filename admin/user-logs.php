<?php
include('includes/config.php');
$pageTitle = 'User Logs';
include('includes/header.php');

$rows = mysqli_query($bd, "SELECT * FROM userlog ORDER BY id DESC LIMIT 100");
?>
<div class="mb-4">
    <p class="page-subtitle">Student login activity (latest 100)</p>
</div>
<div class="card-modern overflow-hidden fade-in">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr><th>#</th><th>Reg No</th><th>IP Address</th><th>Login Time</th><th>Logout</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php $i=1; while ($r = mysqli_fetch_assoc($rows)): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['studentRegno']) ?></td>
                    <td><code><?= htmlspecialchars($r['userip']) ?></code></td>
                    <td><?= date('d M Y, H:i:s', strtotime($r['loginTime'])) ?></td>
                    <td><?= $r['logout'] ? date('d M Y, H:i:s', strtotime($r['logout'])) : '<span class="badge badge-modern badge-warning">Active</span>' ?></td>
                    <td><?= $r['status'] ? '<span class="badge badge-modern badge-success">Success</span>' : '<span class="badge badge-modern badge-danger">Failed</span>' ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include('includes/footer.php'); ?>
