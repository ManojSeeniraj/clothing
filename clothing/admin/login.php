<?php
 require_once $_SERVER['DOCUMENT_ROOT'].'/clothing/core/init.php';
 include 'includes/head.php';
 
 $email=((isset($_POST['email']))?standardize($_POST['email']):'');
 $email=trim($email);
 $Password=((isset($_POST['Password']))?standardize($_POST['Password']):'');
 $Password=trim($Password);
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

    if(empty($_POST['email']) || empty($_POST['Password']))
    {
    $errors[]="You have to enter the email and password";
    }

    if(!filter_var($email,FILTER_VALIDATE_EMAIL))
    {
     $errors[]= "You must enter a valid email";
    }

    //if the lenghth of password is small

    if(strlen($Password) <6){
        $errors[]= "Password must be greater than 6 characters";
    }
   //check if the email is present 
   $query=$db->query("SELECT * FROM user where email='$email'");
   $User=mysqli_fetch_assoc($query);
   $userCount=mysqli_num_rows($query);

   if(!password_verify($Password, $User['password'])){

    $errors[]= "Incorrect Password plz try again!.";
}

   if($userCount < 1){
       $errors[] ="That email doesn\'t exist in the database";
       }


    //display the error
   if(!empty($errors)){
     echo errorshow($errors);
    }else{
        $user_id=$User['id'];
        login($user_id);
    }


 }

   ?>
 </div>
 <h2 class="text-center">Login</h2><hr>
 <form action="login.php" method="post">
 <div class="form-group">
 <label for="email"><b>Email:</b></label>
 <input type="text" class="form-control" name="email" id="email" value="<?=$email;?>">
 </div>
 <div class="form-group">
 <label for="Password"><b>Password:</b></label>
 <input type="Password" class="form-control" name="Password" id="Password" value="<?=$Password;?>">
 </div>
 <div class="form-group">
 <input type="submit" value="login" class="btn btn-success">
 </div>
 </form>
 <div style="background-color:#f1f1f1">
 <p class="text-right"><a href="/clothing/index.php">Visit Site</a><p>
 </div>
 </div>


 <?php include 'includes/footer.php'; ?>