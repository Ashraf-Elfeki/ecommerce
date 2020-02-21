<?php 
    ob_start();	
    session_start();
    $pageTitle = "Login";
    if (isset($_SESSION['user'])) {
        header('location:index.php');
    }
    include "init.php";	
    // Check If user Comming from post Request or not
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $hashedpass = sha1($password);

            // Check if the logined user is exist in DB
            $stmt = $con->prepare("select UserID, UserName, Password FROM users WHERE UserName = ? And Password = ?");
            $stmt->execute(array($username,$hashedpass));
            $get = $stmt->fetch();
            $count = $stmt->rowCount();

            if($count > 0) {
                $_SESSION['user'] = $username;
                $_SESSION['uid'] = $get['UserID'];
                header('location:index.php');
                exit();
            } else {
                errorRedirect("Invalid User Name or Password !",'back');
            }
        } else {
            $formErrors   = array();
            $username     = $_POST['username'];
            $email        = $_POST['email'];
            $password     = $_POST['password'];
            $confirmpass  = $_POST['confirmpassword'];
            
            // User Name Validation 
            if (isset($username) && !empty($username)) {
                    $filteredName = filter_var($username, FILTER_SANITIZE_STRING);
                    if(strlen($filteredName) < 4 ) {
                            $formErrors [] = "Your User Name Must be more than 4 characters";
                    }

            } else {
                $formErrors[] = "You Must Type your user name";
            }

            // Email Validation 
            if (isset($email)) {
                $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                if (!filter_var($filteredEmail, FILTER_VALIDATE_EMAIL)) {
                    $formErrors[] = "You Must Enter Valid Email Format";
                }
            } else {
                    $formErrors[] = "You Must Type your Email";
            }

            // Password Validation 
            if (isset($password) && isset($confirmpass) && !empty($password)) {
                if( sha1($password) !== sha1($confirmpass)) {
                    $formErrors [] = "The password Must be matched";
                }

            } else {
                    $formErrors[] = "You Must Type your Password";
            }

            // check if the inserted data is free errors or not 
            if(empty($formErrors)){
                if (!checkexist("UserName","users",$username)){
                    $stmt = $con->prepare("Insert INTO users (UserName, Password, Email,RegStatus, Date) VALUES (:xuser, :xpass, :xemail, 0, now()) ");
                    $stmt->execute(array(
                                'xuser'  => $username,
                                'xpass'  => sha1($password), 
                                'xemail' => $email));
                    $count = $stmt->rowCount();

                    if ($count > 0) {
                        $successMsg = "Congrats,You are Registered Now";
                    }else {
                        $formErrors[] = "There Are problem in regestartion App, You can Contact with me ";
                    }
                    // successRedirect("Member Added Successfuly",'members.php');
                } else {
                        $formErrors[] = "This User Already Exists";
                }
            }
        }
    }	
?>
    <div class="container login-page">
	<h1 class="text-center">
            <span data-class=".login" class="selected" >login</span> |
            <span data-class=".signup" >SignUp</span>
	</h1>     
	<!---------------------------- Start Login Form------------------------------------------------------>
	<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="input-container">
                <input class="form-control" type="text" name="username" required placeholder="User Name"  autocomplete="off" >
            </div>
            <div class="input-container">
                <input class="form-control" type="password" name="password" required placeholder="Password" autocomplete="new-password">
            </div>
            <input class="btn btn-primary btn-block" type="submit" value="login" name="login">
	</form>
	<!---------------------------- End Login Form------------------------------------------------------>
	
	<!---------------------------- Start Signup Form------------------------------------------------------>
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="input-container">
                <input class="form-control" type="text" name="username"  placeholder="User Name"  autocomplete="off" >
            </div>
            <div class="input-container">
                <input class="form-control" type="email" name="email"   placeholder="Your Email">
            </div>
            <div class="input-container">
                <input class="form-control" type="password" name="password"  placeholder="Strong Password"  autocomplete="new-password">
            </div>
            <div class="input-container">
                <input class="form-control" type="password" name="confirmpassword"  placeholder="Confirm Password"  autocomplete="new-password">
            </div>
            <input class="btn btn-success btn-block" type="submit" value="SignUp" name="signup">
	</form>
	<!---------------------------- End Signup Form------------------------------------------------------>	

	<div class='the-errors text-center'>
            <?php 
                if (!empty($formErrors)) {
                    foreach ($formErrors as $error) {
                        echo "<div class='msg error'>" . $error . "</div>";
                        //errorRedirect($error,'back');
                    }	
                }
                if (isset($successMsg)) {
                    echo "<div class='msg success'>" . $successMsg . "</div>";				
                }			
            ?>
	</div>
    </div>
<?php 
    include "include/temp/footer.php";
    ob_end_flush();
?>