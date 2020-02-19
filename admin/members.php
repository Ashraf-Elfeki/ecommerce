<?php
/*
===========================================
=======    Manage Member Page 	   ========
=======    Add - Edit - Delete     ========
===========================================
*/
    ob_start();
    session_start();
    $pageTitle = "Manage Members";
    if (isset($_SESSION['username'])) {
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';	
        if ($do == 'Manage') {	
            $query = '';
            if(isset($_GET['page']) && $_GET['page'] == 'pending') {
                    $query = 'AND RegStatus = 0';
            }
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
            $stmt->execute();
            $rows = $stmt->fetchAll();
         ?>
            <h3 class="text-center"\>Manage Members</h3>
            <div class="container text-center">
            <?php 
                if(!empty($rows)){
        ?>
                <div class="table-responsive ">
                    <table class="manage-members table table-hover table-striped table-bordered text-center">
                        <tr class="warning"> 
                            <td>ID</td>
                            <td>Avatar</td>							
                            <td>User Name</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registered Date</td>
                            <td>Controle</td>		
                        </tr>
                        <?php
                            foreach ($rows as $user) {
                                echo "<tr>
                                        <td>" . $user['UserID'] . "</td>
                                        <td>";
                                        if(empty($user['avatar'])){
                                                echo "<img class='img-circle' src='uploads/avatar/avatar.jpg'>";
                                        }else {
                                                echo "<img class='img-circle' src='uploads/avatar/" . $user['avatar'] . "'>";
                                        }

                                        echo "</td>
                                        <td>" . $user['UserName'] . "</td>											
                                        <td>" . $user['Email'] . "</td>
                                        <td>" . $user['FullName'] . "</td>
                                        <td>" . $user['Date'] . "</td>
                                        <td>
                                            <a class='btn btn-info' href='members.php?do=Edit&userid=" . $user['UserID'] . "'><i class='far fa-edit fa-lg'></i>Edit</a>
                                            <a class='btn btn-danger confirm' href='members.php?do=Delete&userid=" . $user['UserID'] . "'><i class='far fa-times-square fa-lg'></i>Delete</a>";
                                            if ($user['RegStatus'] == 0) {
                                                echo "<a class='btn btn-info' href='members.php?do=Activate&userid=" . $user['UserID'] . "'><i class='far fa-check-square fa-lg'></i>Activate</a>";
                                            }
                                echo "</td>						
                                    </tr>";
                            }
                        ?>
                    </table>

                </div>
        <?php } else {
                    echo "<div class='alert alert-danger text-center'>There is No Members Added Yet !, You Can Add New One from the down button</div>";
                }
        ?>
                <a class="btn btn-primary btn-lg" href='members.php?do=Add'><i class="fas fa-plus-square"></i>New Member </a>
            </div>
        <?php
        } 
         elseif ($do == 'Add'){
        ?>
<!-------------------- Add New Member Form -------------------------->
            <h3 class="text-center update-head">Add New Member</h3>
            <div class="container">
                    <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                        <!--   User Name   -->
                        <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">User Name</label>
                                <div class="col-sm-10 col-md-4">
                                        <input type="text" name="username" class="form-control" autocomplete="off" required placeholder="User Name" >
                                </div>
                        </div>

                        <!--   Password   -->					
                        <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10 col-md-4">
                                        <input type="Password" name="password" class="password form-control" autocomplete="new-password" placeholder="Password" required>
                                        <i class="show-pass fa fa-eye fa-2x"></i>
                                </div>
                        </div>

                        <!--   Email   -->					
                        <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10 col-md-4">
                                        <input type="email" name="email" class="form-control" required placeholder="Email" >
                                </div>					
                        </div>

                        <!--   Full Name   -->				
                        <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Full Name</label>
                                <div class="col-sm-10 col-md-4">
                                        <input type="text" name="fullname" class="form-control" required placeholder="Full Name" >
                                </div>
                        </div>

                        <!--   Profile Image   -->				
                        <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Profile Image</label>
                                <div class="col-sm-10 col-md-4">
                                        <input type="file" name="avatar" class="form-control"  >
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

        } elseif ($do == 'Insert') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo "<h1 class='text-center'>Add New Member</h1>";
                echo "<div class='container'>";
                $user  = $_POST['username'];
                $pass  = $_POST['password'];			
                $email = $_POST['email'];
                $fname = $_POST['fullname'];

                print_r($_FILES['avatar']);
                $avatarName 	= $_FILES['avatar']['name'];
                $avatarType 	= $_FILES['avatar']['type'];
                $avatarTmpName 	= $_FILES['avatar']['tmp_name'];
                $avatarSize 	= $_FILES['avatar']['size'];
                $avatarAllowedExtension = array("jpeg","jpg","png","gif");
                $avatarExtension = strtolower(end(explode('.', $avatarName)));


                echo $avatarName."<br>";
                echo $avatarType."<br>";
                echo $avatarTmpName."<br>";
                echo $avatarSize."<br>";
                echo $avatarExtension;

                /* Form Validation */
                $errors = array();
                if (empty($user)) {
                    $errors[] = "You Must Type Your User Name";
                }elseif (strlen($user) < 4 || strlen($user) > 20 ) {
                   $errors[] = "Your User Name Must Contains from 4 to 20 chars or special Chars ";
                }

                if (empty($pass)) {
                    $errors[] = "You Must Type Your Password";
                }elseif (strlen($pass) < 4 || strlen($pass) > 20 ) {
                    $errors[] = "Your Password Must Contains from 4 to 20 chars or special Chars ";
                }else {
                        $hpass = sha1($pass);
                }

                if (empty($email)) {
                    $errors[] = "You Must Type Your Email";
                }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Invalid Email Format ";
                }

                if (empty($fname)) {
                    $errors[] = "You Must Type Your Full Name";
                } elseif (strlen($fname) < 4 || strlen($fname) > 20 ) {
                    $errors[] = "Your Full Name Must Contains from 4 to 20 chars or special Chars ";
                }
                if (empty($avatarName)) {
                    $errors[] = "You Must upload your profile Image";
                }elseif (!in_array($avatarExtension, $avatarAllowedExtension)) {
                    $errors[] = "Invalid Image Type";
                }elseif ($avatarSize > 4194304) {
                    $errors[] = "Uploaded Image must be smaller 4 MB";
                }

                // check if the inserted data is free errors or not 
                if(empty($errors)){

                    $avatar = rand(1,1000000000)."_".$avatarName;
                    move_uploaded_file($avatarTmpName, "uploads\avatar\\".$avatar);
                    echo "Uploaded Done";
                    if (!checkexist("UserName","users",$user)){

                        $stmt = $con->prepare("Insert INTO users (UserName, Password, Email, FullName,RegStatus, Date, avatar) VALUES (:xuser, :xpass, :xemail, :xfullname, 1, now(), :xavatar) ");
                        $stmt->execute(array(
                                'xuser' 	=> $user,
                                'xpass' 	=> $hpass, 
                                'xemail' 	=> $email, 
                                'xfullname' => $fname,
                                'xavatar' 	=> $avatar
                        ));
                        $count = $stmt->rowCount();
                        //echo "<div class='alert alert-success text-center'>" .  . " </div>";
                        successRedirect("Member Added Successfuly",'members.php');

                    } else {
                        errorRedirect("This User Already Exists",'back');
                    }

                }else {
                    foreach ($errors as $error) {
                        //echo  "<div class='alert alert-danger text-center'>" . $error."</div>";
                        errorRedirect($error,'');
                    }
                }

                } else {
                    errorRedirect("sorry you cann't browse this page Directly",'back');
                }
                echo "</div>";
        }elseif ($do == 'Activate'){
            echo "<h1 class='text-center'>Activate Member</h1>";
            echo "<div class='container'>";	
            if (isset($_GET['userid']) && is_numeric($_GET['userid']) && $_GET['userid'] > 0 ) { 
                $userid = intval($_GET['userid']);
                if (checkexist("UserID","users",$userid)){			
                    $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                    $stmt->execute(array($userid));
                    successRedirect("User Activated Successfuly",'members.php');
                } else {
                    errorRedirect("This User Is Not Exist in DB",'back');
                }
            }
            echo "</div>";

        } elseif ($do == 'Edit') {
                //$pageTitle = "Update". $_SESSION['username'] . "Profile";
                if (isset($_GET['userid']) && is_numeric($_GET['userid']) && $_GET['userid'] > 0 ) { 

                                $userid = intval($_GET['userid']);
                                $stmt = $con->prepare("select * FROM users WHERE UserID = ? LIMIT 1");
                                $stmt->execute(array($userid));
                                $count = $stmt->rowCount();

                                if ($count > 0) {
                                        $row = $stmt->fetch(); ?>
                                        <!-------------------- Edit Member Form -------------------------->

                                        <h3 class="text-center update-head">Edit Member</h3>
                                        <div class="container">
                                                <form class="form-horizontal" action="?do=Update" method="POST">
                                                        <input type="hidden" name="userid" value="<?php echo $row['UserID']?>">
                                                        <!--   User Name   -->
                                                        <div class="form-group form-group-lg">
                                                                <label class="col-sm-2 control-label">User Name</label>
                                                                <div class="col-sm-10 col-md-4">
                                                                        <input type="text" name="username" class="form-control" autocomplete="off" value="<?php echo $row['UserName']?>" required>
                                                                </div>
                                                        </div>

                                                        <!--   Password   -->					
                                                        <div class="form-group form-group-lg">
                                                                <label class="col-sm-2 control-label">Password</label>
                                                                <div class="col-sm-10 col-md-4">
                                                                        <input type="hidden" name="oldpassword" value="<?php echo $row['Password']?>">
                                                                        <input type="Password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="If U Don't need to change, Leave Blank">
                                                                </div>
                                                        </div>

                                                        <!--   Email   -->					
                                                        <div class="form-group form-group-lg">
                                                                <label class="col-sm-2 control-label">Email</label>
                                                                <div class="col-sm-10 col-md-4">
                                                                        <input type="email" name="email" class="form-control" value="<?php echo $row['Email']?>"required>
                                                                </div>					
                                                        </div>

                                                        <!--   Full Name   -->				
                                                        <div class="form-group form-group-lg">
                                                                <label class="col-sm-2 control-label">Full Name</label>
                                                                <div class="col-sm-10 col-md-4">
                                                                        <input type="text" name="fullname" class="form-control" value="<?php echo $row['FullName']?>" required>
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
                        errorRedirect("Invalid User ID",'back');
                        }

        } elseif ($do == 'Update') {

                echo "<h1 class='text-center'>Update Member</h1>";
                echo "<div class='container'>";
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $id    = $_POST['userid'];
                        $user  = $_POST['username'];
                        $email = $_POST['email'];
                        $fname = $_POST['fullname'];
                        $pass  = '';
                        /* Form Validation */
                        $errors = array();
                        if (empty($user)) {
                                $errors[] = "You Must Type Your User Name";
                        }elseif (strlen($user) < 4 || strlen($user) > 20 ) {
                                $errors[] = "Your User Name Must Contains from 4 to 20 chars or special Chars ";
                        }

                        if (empty($email)) {
                            $errors[] = "You Must Type Your Email";
                        }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $errors[] = "Invalid Email Format ";
                        }

                        if (empty($fname)) {
                           $errors[] = "You Must Type Your Full Name";
                        } elseif (strlen($fname) < 4 || strlen($fname) > 20 ) {
                           $errors[] = "Your Full Name Must Contains from 4 to 20 chars or special Chars ";
                        }

                        $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] :	$pass = sha1($_POST['newpassword']);

                        if(empty($errors)){

                                if (!checkexist_update("UserName", "users", $user, "UserID", $id)){
                                $stmt = $con->prepare("UPDATE users SET UserName = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                                $stmt->execute(array($user, $email, $fname, $pass, $id));
                                //echo "<div class='alert alert-success text-center'>" . $stmt->rowCount() . " Record Updated</div>";
                                successRedirect("User Data Updated Successfuly",'members.php');
                                } else{
                                        errorRedirect("This UserName Is Already Exist, Plz Choose another One","back");
                                }

                        }else {
                                foreach ($errors as $error) {
                                        //echo  "<div class='alert alert-danger text-center'>" . $error."</div>";
                                        errorRedirect($error,'back');
                                }
                        }
                } else {
                    errorRedirect("sorry you cann't browse this page Directly","back");
                }
                echo "</div>";

        } elseif ($do == 'Delete') {

                echo "<h1 class='text-center'>Delete Member</h1>";
                echo "<div class='container'>";	
                        if (isset($_GET['userid']) && is_numeric($_GET['userid']) && $_GET['userid'] > 0 ) { 

                                        $userid = intval($_GET['userid']);
                                        if (checkexist("UserID","users",$userid)){			
                                                $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuserid");
                                                $stmt->bindParam("zuserid",$_GET['userid']);
                                                $stmt->execute();

                                                successRedirect("Member Successfully Deleted","members.php");
                                        } else {
                                                errorRedirect("This User Is Not Exist in DB","back");
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