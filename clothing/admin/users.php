<?php 
   
   require_once '../core/init.php';
   if(!is_logged_in()){
      header('Location:login.php');
   }
   if(!has_permission('admin')){
    permission_error_redirect('index.php');
   }
   include 'includes/head.php';
   include 'includes/navigation.php';

   $userQuery=$db->query("SELECT * FROM user ORDER BY full_name");
   global $user_data;
   global $permission;

   if(isset($_GET['remove'])){
       $delete_id=standardize($_GET['remove']);
       $delete=$db->query("DELETE FROM user WHERE id='$delete_id'");
       $_SESSION['success_flash']="The user is deleted successfully";
       header('Location:users.php');
   }

   if(isset($_GET['add'])){
       $full_name=((isset($_POST['full_name']))?standardize($_POST['full_name']):'');
       $email=((isset($_POST['email']))?standardize($_POST['email']):'');
       $password=((isset($_POST['password']))?standardize($_POST['password']):'');
       $Confirm_Password=((isset($_POST['Confirm_Password']))?standardize($_POST['Confirm_Password']):'');
       $permission=((isset($_POST['permission']))?standardize($_POST['permission']):'');
       $errors=array();

       if($_POST){

        $query=$db->query("SELECT * FROM user where email='$email'");
        $User=mysqli_fetch_assoc($query);
        $userCount=mysqli_num_rows($query);

        if($userCount !=0){
                
            $errors[]="This mail Address Alredy exists";
        }

        $required=array('full_name','email','password','Confirm_Password','permission');

          foreach ($required as $key => $f) {

            if(empty($_POST[$f])){

                $errors[]="You must fill all the details";
                break;
            }
              
          }
          if(strlen($password) <6){
            $errors[]= "Password must be greater than 6 characters";
        }

        if($password != $Confirm_Password){
            $errors[] ="your passwords are not matching plzz check!";
        }
        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
         $errors[]= "You must enter a valid email";
        }


          if(!empty($errors)){

            echo errorshow($errors);
          }
          else{
              $hashed=password_hash($password,PASSWORD_DEFAULT);
              $db->query("INSERT INTO user (full_name,email,password,permission) values ('$full_name','$email','$hashed','$permission')");
              $_SESSION['success_flash']="The user is Added successfully";
              header('Location:users.php');
              
          }


       }
      
      
    ?>
    <h2 class="text-center">Add Users</h2><hr>

    <form action="users.php?add=1" method="post">

    <div class="form-group col-md-6">
    <lable for="full_name">User Name:</lable>
    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= $full_name; ?>"></input>
    </div>
    
    <div class="form-group col-md-6">
    <lable for="email">email:</lable>
    <input type="text" class="form-control" id="email" name="email" value="<?= $email; ?>"></input>
    </div>

    <div class="form-group col-md-6">
    <lable for="password">Password:</lable>
    <input type="password" class="form-control" id="password" name="password" value="<?= $password; ?>"></input>
    </div>

    <div class="form-group col-md-6">
    <lable for="Confirm_Password">Confirm Password:</lable>
    <input type="password" class="form-control" id="Confirm_Password" name="Confirm_Password" value="<?= $Confirm_Password; ?>"></input>
    </div>
    
    <div class="form-group col-md-6">
    <label for="name">Permission:</label>
    <select class="form-control" name="permission">
    <option value=""<?= (($permission=='')?' selected': '');?>></option>
    <option value="editor"<?= (($permission=='editor')?' selected': '');?>>Editor</option>
    <option value="admin,editor"<?= (($permission=='admin,editor')?' selected': '');?>>Admin</option>
    </select>
    </div>
    <br>
    <div class="form-group  col-md-6 text-right">
    <a href="users.php" class="btn btn-danger">Cancel</a>
    <input type="submit" value="Add User" class="btn btn-primary"></input>
    </div>



    </form>

     <?php
   }else{
 
   $userQuery=$db->query("SELECT * FROM user ORDER BY full_name");
?>


<h2 class="text-center">Users</h2>
<a href="users.php?add=1" class="btn btn-success pull-right" id="add-button">Add User</a><div class="clearfix"></div>
<hr>


<table class="table table-bordered table-striped table-condensed">
<thead>
<th></th>
<th>Name:</th>
<th>Email</th>
<th>Join Date</th>
<th>Last Date</th>
<th>Permission</th>
</thead>
<tbody>
<?php while($user = mysqli_fetch_assoc($userQuery)) : ?>
<tr>
<td>
<?php if($user['id']!= $user_data['id']) :?>
 <a href="users.php?remove=<?= $user['id']; ?>"class="btn btn-xs btn-default" ><span class="glyphicon glyphicon-remove"></a></span>
<?php endif; ?>
</td>
<td><?= $user['full_name']; ?></td>
<td><?= $user['email']; ?></td>
<td><?= prety_date($user['join_date']); ?></td>
<td><?= (($user['last_login']=='0000-00-00 00:00:00')?'Never':prety_date($user['last_login'])); ?></td>
<td><?= $user['permission']; ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
   <?php }include 'includes/footer.php'; 