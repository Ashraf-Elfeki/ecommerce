<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<title><?php printTitle();?></title>
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="layout/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="layout/css/bootstrap-tagsinput.css">
		<link rel="stylesheet" type="text/css" href="layout/css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="layout/css/jquery.selectBoxIt.css">
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-xVVam1KS4+Qt2OrFa+VdRUoXygyKIuNWUUUBZYv+n27STsJ7oDOHJgfF0bNKLMJF" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="layout/css/frontend.css">
	</head>
	<body>
		<div class="upper-bar">
			<div class="container">
				<?php 
					if (isset($_SESSION['user'])) {
				?>
					<div class="btn-group my-info pull-right ">
						<img class="img-circle img-thumbnail" src="client1.png" alt="">

						<span class="btn btn-default dropdown-toggle " data-toggle='dropdown'><?php echo $sessionUser; ?>
							<span class="caret"></span>
						</span>
						<ul class="dropdown-menu">
							<li><a href="profile.php">My Profile</a></li>
							<li><a href="newad.php">New Item</a></li>
							<li><a href="profile.php#active-items">My Items</a></li>

							<li><a href="profile.php#pending-items">My Pending Items</a></li>
							<li><a href="logout.php">Logout</a></li>		
						</ul>
					</div>

				<?php		
						echo "<span>Welcome <a href='profile.php'>".$sessionUser."</a></span>  ";
						echo "<a href='newad.php'>Add New Ads</a>";
						echo "<a href='logout.php'>Logout</a>";
						if(checkUserStatus($sessionUser)){
							echo "Your Account is Approved From Admin";
						} else {
							echo "Your Account is Pending till now";
						}

					} else {
				?>
					<span class="pull-right">
						<a href="login.php"> Login </a> <strong>|</strong>
						<a href="login.php"> SignUp </a> 
					</span>
				<?php } ?>
			</div>
		</div>
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
				      <a class="navbar-brand" href="index.php">Home Page</a>
			    </div>

			    <!-- Collect the nav links, forms, and other content for toggling -->
			    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			      <ul class="nav navbar-nav navbar-right">
			      	<?php 
			      	$find= array(' ', "'");
			      	$replace= array('-', '^');
			      	$allcats = getAll("*","categories",'WHERE Parent = 0',"ORDER BY ID DESC");
			      			foreach ($allcats as $cat) {
								echo "<li><a href='categories.php?pageid=".$cat['ID']  ."&pagename=" .str_replace($find, $replace, $cat['Name']) . "'>" . $cat['Name']."</a></li>";
							}
			      	?>
			      </ul>
			     
			    </div><!-- /.navbar-collapse -->
		 	 </div><!-- /.container-fluid -->
		</nav>