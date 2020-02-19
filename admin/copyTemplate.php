
<?php

/*
===========================================
=======    template Page 	   ========
=======    Manage - (Add - Insert - Activate ) - ( Edit - Update ) - Delete     ========
===========================================

*/
	ob_start();        // output Buffering Start
	session_start();	// Start Session 
	$pageTitle = "";	// Page Title

	if (isset($_SESSION['username'])) {
		include 'init.php';
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		
		if ($do == 'Manage') {
		
		} 
		elseif ($do == 'Add') {


		}
		elseif ($do == 'Insert') {

		}
		elseif ($do == 'Activate'){


		} 
		elseif ($do == 'Edit') {

							
		} 
		elseif ($do == 'Update') {


		} 
		elseif ($do == 'Delete') {

		}

		include $tmp."footer.php";
	} 
	else {
		header("location:index.php");
		exit();
	}
	ob_end_flush();