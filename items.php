<?php
    ob_start();
    session_start();
    $pageTitle = "Item Page";
    include "init.php";

    if (isset($_GET['itemid']) && is_numeric($_GET['itemid']) && $_GET['itemid'] > 0 ) { 

        $itemid = intval($_GET['itemid']);
        $query = 'WHERE items.Approve = 0';
        $stmt = $con->prepare("SELECT items.* , users.UserName, categories.Name AS Category, categories.ID AS CatID
                                FROM 	   items
                                INNER JOIN users      ON items.UserID = users.UserID 
                                INNER JOIN categories ON items.CatID  = categories.ID
                                WHERE ItemID = ?");
        //$stmt = $con->prepare("select * FROM items WHERE ItemID = ?");
        $stmt->execute(array($itemid));
        $count = $stmt->rowCount();
        if ($count > 0) {
            $item = $stmt->fetch();	
            ?>
            <div class="container display-item">
                <div class="row">
                    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
                    <div class="col-md-3">
                        <img class="img-responsive img-thumbnail center-block" src="client1.png" alt="">
                    </div>
                    <div class="col-md-9">
                        <div class="item-info">
                            <h2><?php echo $item['Name'] ?></h2>
                            <p><?php echo $item['Description'] ?></p>
                            <ul class="list-unstyled">
                                <li>
                                    <i class="fas fa-money-bill-wave fa-fw"></i>						
                                    <span>Price</span>: <?php echo $item['Price'] ?>
                                </li>
                                <li>
                                    <i class="fas fa-calendar-day fa-fw"></i>							
                                    <span>Published In</span>: <?php echo $item['AddDate'] ?>
                                </li>
                                <li>
                                    <i class="fas fa-building fa-fw"></i>
                                    <span>Made In</span>: <?php echo $item['CountryMade'] ?>
                                </li>
                                <li>
                                    <i class="fas fa-user fa-fw"></i>
                                    <span>Added By</span>: <a href="#"><?php echo $item['UserName'] ?></a>
                                </li> 	
                                <li>
                                    <i class="fas fa-user-tag fa-fw"></i>
                                    <span>Category</span>: <a href="categories.php?pageid=<?php echo $item["CatID"]; ?>"><?php echo $item['Category'] ?></a>
                                </li>
                                <li>
                                    <i class="fas fa-user-tag fa-fw"></i>
                                    <span>Tags</span>: 
                                    <?php 
                                        if(!empty($item['Tags'])){
                                            $itemTags = explode(',', $item['Tags']);
                                            echo "<div class='bootstrap-tagsinput'>";
                                                    foreach ($itemTags as $tag) {
                                                        //echo $tag . " ";
                                                        echo "<a href='tags.php?name={$tag}'><span class='tag label label-success'>".$tag."</span></a>";
                                                    }
                                            echo "</div>";
                                        }
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="custom-hr">
            
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-3">
                        <div class="add-comment">
                            <h3>Type Your Comment</h3>
                            <?php
                                if (isset($_SESSION['user'])) {
                                    echo '<form action="'.$_SERVER["PHP_SELF"].'?itemid='.$item["ItemID"].'" method="POST">
                                             <textarea name="comment" required></textarea>
                                             <input class="btn btn-primary" type="submit" value="Add Comment">
                                        </form>';
                                } else {
                                echo "<div class='alert alert-info'>
                                         You Must be logined to add Your Comment, Click <a href='login.php'>here</a> to login 
                                     </div>";
                                }

                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                                $comment    = filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
                                $userid     = $_SESSION['uid'];
                                $itemid     = $item['ItemID'];
                                // User Name Validation 
                                if (!empty($comment)) {
                                    $stmt = $con->prepare("Insert INTO comments (Comment, Status, CommentDate,ItemID, UserID) VALUES (:xcomment, 0, now(), :xitemid, :xuserid )");
                                    $stmt->execute(array(
                                    'xcomment' => $comment,
                                    'xitemid' => $itemid, 
                                    'xuserid' => $userid));
                                    $count = $stmt->rowCount();

                                    if ($count > 0) {
                                         successRedirect("Congrats,your comment is posted and waiting to Published from admin");
                                    }else {
                                        errorRedirect("There Are problem in comment App, You can Contact with me");
                                    }
                                } else {
                                        echo "<div class='alert alert-danger'> Plz type your comment first and click add </div>";
                                    }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="custom-hr">
            <div class="container">
            <h3> Member Comments</h3>
            <?php
            $stmt = $con->prepare(" SELECT comments.*, users.UserName FROM comments 
            INNER JOIN users ON comments.UserID = users.UserID
            WHERE ItemID = ? AND Status = 1 ORDER BY CID DESC ");
            $stmt->execute(array($item['ItemID']));
            $rows = $stmt->fetchAll();
            if(!empty($rows)){
                foreach ($rows as $row) {
                    echo '<div class="comment-box">
                    <div class="row">
                    <div class="col-md-2 text-center">
                    <img class="img-responsive img-thumbnail img-circle center-block" src="profile.jpg" alt=""><span>'.$row["UserName"].'</span>
                    </div>
                    <div class="col-md-10">
                    <p class="lead">'. $row["Comment"] .'</p>
                    </div>
                    </div>
                    <hr class="custom-hr">
                    </div>';
                }
            }else {echo '<div class="alert alert-danger">No Comments typed about this item till Now !</div>';}

            ?>
            </div>	
            <?php

       } else { 
           errorRedirect("This Item is not Exist In DB",'back');   
       }

    } else { 
        errorRedirect("Invalid Item ID",'back');
    }

    include "include/temp/footer.php";
    ob_end_flush();