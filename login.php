<?php
    session_start();
    $pageTitle = 'Login | SignUp';

    if(isset($_SESSION['member'])){
        header('Location: profile.php');
    }
include "init.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['login'])) {
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashedPass = sha1($password);

        $stmt = $con->prepare("SELECT userID, username, password FROM users
                                WHERE username = ? AND password = ?");
        $stmt->execute(array($username,$hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        //if count > 0 then database contains record about this username
        if ($count > 0) {
            $_SESSION['member'] = $username;
            $_SESSION['id'] = $row['userID'];
            header('Location: index.php');
            exit();
        }
    }else{

        $errors = array();
        if (isset($_POST['username'])) {
            $filterusername = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            if (strlen($filterusername) < 4) {
               $errors[] = 'Username can\'t be less than 5 letters';
            }

        }
        if (isset($_POST['password']) && isset($_POST['againpassword'])) {

            if (empty($_POST['password']) && empty($_POST['againpassword'])) {
                $errors[] = 'Sorry password can\'t be empty';
             }

            $pass1 = sha1($_POST['password']);
            $pass2 = sha1($_POST['againpassword']);

            if ($pass1 !== $pass2) {
               $errors[] = 'Sorry password isn\'t match';
            }

        }
        if (isset($_POST['fullname'])) {
            $filterfullname = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
            if (strlen($filterfullname) < 10) {
               $errors[] = 'Full name can\'t be less than 10 letters';
            }

        }
        if (isset($_POST['email'])) {
            $filteremail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) != true) {
               $errors[] = 'Email isn\'t valid';
            }

        }
        if (empty($errors)) {
            
                //check if user exist in database
                $check = checkItem("username","users",$_POST['username']);
                if ($check == 0) {
                //insert new member into database
                $stmt = $con->prepare("INSERT INTO users (username, password, email, fullname, regstatus, date)
                VALUES (:username, :pass, :email, :fullname, 0, now())");
                $stmt->execute(array(
                    'username' => $filterusername,
                    'pass' => $pass1,
                    'email' => $filteremail,
                    'fullname' => $filterfullname
                )); 

                echo '<div class="alert alert-success">You add new member successfuly</div>';
                header("refresh:2;url=index.php");
                exit();
                }else{
                    echo '<div class="alert alert-danger">This user already exist</div>';
                }
        }



    }
}
?>

<div class="container login-page">
        <h4 class="text-center">
            <span class="loginspan selected" data-class="login">Login</span>
            | <span class="signupspan" data-class="signup">SignUp</span>
        </h4>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input class="form-control" type="text" name="username" autocomplete="off" placeholder="enter your username" required="required">
        <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="enter your password" required="required">
        <input class="btn btn-primary" name="login" type="submit" value="Login">
    </form>







    <form class="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input class="form-control" type="text" name="username" autocomplete="off" placeholder="type your username">
        <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="type complex password">
        <input class="form-control" type="password" name="againpassword" autocomplete="new-password" placeholder="type password again">
        <input class="form-control" type="text" name="fullname" autocomplete="off" placeholder="type full name">
        <input class="form-control" type="email" name="email" autocomplete="off" placeholder="type valid email">
        <input class="btn btn-success" name="signup" type="submit" value="SignUp">
    </form>
</div>

<div class="container errors text-center">
    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">'.$error.'</div>';
        }
    }
    ?>
</div>
<?php include $tpl . "footer.php"; ?>