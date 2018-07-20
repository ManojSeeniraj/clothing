<?php 
$db =mysqli_connect('127.0.0.1','root','jesuslove1234','clothing');
if(mysqli_connect_errno()){
echo 'There is an error with: '.mysql_connect_error();
die();
}
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/clothing/config.php';
require_once  BASEURL.'helpers/helpers.php';



if(isset($_SESSION['LogUser'])){
    $user_id=$_SESSION['LogUser'];
    $query= $db->query("SELECT * FROM User WHERE id='$user_id'");
    $user_data=mysqli_fetch_assoc($query);
    $fn=explode(" ",$user_data['full_name']);
    $user_data['first']=$fn[0];
    $user_data['last']=$fn[1];
}

if(isset($_SESSION['success_flash'])){
    echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'<p></div>';
    unset($_SESSION['success_flash']);
}

if(isset($_SESSION['error_flash'])){
    echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'<p></div>';
    unset($_SESSION['error_flash']);
}
