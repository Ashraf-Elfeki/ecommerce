<nav class="navbar navbar-inverse">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="dashboard.php"><?php echo lang('Brand'); ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="categories.php"><?php echo lang('CATEGORIES'); ?> </a></li>
        <li><a href="items.php"><?php echo lang('ITEMS'); ?></a></li>
        <li><a href="members.php"><?php echo lang('MEMBERS'); ?></a></li>
        <li><a href="comments.php"><?php echo lang('COMMENTS'); ?></a></li>
        <li><a href="#"><?php echo lang('STATISTICS'); ?></a></li>
        <li><a href="#"><?php echo lang('LOGS'); ?></a></li>
      </ul>
     
      <ul class="nav navbar-nav navbar-right">
       
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['username']?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../index.php">Visit Shop</a></li>            
            <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>"><?php echo lang('EDIT-PROFILE'); ?></a></li>
            <li><a href="#"><?php echo lang('SETTINGS'); ?></a></li>
            <li><a href="logout.php"><?php echo lang('LOGOUT'); ?></a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>