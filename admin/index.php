<?php 
    session_start();
    $nonavbar = '';
    $pageTitle = 'Login';

    if(isset($_SESSION['username'])){
        header('Location: dashboard.php');
    }
    include "init.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);

        $stmt = $con->prepare("SELECT userID,username, password FROM users
                                 WHERE username = ? AND password = ? 
                                 AND groupID = 1 LIMIT 1");
        $stmt->execute(array($username,$hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        //if count > 0 then database contains record about this username
        if ($count > 0) {
           $_SESSION['username'] = $username;
           $_SESSION['ID'] = $row['userID'];
           header('Location: dashboard.php');
           exit();
        }
    }
?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <h4 class="text-center">Login Admin</h4>
    <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
    <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password">
    <input class="btn btn-primary btn-block" type="submit" value="Login">
</form>


<?php include $tpl . "footer.php"; ?>
