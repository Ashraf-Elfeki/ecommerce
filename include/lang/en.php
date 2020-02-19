<?php

	function lang ( $phrase ) {
		static $lang = array (

		// Navbar links
			'Brand'			 => 'Home',
			'CATEGORIES' 	 => 'Categories',
			'ITEMS'			 => 'Items',
			'MEMBERS' 		 => 'Members',
			'COMMENTS'		 => 'Comments',
			'STATISTICS' 	 => 'Statistics',
			'LOGS' 			 => 'Logs',
			'EDIT-PROFILE'   => 'Edit Profile',
			'SETTINGS' 		 => 'Settings',
			'LOGOUT' 		 => 'Logout'

		);
		return $lang[$phrase];
	}