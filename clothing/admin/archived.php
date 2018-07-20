<?php
 require_once $_SERVER['DOCUMENT_ROOT'].'/clothing/core/init.php';
 if(!is_logged_in()){
    login_error_redirect();
 }
 include 'includes/head.php';
 include 'includes/navigation.php';

 $sql="SELECT * FROM products WHERE  deleted= 1";
 $delete_result=$db->query($sql);
 
 if(isset($_GET['refresh'])){
     $refresh=standardize($_GET['refresh']);
     $db->query("UPDATE products SET deleted=0 WHERE id='$refresh'");
     header('Location:products.php');
 }
 
 ?>
 
 <h3 class="text-center">Archieved Products</h3><hr>
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
<a href="archived.php?refresh=<?php echo $product['id'];?>" class="btn btn-xs btn-default" ><span class="glyphicon glyphicon-refresh"></a></span>
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



 <?php include 'includes/footer.php'; ?>