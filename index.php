<?php
echo phpinfo();
    ob_start();
    session_start();
    $pageTitle = "Home";
    include "init.php";
?>
    <div class="container">
        <h1 class="text-center">Home</h1>
        <div class="row">
            <?php
                $approveditems = getAll('*','items','WHERE Approve = 1','ORDER BY ItemID DESC');
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
            ?>
        </div>
    </div>
<?php
    include "include/temp/footer.php";
    ob_end_flush();