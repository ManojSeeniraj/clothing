<!--Top Navigation Bar-->


<?php 
   $sql= "SELECT * FROM categories WHERE parent=0";
   $pquery= $db->query($sql); //getting parents with id 0;
?>
<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <a href="index.php" class="navbar-brand">Mano's clothing</a>
            <ul class="nav navbar-nav">
          
          <!--All stored in $parent-->
            <?php while ($parent=mysqli_fetch_assoc($pquery)) : ?>     
          <!--Stores the id of the parent element-->
            <?php 
                $parent_id= $parent ['id'];  
                $sql2="SELECT * FROM categories WHERE parent='$parent_id'";
                $cquery= $db->query($sql2);
            ?>
            
                  
                 
            
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['categories'];?> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
            <?php while ($child=mysqli_fetch_assoc($cquery)) :?>
            <li><a href="categori.php?cat=<?= $child['id'];?>"><?php echo $child['categories'];?></a></li>
                <?php endwhile ?>
               
</ul>
</li>
          <?php endwhile ?>

          
        </div>
    </nav>
