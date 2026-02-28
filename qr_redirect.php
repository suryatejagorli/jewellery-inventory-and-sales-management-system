<?php
if (isset($_GET['id'])) {
    header("Location: mobile_pay.php?id=" . (int)$_GET['id']);
    exit();
}
header("Location: index.php");
exit();