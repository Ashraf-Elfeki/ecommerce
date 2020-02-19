<?php
    ob_start();
    session_start();
    $pageTitle = "Dashboard";
    if (isset($_SESSION['username'])) {
        include 'init.php';
        $latestUserNum = 6; 
        $latestUsers = getLatest("*","users","UserID",$latestUserNum);

        $latestItemsNum = 5 ;
        $latestItems = getLatest("*","items","ItemID",$latestItemsNum);

        $latestComNum = 3;

        $stmt = $con->prepare(" SELECT comments.*, users.UserName AS CommentUser
                                FROM comments				
                                INNER JOIN users ON comments.UserID = users.UserID
                                ORDER By CID DESC
                                LIMIT $latestComNum");
        $stmt->execute();
        $comments = $stmt->fetchAll();

        echo "Welcome ". $_SESSION['username'] ." Session Working Well your ID is " . $_SESSION['ID'];

?>
        <div class="home-stats">
            <div class="container text-center">
                <h1>Admin Dashboard</h1>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat stat-active-members">
                            <i class="fas fa-users fa-5x"></i>
                            <div class="info">
                                Total Members
                                <span>
                                    <a href="members.php"><?php echo getCount('UserID','users'); ?></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat stat-pending-members">
                            <i class="fas fa-user-plus fa-3x"></i>
                            <div class="info">
                                Pending Members
                                <span>
                                    <a href="members.php?do=Manage&page=pending"><?php echo pendingUserCount(); ?></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat stat-items">
                            <i class="fas fa-tags fa-5x"></i>
                            <div class="info">
                                Total Items
                                <span>
                                    <a href="items.php"><?php echo getCount('ItemID','items'); ?></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                            <div class="stat stat-comments">
                                <i class="far fa-comments fa-5x"></i>
                                <div class="info">
                                    Total Comments
                                    <span>
                                        <a href="comments.php"><?php echo getCount('CID','comments'); ?></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <div class="latest">
            <div class="container">
                <div class="row">

                        <div class="col-md-6 panel panel-success">
                                <div class="panel-heading">
                                        <i class="fas fa-users fa-3x"></i>Latest <?php echo $latestUserNum; ?> Registered users
                                        <span class="toggle-info pull-right">
                                                <i class="fas fa-minus fa-2x"></i>
                                        </span>			
                                </div>

                                <div class="panel-body">
                                        <ul class="list-unstyled latest-users" >
                                        <?php
                                        if (!empty($latestUsers)) {
                                                foreach ($latestUsers as $user) {
                                                        echo "<li>" . $user['UserName']."<a class='btn btn-success pull-right' href='members.php?do=Edit&userid=" . $user['UserID'] . "'>
                                                                <i class='far fa-edit fa-lg'></i>Edit</a>";
                                                                if ($user['RegStatus'] == 0) {
                                                                        echo "<a class='btn btn-info pull-right' href='members.php?do=Activate&userid=" . $user['UserID'] . "'><i class='far fa-check-square fa-lg'></i>Activate</a>";
                                                                }
                                                        echo "</li>";
                                                }
                                        } else {
                                                        echo "<div class='alert alert-danger'>there is no Users Registered till now !</div>";
                                                        }	
                                        ?>
                                        </ul>
                                </div>

                        </div>
                        <div class="col-md-6 panel panel-info">
                                <div class="panel-heading">
                                        <i class="fas fa-tags fa-3x"></i>Latest <?php echo $latestItemsNum; ?> Items
                                        <span class="toggle-info pull-right">
                                                <i class="fas fa-minus fa-2x"></i>
                                        </span>
                                </div>
                                <div class="panel-body">
                                        <ul class="list-unstyled latest-items" >
<?php
        if (!empty($latestItems)) {
                foreach ($latestItems as $item) {
                        echo "<li>" . $item['Name']."<a class='btn btn-success pull-right' href='items.php?do=Edit&itemid=" . $item['ItemID'] . "'>
                                <i class='far fa-edit fa-lg'></i>Edit</a>";

                                if ($item['Approve'] == 0) {
                                        echo "<a class='btn btn-info pull-right' href='items.php?do=Approve&itemid=" . $item['UserID'] . "'><i class='far fa-check-square fa-lg'></i>Approve</a>";
                                }
                        echo "</li>";
                }
        } else {
                        echo "<div class='alert alert-danger'>there is no Items added !</div>";
                        }
?>
                        </ul>
                                </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-6 panel panel-success">
                        <div class="panel-heading">
                            <i class="fas fa-users fa-3x"></i>Latest <?php echo $latestComNum; ?> Comments
                            <span class="toggle-info pull-right">
                                <i class="fas fa-minus fa-2x"></i>
                            </span>			
                        </div>

                        <div class="panel-body">
                            <ul class="list-unstyled latest-users" >
<?php
                            if (!empty($comments)) {
                                foreach ($comments as $com) {
                                echo "<div class='comment-box'> 
                                        <a href='members.php'><span class='member-n'>".$com['CommentUser']."</span></a>
                                        <p class='member-c'>".$com['Comment']."</p>
                                     </div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>there is no comments added !</div>";
                            }

?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php
        include $tmp."footer.php";
    } else {
        header("location:index.php");
        exit();
    }
    ob_end_flush();