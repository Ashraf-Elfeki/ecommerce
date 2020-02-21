<<<<<<< HEAD
<?php	
    ob_start();
    session_start();
    print_r($_SESSION);
    $pageTitle = "Create New Item";
    include "init.php";
    if (isset($_SESSION['user'])) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formErrors = array();
            $name 	= filter_var($_POST['name'],FILTER_SANITIZE_STRING);
            $desc 	= filter_var($_POST['description'],FILTER_SANITIZE_STRING);
            $price 	= "$".filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
            $Country 	= filter_var($_POST['country'],FILTER_SANITIZE_STRING);
            $status 	= filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
            $cat 	= filter_var($_POST['item-category'],FILTER_SANITIZE_NUMBER_INT);
            $tags 	= filter_var($_POST['tags'],FILTER_SANITIZE_STRING);

            if(strlen($name) < 4 ) {
                $formErrors [] = "The Item Name Must be more than 4 characters";
            }
            if(strlen($desc) < 4 ) {
                $formErrors [] = "Item Description Must be more than 4 characters";
            }

            if(empty($price) ) {
                $formErrors [] = "You must set item price";
            }
            
            if(empty($Country) ) {
                $formErrors [] = "You must set item country";
            }
            
            if($status < 0 ) {
                $formErrors [] = "You must select item status";
            }
            
            if($cat < 0) {
                $formErrors [] = "You must select item category";
            }      
            // check if the inserted data is free errors or not 
            if(empty($formErrors)){
                $stmt = $con->prepare("Insert INTO items (Name, Description, Price, AddDate, CountryMade, Status, CatID, userID, Tags) VALUES (:xname, :xdesc, :xprice, now(), :xcountry, :xstatus, :xcategory, :xmember, :xtags ) ");
                $stmt->execute(array(
                        'xname'    => $name,
                        'xdesc'    => $desc, 
                        'xprice'   => $price, 
                        'xcountry' => $Country,
                        'xstatus'  => $status,
                        'xmember'  => $_SESSION['uid'],
                        'xcategory'=> $cat,
                        'xtags'	   => $tags
                ));
                if ($stmt) {
                    $successMsg = "Item Added Successfuly, It will approved within 24 hours";
                } else{
                    errorRedirect("Item not added to the website",'back');
                }	
            }
            
        }else {
            errorRedirect("You Can't access this page directly",'back');
        }
        ?>
        <h1 class="text-center">Create New Item</h1>
        <div class='create-ad block'>
                <div class="container">
                        <div class="panel panel-info">
                                <div class="panel-heading">Create New Item</div>
                                <div class="panel-body">
                                    <?php if(checkUserStatus($_SESSION['user'])){ ?>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <form class="form-horizontal addItemForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                                                    <!--   Item Name   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Item Name</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <input type="text" name="name" class="form-control live" data-class=".live-name" required placeholder="Name of the Item" pattern=".{4,}" title="Item Name at least 4 chars">
                                                            </div>
                                                    </div>
                                                    <!--   Item Description   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Item Description</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <input type="text" name="description" class="form-control live" data-class=".live-desc" required placeholder="Description of the Item" pattern=".{10,}" title="Item Description at least 10 chars" >
                                                            </div>
                                                    </div>
                                                    <!--  Item Price   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Item Price</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <input type="text" name="price" class="form-control live" data-class=".live-price" required placeholder="Price of the Item" title="Item Price In Dollar" >
                                                            </div>
                                                    </div>							
                                                    <!--  Item Country of Made   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Made IN</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <input type="text" name="country" class="form-control" required placeholder="Country of Made of the Item" title="Where is this item made in ?" >
                                                            </div>
                                                    </div>

                                    <!--  Item Status   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Item Status</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <select name="status" title="Click To Select One" required>
                                                                    <option value="">....</option>		
                                                                    <option value="1">New</option>
                                                                    <option value="2">Like New</option>
                                                                    <option value="3">Used</option>
                                                                    <option value="4">Old</option>
                                                                </select>
                                                            </div>
                                                    </div>	

                                                    <!--  Item Category   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Item Category</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <select name="item-category" title="Click To Select One" required>
                                                                    <option value="">....</option>		
                                                                    <?php
                                                                        $stmt2 = $con->prepare("SELECT * FROM categories");
                                                                        $stmt2->execute();
                                                                        $categories = $stmt2->fetchAll();
                                                                        foreach ($categories as $category) {
                                                                            echo "<option value='" . $category['ID'] . "'>" . $category['Name'] . "</option>";
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                    </div>

                                                    <!--  Item Tags of Made   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Tags</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <input type="text" name="tags" class="form-control" data-role="tagsinput" placeholder="Type Tags seperated with comma" >
                                                            </div>
                                                    </div>

                                                    <!--   Submit   -->
                                                    <div class="form-group form-group-lg">
                                                            <div class="col-sm-offset-3 col-sm-9">
                                                                <input type="submit" Value="Add" class="btn btn-primary btn-block btn-lg">
                                                            </div>
                                                    </div>

                                                </form>							
                                            </div>
                                            <div class="col-md-4">
                                                <div class='thumbnail item-box live-preview'>
                                                    <span class='price-tag'>$<span class="live-price">Price</span></span>
                                                    <img class='img-responsive' src='client1.png' alt='product' />
                                                    <div class='caption'>
                                                            <h3 class="live-name">Item Name</h3>
                                                            <p class="live-desc">Item Description</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else {
                                                echo  '<div class="alert alert-info"> Sorry, You Can\'t add new Item untill your account activated from admin</div>';
                                            }
                                     ?>
                                </div>
                        </div>

                        <?php 
                                if (!empty($formErrors)) {
                                        foreach ($formErrors as $error) {
                                                        echo "<div class='alert alert-danger'>".$error."</div>";
                                                //errorRedirect($error,'back');
                                        }	
                                }

                                if (isset($successMsg)) {
                                echo "<div class='alert alert-success'>" . $successMsg . "</div>";				
                        }	
                        ?>

                </div>
        </div>
        <?php
    } else {
        header('location:login.php');
        exit();
    }

    include "include/temp/footer.php";
=======
<?php	
    ob_start();
    session_start();
    print_r($_SESSION);
    $pageTitle = "Create New Item";
    include "init.php";
    if (isset($_SESSION['user'])) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formErrors = array();
            $name 	= filter_var($_POST['name'],FILTER_SANITIZE_STRING);
            $desc 	= filter_var($_POST['description'],FILTER_SANITIZE_STRING);
            $price 	= "$".filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
            $Country 	= filter_var($_POST['country'],FILTER_SANITIZE_STRING);
            $status 	= filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
            $cat 	= filter_var($_POST['item-category'],FILTER_SANITIZE_NUMBER_INT);
            $tags 	= filter_var($_POST['tags'],FILTER_SANITIZE_STRING);

            if(strlen($name) < 4 ) {
                $formErrors [] = "The Item Name Must be more than 4 characters";
            }
            if(strlen($desc) < 4 ) {
                $formErrors [] = "Item Description Must be more than 4 characters";
            }

            if(empty($price) ) {
                $formErrors [] = "You must set item price";
            }
            
            if(empty($Country) ) {
                $formErrors [] = "You must set item country";
            }
            
            if($status < 0 ) {
                $formErrors [] = "You must select item status";
            }
            
            if($cat < 0) {
                $formErrors [] = "You must select item category";
            }      
            // check if the inserted data is free errors or not 
            if(empty($formErrors)){
                $stmt = $con->prepare("Insert INTO items (Name, Description, Price, AddDate, CountryMade, Status, CatID, userID, Tags) VALUES (:xname, :xdesc, :xprice, now(), :xcountry, :xstatus, :xcategory, :xmember, :xtags ) ");
                $stmt->execute(array(
                        'xname'    => $name,
                        'xdesc'    => $desc, 
                        'xprice'   => $price, 
                        'xcountry' => $Country,
                        'xstatus'  => $status,
                        'xmember'  => $_SESSION['uid'],
                        'xcategory'=> $cat,
                        'xtags'	   => $tags
                ));
                if ($stmt) {
                    $successMsg = "Item Added Successfuly, It will approved within 24 hours";
                } else{
                    errorRedirect("Item not added to the website",'back');
                }	
            }
            
        }else {
            errorRedirect("You Can't access this page directly",'back');
        }
        ?>
        <h1 class="text-center">Create New Item</h1>
        <div class='create-ad block'>
                <div class="container">
                        <div class="panel panel-info">
                                <div class="panel-heading">Create New Item</div>
                                <div class="panel-body">
                                    <?php if(checkUserStatus($_SESSION['user'])){ ?>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <form class="form-horizontal addItemForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                                                    <!--   Item Name   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Item Name</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <input type="text" name="name" class="form-control live" data-class=".live-name" required placeholder="Name of the Item" pattern=".{4,}" title="Item Name at least 4 chars">
                                                            </div>
                                                    </div>
                                                    <!--   Item Description   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Item Description</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <input type="text" name="description" class="form-control live" data-class=".live-desc" required placeholder="Description of the Item" pattern=".{10,}" title="Item Description at least 10 chars" >
                                                            </div>
                                                    </div>
                                                    <!--  Item Price   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Item Price</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <input type="text" name="price" class="form-control live" data-class=".live-price" required placeholder="Price of the Item" title="Item Price In Dollar" >
                                                            </div>
                                                    </div>							
                                                    <!--  Item Country of Made   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Made IN</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <input type="text" name="country" class="form-control" required placeholder="Country of Made of the Item" title="Where is this item made in ?" >
                                                            </div>
                                                    </div>

                                    <!--  Item Status   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Item Status</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <select name="status" title="Click To Select One" required>
                                                                    <option value="">....</option>		
                                                                    <option value="1">New</option>
                                                                    <option value="2">Like New</option>
                                                                    <option value="3">Used</option>
                                                                    <option value="4">Old</option>
                                                                </select>
                                                            </div>
                                                    </div>	

                                                    <!--  Item Category   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Item Category</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <select name="item-category" title="Click To Select One" required>
                                                                    <option value="">....</option>		
                                                                    <?php
                                                                        $stmt2 = $con->prepare("SELECT * FROM categories");
                                                                        $stmt2->execute();
                                                                        $categories = $stmt2->fetchAll();
                                                                        foreach ($categories as $category) {
                                                                            echo "<option value='" . $category['ID'] . "'>" . $category['Name'] . "</option>";
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                    </div>

                                                    <!--  Item Tags of Made   -->
                                                    <div class="form-group form-group-lg">
                                                            <label class="col-sm-3 control-label">Tags</label>
                                                            <div class="col-sm-10 col-md-9">
                                                                <input type="text" name="tags" class="form-control" data-role="tagsinput" placeholder="Type Tags seperated with comma" >
                                                            </div>
                                                    </div>

                                                    <!--   Submit   -->
                                                    <div class="form-group form-group-lg">
                                                            <div class="col-sm-offset-3 col-sm-9">
                                                                <input type="submit" Value="Add" class="btn btn-primary btn-block btn-lg">
                                                            </div>
                                                    </div>

                                                </form>							
                                            </div>
                                            <div class="col-md-4">
                                                <div class='thumbnail item-box live-preview'>
                                                    <span class='price-tag'>$<span class="live-price">Price</span></span>
                                                    <img class='img-responsive' src='client1.png' alt='product' />
                                                    <div class='caption'>
                                                            <h3 class="live-name">Item Name</h3>
                                                            <p class="live-desc">Item Description</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else {
                                                echo  '<div class="alert alert-info"> Sorry, You Can\'t add new Item untill your account activated from admin</div>';
                                            }
                                     ?>
                                </div>
                        </div>

                        <?php 
                                if (!empty($formErrors)) {
                                        foreach ($formErrors as $error) {
                                                        echo "<div class='alert alert-danger'>".$error."</div>";
                                                //errorRedirect($error,'back');
                                        }	
                                }

                                if (isset($successMsg)) {
                                echo "<div class='alert alert-success'>" . $successMsg . "</div>";				
                        }	
                        ?>

                </div>
        </div>
        <?php
    } else {
        header('location:login.php');
        exit();
    }

    include "include/temp/footer.php";
>>>>>>> c0b1787e8df51c5b21cccea03a9c3fc6284b1282
    ob_end_flush();