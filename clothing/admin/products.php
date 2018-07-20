
<?php
 require_once $_SERVER['DOCUMENT_ROOT'].'/clothing/core/init.php';
 if(!is_logged_in()){
    login_error_redirect();
 }
 include 'includes/head.php';
 include 'includes/navigation.php';
 $dbpath='';

 //delete category
 if(isset($_GET['remove'])){
     $id=standardize($_GET['remove']);
     $db->query("UPDATE products SET deleted=1 WHERE id='$id'");
     header('Location:products.php');
 }

 if(isset($_GET['add']) || isset($_GET['edit'])){
 $brun=$db->query("SELECT * FROM brands ORDER BY brand");
 $parentdb=$db->query("SELECT * FROM categories WHERE parent=0 ORDER BY categories");

 $title=((isset($_POST['title']) && $_POST['title']!='')?standardize($_POST['title']) :'' );
 $brand=((isset($_POST['brand']) && $_POST['brand']!='')?standardize($_POST['brand']) :'' );
 $parent=((isset($_POST['parent']) && $_POST['parent']!='')?standardize($_POST['parent']) :'' );
 $category=((isset($_POST['child']) && $_POST['child']!='')?standardize($_POST['child']) : '');
 $price=((isset($_POST['price']) && $_POST['price']!='')?standardize($_POST['price']) : '');
 $list_price=((isset($_POST['listprice']) && $_POST['listprice']!='')?standardize($_POST['listprice']) : '');
 $discription=((isset($_POST['discription']) && $_POST['discription']!='')?standardize($_POST['discription']) : '');
 $sizes=((isset($_POST['sizes']) && $_POST['sizes']!='')?standardize($_POST['sizes']) : '');
 $sizes=rtrim($sizes,',');
 $saved_image='';

 if(isset($_GET['edit'])){
    $edit_id=(int)$_GET['edit'];
    $sql= $db->query("SELECT * FROM products WHERE id='$edit_id'");
    $products=mysqli_fetch_assoc($sql);
    if (isset($_GET['remove_image'])) {
         
        $image_url=$_SERVER['DOCUMENT_ROOT'].$products['image'];echo $image_url;
        unlink($image_url);
        $db->query("UPDATE products SET image='' WHERE id='$edit_id'");
        header('Location:products.php?edit='. $edit_id);
        
    }
    $category=((isset($_POST['child']) && $_POST['child']!='')?standardize($_POST['child']) : $products['categories']);
    $title=$products['title'];
    $title=((isset($_POST['title']) && $_POST['title']!='')?standardize($_POST['title']) :$products['title'] );
    $brand=((isset($_POST['brand']) && $_POST['brand']!='')?standardize($_POST['brand']) : $products['brand']);
    $parentQuery=$db->query("SELECT * FROM categories WHERE id='$category'");
    $parentResults=mysqli_fetch_assoc($parentQuery);
    $parent=((isset($_POST['parent']) && $_POST['parent']!='')?standardize($_POST['parent']) : $parentResults['parent']);
    $price=((isset($_POST['price']) && $_POST['price']!='')?standardize($_POST['price']) :$products['price']);
    $list_price=((isset($_POST['listprice']) && $_POST['listprice']!='')?standardize($_POST['listprice']) :$products['list_price']);
    $sizes=((isset($_POST['sizes']) && $_POST['sizes']!='')?standardize($_POST['sizes']) :$products['size']);
    $sizes=rtrim($sizes,',');
    $discription=((isset($_POST['discription']) && $_POST['discription']!='')?standardize($_POST['discription']) :$products['discription']);
    $saved_image= (($products['image']!='')?$products['image']:'');
    $dbpath=$saved_image;
}

 if(!empty($sizes)){
    $sizeString=standardize($sizes);
    $sizeString=rtrim($sizeString,',');
    $sizeArray=explode(',',$sizeString);
    $sArray=array();
    $qArray=array();
    foreach($sizeArray as $ss){
        $s= explode(':',$ss);
        $sArray[]=$s[0];
        $qArray[]=$s[1];
    }
}else{
    $sizesArray= array();
}

 if($_POST){
    $errors=array();
    $required=array('title','brand','price','parent','child','sizes');
    foreach($required as $feild){

    if($_POST[$feild]== ''){
        $errors[] = 'All Feilds With and Astrisk are required.';
        break;
    }
 }
 if(!empty($_FILES)){
     var_dump($_FILES);
     $photo=$_FILES['photo'];
     $name=$photo['name'];
     $nameArray=explode('.',$name);
     $fileName=$nameArray[0];
     $fileExt=$nameArray[1];
     $mime=explode('/',$photo['type']);
     $mimeType=$mime[0];
     $mimeExt=$photo[1];
     $tempLoc=$photo['tmp_name'];
     $fileSize=$photo['size'];
     $allowed=array('png','jpg','jpeg','gif');
     $uploadName=md5(microtime()).'.'.$fileExt;
     $uploadPath= BASEURL.'/images/products/'.$uploadName;
     $dbpath='/clothing/images/products/'.$uploadName;
     if($mimeType!= 'image'){

        $errors[] ='The file must be an image.';
     }
     if(!in_array($fileExt,$allowed)){

        $errors[] ='The file extention must be a png,jpg, jpeg , or gif.';
     }
     if($fileSize > 15000000){
       $errors[]='The files size must be under 15MB.';
     }
     if($fileExt!= $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
         $errors[] = 'File extension does not match the file.';
     }
 }
 if(!empty($errors)){
     echo errorshow($errors);
 }
 else {
     //upload into the database 
     if(!empty($_FILES)){
     move_uploaded_file($tempLoc,$uploadPath);
     }
     $insertSql="INSERT INTO products(`title`,`price`,`list_price`,`brand`,`categories`,`size`,`image`,`discription`) 
     VALUES ('$title','$price','$list_price','$brand','$category','$sizes','$dbpath','$discription')";

     if(isset($_GET['edit'])){

        $insertSql="UPDATE products SET
                   `title`='$title', 
                   `price`='$price', 
                   `list_price`='$list_price',
                   `brand`='$brand', 
                   `categories`='$category',
                   `size`='$sizes',
                   `image`='$dbpath',
                   `discription`='$discription' 
                    WHERE id='$edit_id'";
     }
     $db->query($insertSql);
     header('Location:products.php');
 }
}

?>
<h2 class="text-center"><?= ((isset($_GET['edit']))?'Edit':'Add');?> Products</h2><hr>

<form action="products.php?<?= ((isset($_GET['edit']))?'edit='.$edit_id:'add=1') ;?>" method="POST" enctype="multipart/form-data">

<div class="form-group col-md-3">
<lable for="title">Title*:</lable>
<input type="text" name="title" id="title" class="form-control" value="<?= $title; ?>" >
</div>

<div class="form-group col-md-3">
<lable for="brands">Brands*:</lable>
<select class="form-control" id="brand" name="brand">
<!--if northing is there in the box allow to select -->
<option value=""<?= (($brand=='')?' selected':'') ;?>> </option>
<?php while($b=mysqli_fetch_assoc($brun)):  ?>
<option value="<?php echo $b['id'];?>" <?= (($brand== $b['id'])?' selected':'') ;?>><?= $b['brand']; ?></option>
<?php endwhile; ?>
</select>
</div>


<div class="form-group col-md-3">
<lable for="parent">Parent Category*:</lable>
<select class="form-control" name='parent' id="parent">
<option value=""<?= (($parent=='')?' selected':'') ;?>> </option>
<?php while($p=mysqli_fetch_assoc($parentdb)) :?>
<option value="<?= $p['id'];?>"<?= (($parent==$p['id'])?'selected':''); ?>><?= $p['categories'];  ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="form-group col-md-3">
<lable for="child">Child Category*:</lable>
<select name="child" id="child" class="form-control">
</select>
</div>

<div class="form-group col-md-3">
<lable for="price">Price*:</lable>
<input type="text" id="price" name="price" class="form-control" value="<?= $price; ?>">
</div>

<div class="form-group col-md-3">
<lable for="price">List Price*:</lable>
<input type="text" id="listprice" name="listprice" class="form-control" value="<?= $list_price; ?>">
</div>

<div class="form-group col-md-3">
<lable for="size">Quality & Size*:</lable>
<button  class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Click to Add</button>
</div>

<div class="form-group col-md-3">
<lable for="preview">Quality & Size</lable>
<input type="text" id="sizes" name="sizes" class="form-control" value="<?= $sizes; ?>" readonly>
</div>

<div class="form-group col-md-6">
<?php if($saved_image !=''): ?>
<div class="saved-image"><img src="<?php echo $saved_image; ?>" alt="saved image" /><br>
<a href="products.php?remove_image=1 & edit=<?= $edit_id;?>"  class="text-danger" >Delete Image</a>
</div>
<?php else: ?>
<lable for="photo">Photo:</lable>
<input type="file" id="photo" name="photo" class="form-control">
<?php endif; ?>
</div>

<div class="form-group col-md-6">
<lable for="discription">Discription</lable>
<textarea rows="6" id="discription" class="form-control" name="discription" ><?= $discription; ?></textarea>
</div>

<div class="form-group pull-right">
<a href="products.php" class="btn btn-danger">Cancel</a>
<input type="submit" value="<?= ((isset($_GET['edit']))?'Edit':'Add');?> product" class=" btn btn-success">
<div class="clearfix"></div>
</div>

</form>

<!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="sizesModalLabel">Quality & Size</h4>
      </div>
      <div class="modal-body">
      <div class="container-fluid">
        <?php for($i=1;$i <=12;$i++) :?>
        <div class="form-group col-md-4">
        <lable for="size<?= $i;?>">Sizes</lable>
        <input type="text" id="size<?= $i;?>" name="size<?= $i; ?>" class="form-control" value="<?= ((!empty($sArray[$i-1]))?$sArray[$i-1]:''); ?>">  
        </div>

        <div class="form-group col-md-2">
        <lable for="qty<?= $i;?>">Quantity</lable>
        <input type="number" id="qty<?= $i;?>" name="qty<?= $i; ?>" min="0" class="form-control" value="<?= ((!empty($qArray[$i-1]))?$qArray[$i-1]:''); ?>">  
        </div>

        <?php endfor; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSize();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>







                                            <!--Frount page of product-->



 <?php }
 else {

 $sql="SELECT * FROM products WHERE  deleted= 0";
 $delete_result=$db->query($sql);

 if(isset($_GET['features'])){
     $id=(int)$_GET['id'];
     $features=(int)$_GET['features'];
     $feature_sql="UPDATE products SET features='$features'  WHERE id='$id'";
     $featuresql=$db->query($feature_sql);
     header('Location:products.php');

 }
?>

<h3 class="text-center">Products</h3>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-button">Add Product</a><div class="clearfix"></div>
<hr>

<table class="table table-bordered table-condenced table-striped">
<thead>
<th></th>
<th>Product</th>
<th>Prize</th>
<th>Category</th>
<th>Features</th>
<th>sold</th>
</thead>
<tbody>
<tr>
<?php while($product=mysqli_fetch_assoc($delete_result)) :
    
    $childID=$product['categories'];//12
    $catQL="SELECT * FROM categories WHERE id='$childID'";
    $result=$db->query($catQL);
    $child=mysqli_fetch_assoc($result);//pants
    $parentId=$child['parent'];
    $psql="SELECT * FROM categories WHERE id='$parentId'";
    $presult=$db->query($psql);
    $parent=mysqli_fetch_assoc($presult);//women
    $category=$parent['categories'].'~'.$child['categories'];    
    ?>
<td>
<a href="products.php?edit=<?php echo $product['id'];?>" class="btn btn-xs btn-default" ><span class="glyphicon glyphicon-pencil"></a></span>
<a href="products.php?remove=<?php echo $product['id'];?>" class="btn btn-xs btn-default" ><span class="glyphicon glyphicon-remove"></a></span>
</td>
<td><?= $product['title'];?></td>
<td><?= money($product['price']) ;?></td>
<td><?= $category ;?></td>
<td><a href="products.php?features=<?=(($product['features']==0)?'1':'0');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?= (($product['features']==1)?'minus':'plus') ;?>"></span>
</a>&nbsp <?= (($product['features']==1)? 'Featured product':'Hidden') ;?>
</td>
<td>0</td>
</tr>
<?php endwhile; ?>
</tbody>


</table>




<?php  } include 'includes/footer.php';?>

<script>
jQuery('document').ready(function () {
    get_child_option('<?=$category;?>');         
});

</script>
