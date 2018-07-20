<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/clothing/core/init.php';
$parentID=(int)$_POST['parentID'];
$selected=standardize($_POST['selected']);
$childquery= $db->query("SELECT * FROM categories WHERE parent= '$parentID'");

ob_start();
?>
<option value=""></option>
<?php while($child=mysqli_fetch_assoc($childquery)) :?>
<option value="<?=$child['id']  ;?>"<?= (($selected==$child['id'])?' selected':'');?>><?= $child['categories'] ;?>
</option>
<?php endwhile; ?>
<?= ob_get_clean(); ?>