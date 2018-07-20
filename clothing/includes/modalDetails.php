<!--modal details-->


<?php
require_once '../core/init.php';
$id=$_POST['id'];
$id =(int)$id;
$sql="SELECT * FROM products WHERE id= '$id'";
$result= $db->query($sql);
$product=mysqli_fetch_assoc($result);
$brand_id=$product['brand'];
$sql="SELECT brand FROM brands WHERE id='$brand_id'";
$brand_query=$db->query($sql);
$brand=mysqli_fetch_assoc($brand_query);
$brandsize=$product['size'];
$brandsize=rtrim($brandsize,','); 
$sizearray_size=explode(',',$brandsize);
?>
<?php ob_start(); ?>
<div class="modal fade details-1" id="details-modal"  role="dialog" tabindex="-1" aria-labelledby="details" aria-hidden="true">
 <div class="modal-dialog modal-lg">
   <div class="modal-content">
      <div class="modal-header">
       <button class="close" type="button" onclick="modalclose()" aria-lable="close">
       <span class="area-hidden">&times;</span>
       </button>
       <h4 class="modal-title text-center"><?php  echo $product['title']; ?></h4>
      
   </div>
    <div class="modal-body">
     <div  class="container-fluid">
       <div class="row">
       <span id="modal_error" class="bg-danger"></span>
        <div class="col-sm-6">
          <div class="center-block">
            <img src="<?php echo $product['image']; ?>" alt="<?php  echo $product['title']; ?>" class="details img-responsive" />
          </div>
      </div>
   <div class="col-sm-6">
        <h4> Details</h4>
        <p><?php echo nl2br($product['discription']); ?></p>
        <hr>
        <p>Prize:<?php  echo $product['price']; ?></p>
        <p>Brand:<?php echo $brand['brand']; ?></p>

        
       
        <form action="add_cart.php" method="post" id="add_cart_form">
        <input type="hidden" name="product_id" value="<?= $id ;?>">
        <input type="hidden" name="available" id="available" value="">
        <div class="form-group">
        <div class="col-xs-3"><lable for="quantity">Quantity:</lable>
        <input type="number"  class="form-control" id="quantity" name="quantity" min="0"></div><br><div class="col-xs-9"></div><br><br>
        
        <div class="form-group">
        <lable for="size">Size:</lable>
        <select name="size" id="size" class="form-control">
        <option value=""></option>
        <?php 
          foreach($sizearray_size as $string){
            $sizearray_size= explode(':',$string);
            $size=$sizearray_size[0];
            $available=$sizearray_size[1];
            echo '<option value="'.$size.'" data-available="'.$available.'">'.$size.'('.$available." Available)</option>";
        
          }?>
        </select>
        </div>
        
        
        </form>
   </div>
</div>
</div>
</div>

<div class="modal-footer">
<button class="btn btn-default" onclick="modalclose()">close</button>
<button class="btn btn-warning" onclick="add_to_cart(); return false;"><span class="glyphicon glyphicon-add-to-cart"></span>Add to Cart</button>
  </div>
  </div>
  </div>
</div>
<script>

jQuery('#size').change(function(){

  var available=jQuery('#size option:selected').data("available");
  jQuery('#available').val(available);
});


function modalclose(){

  jQuery('#details-modal').modal('hide');
  setTimeout(function() {

    jQuery('#details-modal').remove();
    
  }, 500);
}


</script>


<?php echo ob_get_clean(); ?>