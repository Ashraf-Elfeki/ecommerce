<?php

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
    include "connect.php";

    if(!(isset($noNavbar))) {
        include $tmp . "navbar.php";
    } 
