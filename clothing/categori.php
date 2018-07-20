<?php
      require_once 'core/init.php';
      include  'includes/head.php';
      include  'includes/navigation.php';
      include  'includes/partial.php';
      include  'includes/leftbar.php';

      if(isset($_GET['cat'])){
          $cat_id=standardize($_GET['cat']);
      }else{
          $cat_id=" ";
      }
      $sql="SELECT * FROM products WHERE categories='$cat_id'";
      $pshowcategory=$db->query($sql);

      $category=get_category($cat_id);
?>

<!--main-->

<div class="col-md-8">
<div class="row">
<h2 class="text-center"><?=$category['parent'].' '.$category['child'];?></h2>

<?php while ($features=mysqli_fetch_assoc($pshowcategory)) :?> 
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


