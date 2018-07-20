<!DOCTYPE html>

<html>
<?php
      require_once 'core/init.php';
      include  'includes/head.php';
      include  'includes/navigation.php';
      include  'includes/header.php';
      include  'includes/leftbar.php';
      $sql="SELECT * FROM products WHERE features=1";
      $pshow=$db->query($sql);
?>

<!--main-->

<div class="col-md-8">
<div class="row">
<h2 class="text-center">Featured Products</h2>

<?php while ($features=mysqli_fetch_assoc($pshow)) :?> 
  <div class="col-md-3">
  <h4><?php echo $features['title'];?></h4>
  <img src="<?php echo $features['image']; ?>" alt=<?php echo $features['title'];?> class="img-thumb" />
  <p class="price-list text-danger">List Price: <s><?php echo $features['list_price']; ?></s></p>
  <p class="price">Our price:<?php echo $features['price']; ?></p>
  <button type="button"  class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $features['id']; ?>)">Details</button>
  </div>
<?php endwhile ?>
</div>
</div> 

<?php  
       include  'includes/rightbar.php';
       include  'includes/footer.php';
?>


