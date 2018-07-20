<?php
 require_once $_SERVER['DOCUMENT_ROOT'].'/clothing/core/init.php';
 if(!is_logged_in()){
    login_error_redirect();
 }
 include 'includes/head.php';


 $hashed=$user_data['password'];
 $old_Password=((isset($_POST['old_Password']))?standardize($_POST['old_Password']):'');
 $old_Password=trim($old_Password);
 $Password=((isset($_POST['Password']))?standardize($_POST['Password']):'');
 $Password=trim($Password);
 $confirm=((isset($_POST['confirm']))?standardize($_POST['confirm']):'');
 $confirm=trim($confirm);
 $new_hash=password_hash($Password,PASSWORD_DEFAULT);
 $user_id=$user_data['id'];
 $errors=array();

 ?> 
 <style>
           body{
               background-image:url("/clothing/images/headerlogo/background.png");
               background-size: 100vw 100vh;
               background-attachment: fixed;
           }
     </style>

 <div class="login-form">
 <div>

<?php

if($_POST) 
{

    if(empty($_POST['old_Password']) || empty($_POST['Password'])  || empty($_POST['confirm']))
    {
    $errors[]="You have to fill all the informations.";
    }


    if($Password != $confirm){
        $errors[]="You new Password and confirm password are not matching!.";
    }
   

    //if the lenghth of password is small

    if(strlen($Password) <6){
        $errors[]= "Password must be greater than 6 characters";
    }
   

   if(!password_verify($old_Password, $hashed)){

    $errors[]= "Incorrect Password, we dont have this record!.";
}




    //display the error
   if(!empty($errors)){
     echo errorshow($errors);
    }else{
   //update password
       $db->query("UPDATE user SET  `password`='$new_hash' WHERE id='$user_id'");
       $_SESSION['success_flash']="your password has successfuly changed";
       header('Location:index.php');

      
    }
   


 }

   ?>
 </div>
 <h2 class="text-center">Change Password</h2><hr>
 <form action="changePassword.php" method="post">
 <div class="form-group">
 <label for="old_password"><b>Old Password:</b></label>
 <input type="password" class="form-control" name="old_Password" id="old_Password" value="<?= $old_Password; ?>">
 </div>
 <div class="form-group">
 <label for="Password"><b>new Password:</b></label>
 <input type="Password" class="form-control" name="Password" id="Password" value="<?=$Password;?>">
 </div>
 <div class="form-group">
 <label for="Password"><b>confirm Password:</b></label>
 <input type="Password" class="form-control" name="confirm" id="confirm" value="<?=$confirm;?>">
 </div>
 <div class="form-group">
 <a href="index.php" class="btn btn-danger">cancle</a>
 <input type="submit" value="login" class="btn btn-success">
 <a  href="/clothing/index.php" class="btn btn-primary pull-right">Visit Site</a><p>
 </div>
 </form>
 </div>


 <?php include 'includes/footer.php'; ?>