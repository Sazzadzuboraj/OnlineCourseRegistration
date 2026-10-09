<?php
include('includes/config.php');

if (!empty($_SESSION['login'])) {
    $regno = $_SESSION['login'];
    $time = date('Y-m-d H:i:s');
    mysqli_query($bd, "UPDATE userlog SET logout='$time' WHERE studentRegno='$regno' AND logout IS NULL ORDER BY id DESC LIMIT 1");
}

session_unset();
session_destroy();
header('Location: index.php');
exit;
?>
