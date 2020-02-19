<?php

/*
===========================================
=======    Items Page 	   ========
=======    Manage - (Add - Insert - Activate ) - ( Edit - Update ) - Delete     ========
===========================================

*/
    ob_start();        // output Buffering Start
    session_start();	// Start Session 
    $pageTitle = "Items";	// Page Title

    if (isset($_SESSION['username'])) {
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') {
            $query = '';
            if(isset($_GET['page']) && $_GET['page'] == 'pending') {
                $query = 'WHERE items.Approve = 0';
            }

            $stmt = $con->prepare("SELECT items.* , users.UserName, categories.Name AS Category
                                    FROM items
                                    INNER JOIN users      ON items.UserID = users.UserID 
                                    INNER JOIN categories ON items.CatID  = categories.ID
                                    $query 
                                    ORDER BY ItemID DESC;");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            ?>
            <h3 class="text-center">Manage Items</h3>
            <div class="container text-center">
        <?php 
                if(!empty($rows)){
        ?>
                <div class="table-responsive ">
                        <table class="table table-hover table-striped table-bordered text-center">
                            <tr class="warning"> 
                                <td>ID</td>
                                <td>Name</td>
                                <td>User Name</td>
                                <td>Category</td>				
                                <td>Description</td>
                                <td>Price</td>
                                <td>Manf Country</td>
                                <td>Added Date</td>				
                                <td>Controle</td>		
                            </tr>
                            <?php
                                foreach ($rows as $item) {
                                    echo "<tr>
                                            <td>" . $item['ItemID'] . "</td>
                                            <td>" . $item['Name'] . "</td>
                                            <td>" . $item['UserName'] . "</td>
                                            <td>" . $item['Category'] . "</td>							
                                            <td>" . $item['Description'] . "</td>
                                            <td>" . $item['Price'] . "</td>
                                            <td>" . $item['CountryMade'] . "</td>
                                            <td>" . $item['AddDate'] . "</td>
                                            <td>
                                                <a class='btn btn-info' href='items.php?do=Edit&itemid=" . $item['ItemID'] . "'><i class='far fa-edit fa-lg'></i>Edit</a>
                                                <a class='btn btn-danger confirm' href='items.php?do=Delete&itemid=" . $item['ItemID'] . "'><i class='far fa-times-square fa-lg'></i>Delete</a>";
                                                if ($item['Approve'] == 0) {
                                                        echo "<a class='btn btn-info' href='items.php?do=Approve&itemid=" . $item['ItemID'] . "'><i class='far fa-check-square fa-lg'></i>Approve</a>";
                                                }

                                    echo    "</td>						
                                       </tr>";
                                }

                            ?>
                        </table>

                </div>
        <?php 
                } else {
                    echo "<div class='alert alert-danger'>There is No Items Added Yet !, You Can Add New Item from the down button</div>";
                }
        ?>
                <a class="btn btn-primary btn-lg" href='items.php?do=Add'><i class="fas fa-plus-square"></i>New Item </a>
            </div>

        <?php

        } 
        elseif ($do == 'Add') {
    ?>
            <!-------------------- Add New Item Form -------------------------->
            <h3 class="text-center update-head">Add New Item</h3>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!--   Item Name   -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Item Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" class="form-control" required placeholder="Name of the Item" >
                        </div>
                    </div>
                    <!--   Item Description   -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Item Description</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control" required placeholder="Description of the Item" >
                        </div>
                    </div>
                    <!--  Item Price   -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Item Price</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="price" class="form-control" required placeholder="Price of the Item" >
                        </div>
                    </div>							
                    <!--  Item Country of Made   -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country of Made</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="country" class="form-control" required placeholder="Country of Made of the Item" >
                        </div>
                    </div>

                    <!--  Item Status   -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Item Status</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="status">
                                <option value="0">....</option>		
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Old</option>
                            </select>
                        </div>
                    </div>	

                    <!--  Item Members   -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Item Member</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="item-member">
                                <option value="0">....</option>		
                                <?php
                                    $members = getAll("*","users","WHERE RegStatus != 0");
                                    //$stmt = $con->prepare("SELECT * FROM users WHERE RegStatus != 0 ");
                                    //$stmt->execute();
                                    //$members = $stmt->fetchAll();
                                    foreach ($members as $member) {
                                        echo "<option value='" . $member['UserID'] . "'>" . $member['UserName'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>


                    <!--  Item Category   -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Item Category</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="item-category">
                                <option value="0">....</option>		
                                <?php
                                    $categories = getAll("*","categories","WHERE Parent =0 ");
                                    //$stmt2 = $con->prepare("SELECT * FROM categories");
                                    //$stmt2->execute();
                                    //$categories = $stmt2->fetchAll();
                                    foreach ($categories as $category) {
                                        echo "<option value='" . $category['ID'] . "'>" . $category['Name'] . "</option>";
                                        $childgories = getAll("*","categories","WHERE Parent = {$category['ID']} ");
                                        foreach ($childgories as $child) {
                                            echo "<option value='" . $child['ID'] . "'> ## " . $child['Name'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!--  Item Tags of Made   -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="tags" class="form-control" data-role="tagsinput" placeholder="Type Tags seperated with comma" >
                        </div>
                    </div>

                    <!--   Submit   -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-4">
                            <input type="submit" Value="Add" class="btn btn-primary btn-block btn-lg">
                        </div>
                    </div>
                </form>
            </div>
    <?php
        }
        elseif ($do == 'Insert') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo "<h1 class='text-center'>Add New Item</h1>";
                echo "<div class='container'>";
                $name    	= $_POST['name'];
                $desc    	= $_POST['description'];		
                $price   	= $_POST['price'];
                $country 	= $_POST['country'];
                $status     = $_POST['status'];
                $member  	= $_POST['item-member'];
                $category   = $_POST['item-category'];			
                $tags  		= $_POST['tags'];			
                /* Form Validation */
                $errors = array();
                if (empty($name)) {
                    $errors[] = "You Must Type The Item Name";
                }

                if (empty($desc)) {
                    $errors[] = "You Must Type Item Description";
                }

                if (empty($price)) {
                    $errors[] = "You Must Type Item Price";
                }

                if (empty($country)) {
                    $errors[] = "You Must Type Item Country";
                }

                if ($status == 0 ) {
                    $errors[] = "You Must choose Item Status";
                }					

                if ($member == 0 ) {
                    $errors[] = "You Must choose Item Member";
                }
                if ($category == 0 ) {
                    $errors[] = "You Must choose Category Item";
                }			

                // check if the inserted data is free errors or not 
                if(empty($errors)){

                        $stmt = $con->prepare("Insert INTO items (Name, Description, Price, AddDate, CountryMade, Status, CatID, userID,Tags) VALUES (:xname, :xdesc, :xprice, now(), :xcountry, :xstatus, :xcategory, :xmember, :xtags ) ");
                        $stmt->execute(array(
                                'xname'     => $name,
                                'xdesc'     => $desc, 
                                'xprice'    => $price, 
                                'xcountry'  => $country,
                                'xstatus'   => $status,
                                'xmember'   => $member,
                                'xcategory' => $category,
                                'xtags'		=> $tags
                        ));
                        $count = $stmt->rowCount();

                        //echo "<div class='alert alert-success text-center'>" .  . " </div>";
                        successRedirect("Item Added Successfuly",'items.php');

                }else {
                        foreach ($errors as $error) {
                                //echo  "<div class='alert alert-danger text-center'>" . $error."</div>";
                                errorRedirect($error,'back');	
                        }
                }
            } else {
                errorRedirect("sorry you cann't browse this page Directly",'back');
            }
            echo "</div>";
        } 
        elseif ($do == 'Approve'){
            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";	
                if (isset($_GET['itemid']) && is_numeric($_GET['itemid']) && $_GET['itemid'] > 0 ) { 

                $itemid = intval($_GET['itemid']);
                if (checkexist("ItemID","items",$itemid)){			
                    $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE ItemID = ?");
                    $stmt->execute(array($itemid));
                    successRedirect("Item Activated Successfuly",'items.php');
                } else {
                    errorRedirect("This Item Is Not Exist in DB",'back');
                }
            }
            echo "</div>";

        } 
        elseif ($do == 'Edit') {
            if (isset($_GET['itemid']) && is_numeric($_GET['itemid']) && $_GET['itemid'] > 0 ) { 

                $itemid = intval($_GET['itemid']);
                //$edititem = getAll(" * "," items "," WHERE ItemID = ".$itemid);
                $stmt = $con->prepare("select * FROM items WHERE ItemID = ?");
                $stmt->execute(array($itemid));
                $count = $stmt->rowCount();
                $edititem = $stmt->fetch(); 

                if ($count > 0) {	 	
    ?>
                    <!-------------------- Edit Item Form -------------------------->
                    <h3 class="text-center update-head">Edit The Item</h3>
                    <div class="container">
                        <form class="form-horizontal" action="?do=Update" method="POST">
                            <input type="hidden" name="itemid" value="<?php echo $edititem['ItemID']?>">
                            <!--   Item Name   -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Item Name</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" name="name" class="form-control" required placeholder="Name of the Item" value="<?php echo $edititem['Name'] ?>" >
                                </div>
                            </div>
                            <!--   Item Description   -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Item Description</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" name="description" class="form-control" required placeholder="Description of the Item" value="<?php echo $edititem['Description'] ?>" >
                                </div>
                            </div>
                            <!--  Item Price   -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Item Price</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" name="price" class="form-control" required placeholder="Price of the Item" value="<?php echo $edititem['Price'] ?>" >
                                </div>
                            </div>							
                            <!--  Item Country of Made   -->
                            <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Country of Made</label>
                                    <div class="col-sm-10 col-md-4">
                                            <input type="text" name="country" class="form-control" required placeholder="Country of Made of the Item" value="<?php echo $edititem['CountryMade'] ?>" >
                                    </div>
                            </div>

                        <!--  Item Status   -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Item Status</label>
                                <div class="col-sm-10 col-md-4">
                                    <select name="status">
                                        <option value="1" <?php if($edititem['Status'] == 1) { echo "selected";} ?> >New</option>
                                        <option value="2" <?php if($edititem['Status'] == 2) { echo "selected";} ?> >Like New</option>
                                        <option value="3" <?php if($edititem['Status'] == 3) { echo "selected";} ?> >Used</option>
                                        <option value="4" <?php if($edititem['Status'] == 4) { echo "selected";} ?> >Old</option>
                                    </select>
                                </div>
                            </div>	

                            <!--  Item Members   -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Item Member</label>
                                <div class="col-sm-10 col-md-4">
                                    <select name="item-member">
                                        <?php
                                            $stmt = $con->prepare("SELECT * FROM users WHERE RegStatus != 0 ");
                                            $stmt->execute();
                                            $members = $stmt->fetchAll();
                                            foreach ($members as $member) {
                                                echo "<option value='" . $member['UserID'] . "'";
                                                if($edititem['UserID'] == $member['UserID']) { echo 'selected';} 
                                                echo " >" . $member['UserName'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <!--  Item Category   -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Item Category</label>
                                <div class="col-sm-10 col-md-4">
                                    <select name="item-category">
                                        <?php
                                            $stmt2 = $con->prepare("SELECT * FROM categories");
                                            $stmt2->execute();
                                            $categories = $stmt2->fetchAll();
                                            foreach ($categories as $category) {
                                                echo "<option value='" . $category['ID'] . "'";
                                                if($edititem['CatID'] == $category['ID']) { echo 'selected';}
                                                echo ">" . $category['Name'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!--  Item Tags of Made   -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Tags</label>
                                <div class="col-sm-10 col-md-4">
                                        <input type="text" name="tags" class="form-control" data-role="tagsinput" placeholder="Edit Item Tags" value="<?php echo $edititem['Tags'] ?>" >
                                </div>
                            </div>

                            <!--   Submit   -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-4">
                                    <input type="submit" Value="Update" class="btn btn-primary btn-block btn-lg">
                                </div>
                            </div>
                        </form>

                        <?php
                            $stmt = $con->prepare(" SELECT comments.*, users.UserName AS ItemUser
                                                    FROM comments				
                                                    INNER JOIN users ON comments.UserID = users.UserID
                                                    WHERE ItemID = ?
                                                     ");
                            $stmt->execute(array($itemid));
                            $rows = $stmt->fetchAll();
                            if (! empty($rows)) {
                    ?>
                                <h3 class="text-center">Manage <?php echo $edititem['Name'] ?> Comments</h3>
                                <div class="table-responsive ">
                                    <table class="table table-hover table-striped table-bordered text-center">
                                        <tr class="warning"> 
                                                <td>Comment</td>
                                                <td>Added Date</td>
                                                <td>User Name</td>
                                                <td>Controle</td>		
                                        </tr>
                                        <?php
                                            foreach ($rows as $com) {
                                                echo "<tr>
                                                        <td>" . $com['Comment'] . "</td>
                                                        <td>" . $com['CommentDate'] . "</td>
                                                        <td>" . $com['ItemUser'] . "</td>
                                                        <td>
                                                            <a class='btn btn-info' href='comments.php?do=Edit&comid=" . $com['CID'] . "'>
                                                                <i class='far fa-edit fa-lg'></i>Edit</a>
                                                            <a class='btn btn-danger confirm' href='comments.php?do=Delete&comid=" . $com['CID'] . "'>"
                                                        . "<i class='far fa-times-square fa-lg'></i>Delete</a>";
                                                            if ($com['Status'] == 0) {
                                                                echo "<a class='btn btn-info' href='comments.php?do=Approve&comid=" . $com['CID'] . "'>"
                                                                        . "<i class='far fa-check-square fa-lg'></i>Approve</a>";
                                                            }
                                                echo    "</td>						
                                                     </tr>";
                                            }

                                        ?>
                                    </table>
                                </div>
                    <?php   }   ?>

                    </div>
    <?php
                } else {
                    errorRedirect("This Item is not Exist ",'back');
                }
            } else {
                errorRedirect("Invalid item ID",'back');
            }
        } 
        elseif ($do == 'Update') {
            echo "<h1 class='text-center'>Update The Item</h1>";
            echo "<div class='container'>";
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    
                    $id          = $_POST['itemid'];
                    $itemname    = $_POST['name'];
                    $description = $_POST['description'];
                    $country     = $_POST['country'];
                    $price       = $_POST['price'];
                    $status      = $_POST['status'];
                    $member      = $_POST['item-member'];
                    $category    = $_POST['item-category'];
                    $tags        = $_POST['tags'];

                    /* Form Validation */
                    $errors = array();
                    if (empty($itemname)) {
                        $errors[] = "You Must Type The Item Name";
                    }
                    if (empty($description)) {
                        $errors[] = "You Must Type Item Description";
                    }
                    if (empty($price)) {
                        $errors[] = "You Must Type Item Price";
                    }
                    if (empty($country)) {
                        $errors[] = "You Must Type Item Country";
                    }
                    if(empty($errors)){

                        $stmt = $con->prepare("UPDATE items SET Name = ?, Description = ?, Price = ?, CountryMade = ?, Status = ?, CatID = ?, UserID = ?, Tags = ?  WHERE ItemID = ?");
                        $stmt->execute(array($itemname, $description, $price, $country, $status, $category, $member, $tags, $id));
                        //echo "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " Record Updated</div>";
                        successRedirect("Item Data Updated Successfuly",'items.php');

                    }else {
                        foreach ($errors as $error) {
                            //echo  "<div class='alert alert-danger text-center'>" . $error."</div>";
                            errorRedirect($error,'back');
                        }
                    }
                } else {
                    errorRedirect("sorry you cann't browse this page Directly",'back');
                }
            echo "</div>";
        } 
        elseif ($do == 'Delete') {
            echo "<h1 class='text-center'>Delete Item</h1>";
            echo "<div class='container'>";	
                if (isset($_GET['itemid']) && is_numeric($_GET['itemid']) && $_GET['itemid'] > 0 ) { 
                    $itemid = intval($_GET['itemid']);
                    if (checkexist("ItemID","items",$itemid)){			
                        $stmt = $con->prepare("DELETE FROM items WHERE ItemID = :xitemid");
                        $stmt->bindParam("xitemid",$itemid);
                        $stmt->execute();
                        successRedirect("Item Successfully Deleted","items.php");
                    } else {
                        errorRedirect("This Item Is Not Exist in DB",'back');
                    }
                }
            echo "</div>";	
        }

        include $tmp."footer.php";
    } 
    else {
        header("location:index.php");
        exit();
    }
    ob_end_flush();