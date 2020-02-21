<<<<<<< HEAD
<?php 
    ob_start();
    session_start();
    include "init.php";	
    $find= array('-', '^');
    $replace= array(' ', "'");
?>

    <div class="container">
	<h1 class="text-center">
            <?php 
                if (isset($_GET['pagename'])) {
                    echo str_replace($find, $replace, $_GET['pagename']); 
                }else {
                    echo "Online Shop Category";
                }
            ?>	
	</h1>
	<div class="row">
            <?php
                if (isset($_GET['pageid']) && is_numeric($_GET['pageid']) && $_GET['pageid'] > 0 ) {
                    $checkCatID = checkexist('ID','categories',$_GET['pageid']);
                    if($checkCatID){
                        $catID = intval($_GET['pageid']);
                        $approveditems = getAll('*','items','WHERE Approve = 1 AND CatID = '.$catID ,'ORDER BY ItemID DESC');
                        //$approveditems = getApprovedItems('CatID',$catID);
                        if(!empty($approveditems)){
                            foreach ($approveditems as $item) {
                                echo "<div class='col-sm-6 col-md-3'>
                                        <div class='thumbnail item-box'>
                                            <span class='price-tag'>".$item['Price']."</span>
                                            <img class='img-responsive' src='client1.png' alt='product' />
                                            <div class='caption'>
                                                <h3><a href='items.php?itemid=".$item['ItemID']."'>".$item['Name']."</a></h3>
                                                <p>".$item['Description']."</p>
                                                <span class='item-date'>".$item['AddDate']."</span>													
                                            </div>
                                        </div>
                                     </div>";
                            }
                        } else {
                            errorRedirect("No Items Added To This Category till Now !");
                        }
                    } else {
                        errorRedirect("Invalid Category ID ",'index.php');
                    }
                } else {
                    errorRedirect("Invalid Request",'index.php');
                }
            ?>
	</div>
    </div>
<?php 
    include "include/temp/footer.php";
    ob_end_flush();
=======
<?php 
    ob_start();
    session_start();
    include "init.php";	
    $find= array('-', '^');
    $replace= array(' ', "'");
?>

    <div class="container">
	<h1 class="text-center">
            <?php 
                if (isset($_GET['pagename'])) {
                    echo str_replace($find, $replace, $_GET['pagename']); 
                }else {
                    echo "Online Shop";
                }
            ?>	
	</h1>
	<div class="row">
            <?php
                if (isset($_GET['pageid']) && is_numeric($_GET['pageid']) && $_GET['pageid'] > 0 ) {
                    $checkCatID = checkexist('ID','categories',$_GET['pageid']);
                    if($checkCatID){
                        $catID = intval($_GET['pageid']);
                        $approveditems = getAll('*','items','WHERE Approve = 1 AND CatID = '.$catID ,'ORDER BY ItemID DESC');
                        //$approveditems = getApprovedItems('CatID',$catID);
                        if(!empty($approveditems)){
                            foreach ($approveditems as $item) {
                                echo "<div class='col-sm-6 col-md-3'>
                                        <div class='thumbnail item-box'>
                                            <span class='price-tag'>".$item['Price']."</span>
                                            <img class='img-responsive' src='client1.png' alt='product' />
                                            <div class='caption'>
                                                <h3><a href='items.php?itemid=".$item['ItemID']."'>".$item['Name']."</a></h3>
                                                <p>".$item['Description']."</p>
                                                <span class='item-date'>".$item['AddDate']."</span>													
                                            </div>
                                        </div>
                                     </div>";
                            }
                        } else {
                            errorRedirect("No Items Added To This Category till Now !");
                        }
                    } else {
                        errorRedirect("Invalid Category ID ",'index.php');
                    }
                } else {
                    errorRedirect("Invalid Request",'index.php');
                }
            ?>
	</div>
    </div>
<?php 
    include "include/temp/footer.php";
    ob_end_flush();
>>>>>>> c0b1787e8df51c5b21cccea03a9c3fc6284b1282
