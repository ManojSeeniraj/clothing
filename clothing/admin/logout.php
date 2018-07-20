<?php 
 require_once $_SERVER['DOCUMENT_ROOT'].'/clothing/core/init.php';
unset($_SESSION['LogUser']);
header('Location:login.php');
?>