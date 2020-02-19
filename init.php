<?php
	
    ini_set('display_errors', 'on');
    error_reporting(E_ALL);
    include "admin/connect.php";
    $sessionUser= '';
    if (isset($_SESSION['user'])) {
            $sessionUser = $_SESSION['user'];
    }
    // Routes
    $css  = "layout/css/";
    $js   = "layout/js/";
    $tmp  = "include/temp/";
    $lang = "include/lang/";
    $fun  = "include/func/";

    // Including Import Files
    include $fun . "functions.php";
    include_once $lang . "en.php";	
    include $tmp . "header.php";
