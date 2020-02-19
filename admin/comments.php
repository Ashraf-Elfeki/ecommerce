<?php
/*
===========================================
=======    Manage Comments  Page 	   ========
=======     Edit - Delete     ========
===========================================

*/
    ob_start();
    session_start();
    $pageTitle = "Manage Comments";

    if (isset($_SESSION['username'])) {
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') {
            $stmt = $con->prepare(" SELECT comments.*, items.Name AS ItemName, users.UserName AS ItemUser
                                    FROM comments				
                                    INNER JOIN items ON comments.ItemID = items.ItemID  
                                    INNER JOIN users ON comments.UserID = users.UserID
                                    ORDER By comments.CID DESC ");
            $stmt->execute();
            $rows = $stmt->fetchAll();

            echo '<h3 class="text-center">Manage Comments</h3>
                  <div class="container text-center">';
            if(!empty($rows)){ ?>	
                <div class="table-responsive ">
                    <table class="table table-hover table-striped table-bordered text-center">
                        <tr class="warning"> 
                            <td>ID</td>
                            <td>Comment</td>
                            <td>Added Date</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Controle</td>		
                        </tr>
                         <?php
                            foreach ($rows as $com) {
                                echo "<tr>
                                        <td>" . $com['CID'] . "</td>
                                        <td>" . $com['Comment'] . "</td>
                                        <td>" . $com['CommentDate'] . "</td>
                                        <td>" . $com['ItemName'] . "</td>
                                        <td>" . $com['ItemUser'] . "</td>
                                        <td>
                                            <a class='btn btn-info' href='comments.php?do=Edit&comid=" . $com['CID'] . "'><i class='far fa-edit fa-lg'></i>Edit</a>
                                            <a class='btn btn-danger confirm' href='comments.php?do=Delete&comid=" . $com['CID'] . "'><i class='far fa-times-square fa-lg'></i>Delete</a>";
                                            if ($com['Status'] == 0) {
                                                    echo "<a class='btn btn-info' href='comments.php?do=Approve&comid=" . $com['CID'] . "'><i class='far fa-check-square fa-lg'></i>Approve</a>";
                                            }
                                echo "</td>						
                                        </tr>";
                            }

                         ?>
                    </table>
                </div>
            <?php
            echo "</div>";
        } else {
                echo "<div class='alert alert-danger'>There is No Comments Added Yet !</div>";
            }
        } 
        elseif ($do == 'Approve'){

            echo "<h1 class='text-center'>Approve Comments</h1>";
            echo "<div class='container'>";	
                if (isset($_GET['comid']) && is_numeric($_GET['comid']) && $_GET['comid'] > 0 ) { 

                    $comid = intval($_GET['comid']);
                    if (checkexist("CID","comments",$comid)){			
                        $stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE CID = ?");
                        $stmt->execute(array($comid));
                        successRedirect("Comment Approved Successfuly",'comments.php');
                    } else {
                        errorRedirect("This Comment Is Not Exist in DB",'back');
                    }
                }
            echo "</div>";

        } elseif ($do == 'Edit') {
            //$pageTitle = "Update". $_SESSION['username'] . "Profile";
            if (isset($_GET['comid']) && is_numeric($_GET['comid']) && $_GET['comid'] > 0 ) { 

                $comid = intval($_GET['comid']);
                $stmt = $con->prepare("select * FROM comments WHERE CID = ?");
                $stmt->execute(array($comid));
                $count = $stmt->rowCount();

                if ($count > 0) {
                    $row = $stmt->fetch(); ?>
                    <!-------------------- Edit Comment Form -------------------------->

                    <h3 class="text-center update-head">Edit Comment</h3>
                    <div class="container">
                        <form class="form-horizontal" action="?do=Update" method="POST">
                            <input type="hidden" name="comid" value="<?php echo $row['CID']?>">
                            <!--   Comment Content   -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Comment Content</label>
                                <div class="col-sm-10 col-md-4">
                                    <textarea name="comment" class="form-control"><?php echo $row['Comment']?></textarea>
                                </div>
                            </div>

                            <!--   Submit   -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-4">
                                    <input type="submit" Value="Update" class="btn btn-primary btn-block btn-lg">
                                </div>
                            </div>
                        </form>
                    </div>

<?php
                } else {
                    errorRedirect("Theres no Such ID ",'back');	
                }

            } else {
                errorRedirect("Invalid Comment ID",'back');
                }

        } elseif ($do == 'Update') {

                echo "<h1 class='text-center'>Update Comment</h1>";
                echo "<div class='container'>";
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $comid    = $_POST['comid'];
                        $comment  = $_POST['comment'];
                        $stmt = $con->prepare("UPDATE comments SET Comment = ? WHERE CID = ?");
                        $stmt->execute(array($comment, $comid));
                        //echo "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " Record Updated</div>";
                        successRedirect("Comment Data Updated Successfuly",'comments.php');

                } else {
                        errorRedirect("sorry you cann't browse this page Directly",'back');
                }
                echo "</div>";

        } elseif ($do == 'Delete') {

            echo "<h1 class='text-center'>Delete Comment</h1>";
            echo "<div class='container'>";	
                    if (isset($_GET['comid']) && is_numeric($_GET['comid']) && $_GET['comid'] > 0 ) { 

                        $comid = intval($_GET['comid']);
                        if (checkexist("CID","comments",$comid)){			
                            $stmt = $con->prepare("DELETE FROM comments WHERE CID = :zcid");
                            $stmt->bindParam("zcid",$comid);
                            $stmt->execute();

                            successRedirect("Comment Successfully Deleted","comments.php");
                        } else {
                            errorRedirect("This Comment Is Not Exist in DB",'back');
                        }
                    }
            echo "</div>";
        }
        include $tmp."footer.php";
    } else {
        header("location:index.php");
        exit();
    }
    ob_flush();