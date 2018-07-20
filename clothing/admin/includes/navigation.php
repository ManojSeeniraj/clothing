<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <a href="/clothing/admin/index.php" class="navbar-brand">Mano's clothing Admin</a>
            <ul class="nav navbar-nav">    


            <li><a href="brands.php">Brands</a></li>  
            <li><a href="category.php">Category</a></li> 
            <li><a href="products.php">Products</a></li>   
            <li><a href="archived.php">Archived</a></li> 

            <?php if(has_permission('admin')): ?>
            <li><a href="users.php">User</a></li> 
            <?php endif; ?>

            <li class="drop-down">
            <a href="#" class="dropdown-toggle"  data-toggle="dropdown">Hello <?=  $user_data['first'] ?>!
            <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
            <li><a href="changePassword.php">Change Password</a></li>  
            <li><a href="logout.php">Logout</a></li>
            
            
            </ul>
            </li>
            
            
            <!--  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['categories'];?> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
            
                <li><a href="#"><?php echo $child['categories'];?></a></li>
            
               
</ul>
</li>-->
          
        </div>
    </nav>