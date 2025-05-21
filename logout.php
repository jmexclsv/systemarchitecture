<?php
session_start();
session_destroy();
header("Location: e-commerce.php");
exit;
?>