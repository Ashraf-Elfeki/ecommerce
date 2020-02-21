<<<<<<< HEAD
<?php	
    ob_start();
    session_start();
    $pageTitle = "My Profile";
    include "init.php";
    if (isset($_SESSION['user'])) {
        //$userinfo = getAll('*','users','WHERE UserName='."'".$_SESSION['user']."'");
        //print_r($userinfo)
        $getUserInfo = $con->prepare("SELECT * FROM users WHERE UserName = ?");
        $getUserInfo->execute(array($sessionUser));
        $userinfo = $getUserInfo->fetch();
?>
        <h1 class="text-center">My Profile</h1>
        <div class='information block'>
                <div class="container">
                        <div class="panel panel-info">
                                <div class="panel-heading">My Information</div>
                                <div class="panel-body">
                                        <ul class="list-unstyled">
                                                <li>
                                                        <i class="fas fa-unlock-alt fa-fw"></i>
                                                        <span>User Name </span> :
                                                        <?php echo $userinfo['UserName']?>
                                                </li>
                                                <li>
                                                        <i class="fas fa-user fa-fw"></i>
                                                        <span>Email</span> :
                                                        <?php echo $userinfo['Email']?>
                                                </li>
                                                <li>
                                                        <i class="far fa-envelope fa-fw"></i>
                                                        <span>Full Name</span> :
                                                        <?php echo $userinfo['FullName']?>
                                                </li>						
                                                <li>
                                                        <i class="fas fa-calendar-day fa-fw"></i>
                                                        <span>Registerd Date</span> :
                                                        <?php echo $userinfo['Date']?>
                                                </li>												
                                                <li>
                                                        <i class="fas fa-user-tag fa-fw"></i>
                                                        <span>Favorite Categories </span> :			
                                                </li>						
                                        </ul>
                                        <a class="btn btn-info" href="#">Edit Info</a>
                                </div>
                        </div>
                </div>
        </div>

        <div id="active-items" class='my-ads block'>
            <div class="container">
                <div class="panel panel-info">
                    <div class="panel-heading">My Items</div>
                    <div class="panel-body">
                        <?php
                            $useritems = getAll('*','items','WHERE Approve = 1 AND UserID='.$userinfo['UserID']);
                            //	$useritems = getApprovedItems('UserID',$userinfo['UserID']);
                            if(!empty($useritems)){
                                    echo "<div class='row'>";
                                    foreach ($useritems as $item) {
                                            echo "<div class='col-sm-6 col-md-3'>
                                                            <div class='thumbnail item-box'>
                                                            <span class='price-tag'>".$item['Price']."</span>
                                                                    <img class='img-responsive img-thumbnail' src='client1.png' alt='product' />
                                                                    <div class='caption'>
                                                                            <h3><a href='items.php?itemid=".$item['ItemID']."'>".$item['Name']."</a></h3>
                                                                            <p>".$item['Description']."</p>
                                                                            <span class='item-date'>".$item['AddDate']."</span>					
                                                                    </div>
                                                            </div>
                                                    </div>";
                                    }
                            echo "</div>";	
                            } else {
                                echo "<div class='alert-danger'>No Items Added To This Category till Now !, You can add one from <a href='newad.php'>here</a></div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="pending-items" class='my-ads block'>
            <div class="container">
                <div class="panel panel-info">
                    <div class="panel-heading">My Pending Items </div>
                    <div class="panel-body">
                        <?php
                            $useritems = getAll('*','items','WHERE Approve = 0 AND UserID='.$userinfo['UserID']);
                            //$useritems = getPendingItems('UserID',$userinfo['UserID']);
                            if(!empty($useritems)){
                                echo "<div class='row'>";
                                echo "<h3></h3>";
                                foreach ($useritems as $item) {
                                echo "<div class='col-sm-6 col-md-3'>
                                        <div class='thumbnail pending-item-box'>
                                                <span class='approve-status'>Waiting For approval</span>
                                                <span class='price-tag'>".$item['Price']."</span>
                                                <img class='img-responsive img-thumbnail pending-img' src='client1.png' alt='product' />
                                                <div class='caption'>
                                                    <h3><a href='items.php?itemid=".$item['ItemID']."'>".$item['Name']."</a></h3>
                                                    <p>".$item['Description']."</p>
                                                    <span class='item-date'>".$item['AddDate']."</span>				
                                                </div>
                                        </div>
                                     </div>";
                                }
                                echo "</div>";	
                            } else {
                                echo "<div class='alert-success'>You Don't have items pending for admin approval, You can add new item from <a href='newad.php'>here</a></div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class='my-comments block'>
            <div class="container">
                <div class="panel panel-info">
                    <div class="panel-heading">My Comments</div>
                    <div class="panel-body">
                        <?php
                            //$stmt = $con->prepare(" SELECT * FROM comments WHERE UserID = ? ");
                            //$stmt->execute(array($userinfo['UserID']));
                            //$rows = $stmt->fetchAll();
                            $rows = getAll("*","comments","WHERE UserID =".$userinfo['UserID'],"ORDER BY CID DESC");	
                            if(!empty($rows)){
                                foreach ($rows as $row) {
                                    echo "<div class='user-comment'><div class='alert-info'>" . $row['Comment'] . "</div></div>";
                                }
                            }else {
                                echo "<div class='alert-danger'>No Items Added To This Category till Now !</div>";
                            } 
                        ?>
                    </div>
                </div>
            </div>
        </div>		
<?php
    } else {
        header('location:login.php');
        exit();
    }

    include "include/temp/footer.php";
    ob_end_flush();
=======
<?php	
    ob_start();
    session_start();
    $pageTitle = "My Profile";
    include "init.php";
    if (isset($_SESSION['user'])) {
        //$userinfo = getAll('*','users','WHERE UserName='."'".$_SESSION['user']."'");
        //print_r($userinfo)
        $getUserInfo = $con->prepare("SELECT * FROM users WHERE UserName = ?");
        $getUserInfo->execute(array($sessionUser));
        $userinfo = $getUserInfo->fetch();
?>
        <h1 class="text-center">My Profile</h1>
        <div class='information block'>
                <div class="container">
                        <div class="panel panel-info">
                                <div class="panel-heading">My Information</div>
                                <div class="panel-body">
                                        <ul class="list-unstyled">
                                                <li>
                                                        <i class="fas fa-unlock-alt fa-fw"></i>
                                                        <span>User Name </span> :
                                                        <?php echo $userinfo['UserName']?>
                                                </li>
                                                <li>
                                                        <i class="fas fa-user fa-fw"></i>
                                                        <span>Email</span> :
                                                        <?php echo $userinfo['Email']?>
                                                </li>
                                                <li>
                                                        <i class="far fa-envelope fa-fw"></i>
                                                        <span>Full Name</span> :
                                                        <?php echo $userinfo['FullName']?>
                                                </li>						
                                                <li>
                                                        <i class="fas fa-calendar-day fa-fw"></i>
                                                        <span>Registerd Date</span> :
                                                        <?php echo $userinfo['Date']?>
                                                </li>												
                                                <li>
                                                        <i class="fas fa-user-tag fa-fw"></i>
                                                        <span>Favorite Categories </span> :			
                                                </li>						
                                        </ul>
                                        <a class="btn btn-info" href="#">Edit Info</a>
                                </div>
                        </div>
                </div>
        </div>

        <div id="active-items" class='my-ads block'>
            <div class="container">
                <div class="panel panel-info">
                    <div class="panel-heading">My Items</div>
                    <div class="panel-body">
                        <?php
                            $useritems = getAll('*','items','WHERE Approve = 1 AND UserID='.$userinfo['UserID']);
                            //	$useritems = getApprovedItems('UserID',$userinfo['UserID']);
                            if(!empty($useritems)){
                                    echo "<div class='row'>";
                                    foreach ($useritems as $item) {
                                            echo "<div class='col-sm-6 col-md-3'>
                                                            <div class='thumbnail item-box'>
                                                            <span class='price-tag'>".$item['Price']."</span>
                                                                    <img class='img-responsive img-thumbnail' src='client1.png' alt='product' />
                                                                    <div class='caption'>
                                                                            <h3><a href='items.php?itemid=".$item['ItemID']."'>".$item['Name']."</a></h3>
                                                                            <p>".$item['Description']."</p>
                                                                            <span class='item-date'>".$item['AddDate']."</span>					
                                                                    </div>
                                                            </div>
                                                    </div>";
                                    }
                            echo "</div>";	
                            } else {
                                echo "<div class='alert-danger'>No Items Added To This Category till Now !, You can add one from <a href='newad.php'>here</a></div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="pending-items" class='my-ads block'>
            <div class="container">
                <div class="panel panel-info">
                    <div class="panel-heading">My Pending Items </div>
                    <div class="panel-body">
                        <?php
                            $useritems = getAll('*','items','WHERE Approve = 0 AND UserID='.$userinfo['UserID']);
                            //$useritems = getPendingItems('UserID',$userinfo['UserID']);
                            if(!empty($useritems)){
                                echo "<div class='row'>";
                                echo "<h3></h3>";
                                foreach ($useritems as $item) {
                                echo "<div class='col-sm-6 col-md-3'>
                                        <div class='thumbnail pending-item-box'>
                                                <span class='approve-status'>Waiting For approval</span>
                                                <span class='price-tag'>".$item['Price']."</span>
                                                <img class='img-responsive img-thumbnail pending-img' src='client1.png' alt='product' />
                                                <div class='caption'>
                                                    <h3><a href='items.php?itemid=".$item['ItemID']."'>".$item['Name']."</a></h3>
                                                    <p>".$item['Description']."</p>
                                                    <span class='item-date'>".$item['AddDate']."</span>				
                                                </div>
                                        </div>
                                     </div>";
                                }
                                echo "</div>";	
                            } else {
                                echo "<div class='alert-success'>You Don't have items pending for admin approval, You can add new item from <a href='newad.php'>here</a></div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class='my-comments block'>
            <div class="container">
                <div class="panel panel-info">
                    <div class="panel-heading">My Comments</div>
                    <div class="panel-body">
                        <?php
                            //$stmt = $con->prepare(" SELECT * FROM comments WHERE UserID = ? ");
                            //$stmt->execute(array($userinfo['UserID']));
                            //$rows = $stmt->fetchAll();
                            $rows = getAll("*","comments","WHERE UserID =".$userinfo['UserID'],"ORDER BY CID DESC");	
                            if(!empty($rows)){
                                foreach ($rows as $row) {
                                    echo "<div class='user-comment'><div class='alert-info'>" . $row['Comment'] . "</div></div>";
                                }
                            }else {
                                echo "<div class='alert-danger'>No Items Added To This Category till Now !</div>";
                            } 
                        ?>
                    </div>
                </div>
            </div>
        </div>		
<?php
    } else {
        header('location:login.php');
        exit();
    }

    include "include/temp/footer.php";
    ob_end_flush();
>>>>>>> c0b1787e8df51c5b21cccea03a9c3fc6284b1282
