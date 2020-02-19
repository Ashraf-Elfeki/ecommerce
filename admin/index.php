<?php
    session_start();
    $noNavbar = '';
    $pageTitle = "Login";
    include "init.php";	
    if (isset($_SESSION['username'])) {
        header('location:dashboard.php');
    }

    // Check If user Comming from post Request or not
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['user'];
        $password = $_POST['password'];
        $hashedpass = sha1($password);

        // Check if the logined user is exist in DB
        $stmt = $con->prepare("select UserID, UserName, Password FROM users WHERE UserName = ? And Password = ? AND GroupID = 1");
        $stmt->execute(array($username,$hashedpass));
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        $_SESSION['ID'] = $row['UserID'];
        if($count > 0) {
            $_SESSION['username'] = $username;
            header('location:dashboard.php');
            exit();
        } else {
            errorRedirect("Invalid User Name or Password !",'back',1);
        }
    }

?>
    <div class="login-form">
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <h4 class="text-center">Admin Login</h4>
            <label>User Name</label>
            <input class="form-control" type="text" name="user" placeholder="User Name" autocomplete="off" >
            <label>Password</label>
            <input class="form-control" type="password" name="password" placeholder="Password" autocomplete="new-password">
            <input class="btn- btn-primary btn-block btn-lg" type="submit" name="" value="Login">
            <a href="#">Forget Pssword ?</a>
	</form>
    </div>

<?php
    include "include/temp/footer.php";
