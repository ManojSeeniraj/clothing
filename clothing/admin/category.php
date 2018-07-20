<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/clothing/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
 }

include 'includes/head.php';
include 'includes/navigation.php';

$sql="SELECT * FROM categories WHERE parent = 0";
$result=$db->query($sql);
$errors=array();
$category='';
$post_parent='';

//Edit category
if(isset($_GET['edit']) &&  !empty($_GET['edit'])){
    $edit_id=(int)$_GET['edit'];
    $edit_id=standardize($edit_id);
    $esql="SELECT * FROM categories WHERE id='$edit_id'";
    $edit_result=$db->query($esql);
    $edit_category=mysqli_fetch_assoc($edit_result);

}


//Delete category
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id=(int)$_GET['delete'];
    $delete_id=standardize($delete_id);
    $dsql="DELETE FROM categories WHERE id='$delete_id'";
    $sql="SELECT *FROM categories WHERE id='$delete_id";
    $Presult=$db->query($slq);
    $category=mysqli_fetch_assoc($Presult);
    if($category['parent']== 0)
    {
        $sql="DELETE FROM categories WHERE parent=$delete_id";
        $db->query($sql);
    }
    $db->query($dsql);
    header('Location:category.php');
}



//Form processing
if(isset($_POST) && !empty($_POST)){
    $post_parent=standardize($_POST['parent']);
    $category=standardize($_POST['category']);
    $sqlform="SELECT * FROM categories WHERE categories= '$category' AND parent= '$post_parent'";
    if(isset($_GET['edit'])){
        $id=$edit_category['id'];
        $sqlform="SELECT * FROM categories WHERE categories= '$category' AND parent= '$post_parent' AND id!='$id'";
    }
    $fresult=$db->query($sqlform);
    $count= mysqli_num_rows($fresult);

    if($category == ''){
        $errors[].='The category cannot be left blank.';
    }

    if($count >0){
        $errors[] .= $category. ' alredy exists.Please choose a new category.';
    }

    if(!empty($errors)){

        $display=errorshow($errors); ?>
        <script>
        jQuery('document').ready(function(){
            jQuery('#errors').html('<?php echo $display; ?>');
        });
        </script>
  <?php  }else{
    $updatesql="INSERT into categories(categories,parent) VALUES('$category','$post_parent')";
    if(isset($_GET['edit'])){
        $updatesql="UPDATE categories SET categories='$category',parent='$post_parent' WHERE id='$edit_id'";
    }
    $db->query($updatesql);
    header('Location:category.php');
  }
}

  $category_value='';
  $parent_value=0;
 if(isset($_GET['edit'])){
     $category_value=$edit_category['categories'];
     $parent_value=$edit_category['parent'];
 }else {
     if(isset($_POST)){
         $category_value=$category;
         $parent_value=$post_parent;
     }
 
    }

?>


<h2 class='text-center'>Categories</h2><hr>


<div class="row">

<!-- Form -->
<div class="col-md-6">
<form class="form" action="category.php<?php echo ((isset($_GET['edit']))? '?edit='.$edit_id :''); ?>" method="post">
<legend><?php echo ((isset($_GET['edit']))? 'Edit ':'Add a ');?>category</legend>
<div id="errors"></div>
<div class="form-group">
<lable for="parent">Parent</lable>
<select class="form-control" name="parent" id="parent">
<option value="0"<?=(($parent_value== 0)?'selected="selected"':'');?>>Parent</option>
<?php while($parent=mysqli_fetch_assoc($result)):?>
<option value="<?=$parent['id'];?>"<?=(($parent_value == $parent['id'])?' selected="selected"': '');?>><?=$parent['categories'];?></option>
<?php endwhile; ?>
</select>
</div>
<div class="form-group">
<lable for="category">Category</lable> 
<input type="text"  class="form-control" name="category" id="category" value="<?= $category_value ;?>">
</div>
<div class="form-group">
<input type="submit" value="<?= ((isset($_GET['edit']))? 'Edit ':'Add '); ?>category" class="btn btn-success"></input>
</div>
</form>
</div>


<!-- Category area-->
<div class="col-md-6">
<table class="table table-bordered">
<thead>
<th>category</th>
<th>parent</th>
<th></th> 
</thead>
<tbody>
<?php 
    $sql="SELECT * FROM categories WHERE parent = 0";
    $result=$db->query($sql);
    while($parent=mysqli_fetch_assoc($result)):
    $parent_id= $parent['id'];
    $sql2="SELECT * FROM categories WHERE parent= $parent_id";
    $cresult=$db->query($sql2);
?>
<tr class="bg-primary">
<td><?php echo $parent['categories']; ?></td>
<td>Parent</td>
<td>
<a href="category.php?edit=<?php echo $parent['id']; ?>" class="btn btn-xs btn-default" ><span class="glyphicon glyphicon-pencil"></a></span>
<a href="category.php?delete=<?php echo $parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></a></span>
</td>
</tr>
<?php while($result_child=mysqli_fetch_assoc($cresult)):?>
<tr class="bg-info">
<td><?php echo $result_child['categories']; ?></td>
<td><?php echo $parent['categories']; ?></td>
<td>
<a href="category.php?edit=<?php echo $result_child['id']; ?>" class="btn btn-xs btn-default" ><span class="glyphicon glyphicon-pencil"></a></span>
<a href="category.php?delete=<?php echo $result_child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></a></span>
</td>
</tr>
<?php endwhile; ?>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>




<?php include 'includes/footer.php'; ?>