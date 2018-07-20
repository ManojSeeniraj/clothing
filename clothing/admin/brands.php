<?php 
   require_once '../core/init.php';
   if(!is_logged_in()){
    login_error_redirect();
 }
   include 'includes/head.php';
   include 'includes/navigation.php';

   $sql="SELECT * FROM brands ORDER BY brand";
   $brand_display=$db->query($sql);
   $errors=array();


   //Edit brand
   if(isset($_GET['edit']) && !empty($_GET['edit'])){
    
    $edit_id=(int)$_GET['edit'];
    $edit_id=standardize($edit_id);
   
    $sql2="SELECT * FROM brands WHERE id='$edit_id'";
    $edit_result=$db->query($sql2);
    $eBrand=mysqli_fetch_assoc($edit_result);

   }


   //Delete brand
   if(isset($_GET['delete'])&& !empty($_GET['delete'])){  
    
    $delete_id=(int)$_GET['delete']; //geting the id of the object
    $delete_id=standardize($delete_id);
    
    $sql="DELETE FROM brands WHERE id='$delete_id'";//deleting in the database
    $delete_fun=$db->query($sql);
    header('Location:brands.php');//refreshing the page
   }

   //add brand
    if(isset($_POST['add_submit'])){

    $brand=standardize($_POST['brand']);
    //enter the brand name
    if($_POST['brand']==''){
        $errors[] .='you have to enter brand!';
    }
    //checkin if alredy exists
    $sql="SELECT * FROM brands WHERE brand='$brand'";

    if(isset($_GET['edit'])){

        $sql="SELECT * FROM brands WHERE brand='$brand' AND id !='$edit_id'";
    }
    $check_brand=$db->query($sql);
    $count=mysqli_num_rows($check_brand);//gives the number of count
    if($count>0){
        $errors[] .='The brand alredy exists!plz enter other Brands';
    }
    if(!empty($errors)){

        echo errorshow($errors);
    }
    else{
        //add to the database
        $sql="INSERT INTO brands(brand) VALUES('$brand')";
 
        if(isset($_GET['edit'])){
            $sql="UPDATE brands SET brand='$brand' WHERE id='$edit_id'";
        }

        $db->query($sql);
        header('Location:brands.php');//php function which refresh the page
    }
   }
?>



<h3 class="text-center">Brands</h4><hr>

<div class="text-center">
<form  class="form-inline" action="brands.php<?php echo ((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
    <div  class="form-group">
    <?php
     $brand_value='';
     if(isset($_GET['edit'])){
         $brand_value=$eBrand['brand'];
         
     }else{
         if(isset($_POST['brand'])){
             $brand_value=standardize($_POST['brand']);
         }
     }
    ?>
    <label for="newproduct"><?php echo ((isset($_GET['edit']))?'Edit ':'Add A '); ?>product:</label>
                                                                                <!-- shows the value entered -->
    <input type="text"  name="brand" id="brand" class="form-control" value="<?php echo $brand_value; ?>">
    <?php if(isset($_GET['edit'])): ?>
        <a href="brands.php"  class="btn btn-danger">Cancle</a>

    <?php endif ;?>
                                                                            
    <input type="submit" name="add_submit" id="submit" value="<?php echo ((isset($_GET['edit']))?'Edit ':'Add Brand'); ?>" class="btn btn-success">
    </div>
</form>
</div><hr>


<table class="table table-bordered table-stripe table-align table-condensed">
<thead>
<th></th>
<th>Brands</th>
<th></span></th>
</thead>
<tbody>
<?php while ($displayBrand=mysqli_fetch_assoc($brand_display)) :?>
<tr>
<td><a href="brands.php?edit= <?php echo $displayBrand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></td>
<td><?php echo $displayBrand['brand']; ?></td>
<td><a href="brands.php?delete=<?php  echo $displayBrand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></td>
<?php endwhile; ?>
</tr>
</tbody>
</table>


<?php include 'includes/footer.php'; ?>