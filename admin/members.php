<?php 
    session_start();
    $pageTitle = 'Members';
    if(isset($_SESSION['username'])){
        
        include 'init.php';
        
        /**********************/
        if(isset($_GET['do']))
        {
            $do = $_GET['do'];
        }else{
            $do = 'manage';
        }
        /**********************/ 

        if ($do =='manage') {
            //to get just pending users which regstatus = 0
            $query = '';
            if (isset($_GET['page']) && $_GET['page'] == 'pending') {
                $query = 'AND regstatus = 0';
            }
             //manage members page
             //select all users not admins just members where groupid = 0

             $stmt = $con->prepare("SELECT * FROM users WHERE groupID != 1 $query");
             $stmt->execute();
             $row = $stmt->fetchAll();
             ?>

            <h1 class="text-center">Manage members</h1>
            <div class="container">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-dark">
                        <tr>
                            <td>#ID</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Fullname</td>
                            <td>Registered date</td>
                            <td>Control</td>
                        </tr>
                        <?php 
                        
                        foreach ($row as $val) {
                           ?>
                            <tr>
                                <td><?php echo $val['userID']; ?></td>
                                <td><?php echo $val['username']; ?></td>
                                <td><?php echo $val['email']; ?></td>
                                <td><?php echo $val['fullname']; ?></td>
                                <td><?php echo $val['date']; ?></td>
                                <td>
                                    <a href="members.php?do=edit&userid=<?php echo $val['userID']; ?>" class="btn btn-success">Edit</a>
                                    <a href="members.php?do=delete&userid=<?php echo $val['userID']; ?>" class="btn btn-danger confirm">Delete</a>
                                    <?php
                                        if ($val['regstatus'] == 0) {
                                    ?>
                                        <a href="members.php?do=activate&userid=<?php echo $val['userID']; ?>" class="btn btn-info">Activate</a>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </tr>
                           <?php
                        }
                        
                        ?>
                    </table>
                </div>

                <a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add new member</a>
            </div>

             <?php

        
        }elseif($do == 'activate'){
                        //activate member page
                        ?>
                        <h1 class="text-center">Activate member</h1>
                        <div class="container">
                    <?php
        
                        $userid =  isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                        $check = checkItem("userid","users",$userid);
        
                        if ($check > 0) {
                            //activate member from database
                            $stmt = $con->prepare("UPDATE users SET regstatus = 1 WHERE userID = ?");
                            $stmt->execute(array($userid));
                            echo '<div class="alert alert-success">You ativated the member successfuly</div>';
                            header("refresh:2;url=members.php?do=manage");
                            exit();
                        }else{
                            redirectHome("This id not exist",4);
                        }
        
                    ?>
                        </div>
                    <?php
        }elseif($do == 'insert'){

            echo "<h1 class=\"text-center\">Insert member</h1>";
                
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                //get variables from the form
                $pass = $_POST['password'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $fullname = $_POST['fullname'];
                $hpass =  sha1($_POST['password']);

                //check if user exist in database
                $check = checkItem("username","users",$username);
                if ($check == 0) {
                //insert new member into database
                $stmt = $con->prepare("INSERT INTO users (username, password, email, fullname, regstatus, date)
                VALUES (:username, :pass, :email, :fullname, 1, now())");
                $stmt->execute(array(
                    'username' => $username,
                    'pass' => $hpass,
                    'email' => $email,
                    'fullname' => $fullname
                )); 

                echo '<div class="alert alert-success">You add new member successfuly</div>';
                header("refresh:2;url=members.php?do=manage");
                exit();
                }else{
                    echo '<div class="alert alert-danger">This user already exist</div>';
                }
            }else{
                redirectHome("you can not proceed this page directly",5);
            }

        }elseif($do == 'add'){
            //add members page
            echo "<h1 class=\"text-center\">Add member</h1>";
            ?>

                <div class="container">
                    <form class="form-horizontal" action="?do=insert" method="POST">
                    <input type="hidden" name="userid">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="username" class="form-control" autocomplete="off" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="password" name="password" class="form-control" autocomplete="new-password" required="required">
                                <i class="show-pass fa fa-eye"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="email" name="email" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Fullname</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="fullname" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Add" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>

            <?php

        }elseif($do == 'edit'){ 
            
           $userid =  isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            
            $stmt = $con->prepare("SELECT * FROM users WHERE userID = ? LIMIT 1");
            $stmt->execute(array($userid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0) { ?>
        
                <h1 class="text-center">Edit Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="username" class="form-control" value="<?php echo $row['username']; ?>"  autocomplete="off" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="hidden" name="oldpassword" value="<?php echo $row['password']; ?>">
                                <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="leave it if you don't change password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="email" name="email" value="<?php echo $row['email']; ?>" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Fullname</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="fullname" value="<?php echo $row['fullname']; ?>" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
        
       <?php 
            }else{
                redirectHome("no such id",4);
            }
        }elseif($do == 'update'){ 
            //update page
            echo "<h1 class=\"text-center\">Member informations</h1>";
            echo '<div class="container">';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

               //get variables from the form
               $id = $_POST['userid'];
               $username = $_POST['username'];
               $email = $_POST['email'];
               $fullname = $_POST['fullname'];
               //password trick

                $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                //update the database
                $stmt2 = $con->prepare('SELECT * FROM users WHERE
                 username = ? AND userID != ?');    
                $stmt2->execute(array($username,$id));   
                $count = $stmt2->rowCount();

                if ($count == 1) {
                    echo '<div class="alert alert-danger">This user already exist</div>';
                }else{
                    $stmt = $con->prepare("UPDATE users SET username = ?,password = ?, email = ?, fullname = ? WHERE userID = ? LIMIT 1");
                    $stmt->execute(array($username,$pass,$email,$fullname,$id));
                    echo '<div class="alert alert-success">Your informations is updated successfuly</div>';
                    ?>
                    <ul class="list-group">
                        <li class="list-group-item active" aria-current="true"><strong>Your informations</strong></li>
                        <li class="list-group-item"><?php echo $username; ?></li>
                        <li class="list-group-item"><?php echo $email; ?></li>
                        <li class="list-group-item"><?php echo $fullname; ?></li>
                    </ul>
    
                    <?php    
                }            

            }else{
                redirectHome("you can not proceed this page directly");
            }
            echo '</div>';
        }elseif($do = 'delete'){
            //delete member page
            ?>
                <h1 class="text-center">Delete member   </h1>
                <div class="container">
            <?php

                $userid =  isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                $check = checkItem("userid","users",$userid);
                

                if ($check > 0) {
                    //delete member from database
                    $stmt = $con->prepare("DELETE FROM users WHERE userID = ?");
                    $stmt->execute(array($userid));
                    echo '<div class="alert alert-success">You deleted the member successfuly</div>';
                    header("refresh:2;url=members.php?do=manage");
                    exit();
                }else{
                    redirectHome("This id not exist",4);
                }

            ?>
                </div>
            <?php

        }

        include $tpl . "footer.php";

    }else{
        header('Location: index.php');
        exit();
    }