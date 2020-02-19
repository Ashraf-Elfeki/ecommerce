<?php

	function lang ( $phrase ) {

		static $lang = array (
			'MESSAGE' => 'welcome bel3araby',
			'ADMIN' => ' arabic Administrator'
		);
		return $lang[$phrase];
	}