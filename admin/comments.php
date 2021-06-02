<?php

    ob_start();
    session_start();
    $pageTitle = 'Comments';
    if (isset($_SESSION['username'])) {
        include 'init.php';

        $do = isset($_GET['do']) ?  $_GET['do'] : 'manage';

        if ($do == 'manage') {
                    //manage comments page
                    //select all comments
    
                    $stmt = $con->prepare("SELECT comments.*,items.name AS item_name,
                        users.username AS user_name
                    FROM comments
                        INNER JOIN items ON items.itemID = comments.item_id
                        INNER JOIN users ON users.userID = comments.user_id");
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                    ?>
    
                <h1 class="text-center">Manage Comments</h1>
                <div class="container">
    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-dark">
                            <tr>
                                <td>#ID</td>
                                <td>Comment</td>
                                <td>Item</td>
                                <td>User</td>
                                <td>Add Date</td>
                                <td>Control</td>
                            </tr>
                            <?php 
                            
                            foreach ($row as $val) {
                                ?>
                                <tr>
                                    <td><?php echo $val['commentID']; ?></td>
                                    <td><?php echo $val['comment']; ?></td>
                                    <td><?php echo $val['item_name']; ?></td>
                                    <td><?php echo $val['user_name']; ?></td>
                                    <td><?php echo $val['addDate']; ?></td>
                                    <td>
                                        <a href="comments.php?do=edit&commentid=<?php echo $val['commentID']; ?>" class="btn btn-success">Edit</a>
                                        <a href="comments.php?do=delete&commentid=<?php echo $val['commentID']; ?>" class="btn btn-danger confirm">Delete</a>
                                        <?php
                                            if ($val['status'] == 0) {
                                        ?>
                                            <a href="comments.php?do=approve&commentid=<?php echo $val['commentID']; ?>" class="btn btn-info">Approve</a>
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
    
                </div>
    
                    <?php
            
        }elseif($do == 'edit'){

                        
           $commentid =  isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
            
           $stmt = $con->prepare("SELECT * FROM comments WHERE commentID = ?");
           $stmt->execute(array($commentid));
           $row = $stmt->fetch();
           $count = $stmt->rowCount();

           if ($count > 0) { ?>
       
               <h1 class="text-center">Edit Comment</h1>
               <div class="container">
                   <form class="form-horizontal" action="?do=update" method="POST">
                   <input type="hidden" name="commentid" value="<?php echo $commentid; ?>">
                       <div class="form-group">
                           <label class="col-sm-2 control-label">Comment</label>
                           <div class="col-sm-10 col-lg-4">
                               <textarea name="comment" class="form-control"><?php echo $row['comment']; ?></textarea>
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
                        echo "<h1 class=\"text-center\">Comment informations</h1>";
                        echo '<div class="container">';
            
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
                           //get variables from the form
                           $commentid = $_POST['commentid'];
                           $comment = $_POST['comment'];

                            //update the database
                            $stmt = $con->prepare("UPDATE comments SET comment = ?,addDate = NOW() WHERE commentID = ?");
                            $stmt->execute(array($comment,$commentid));
                            echo '<div class="alert alert-success">Comment is updated successfuly</div>';
                            ?>
                            <ul class="list-group">
                                <li class="list-group-item active" aria-current="true"><strong>Your Comment</strong></li>
                                <li class="list-group-item"><?php echo $comment; ?></li>
                            </ul>
            
                            <?php                                
            
                        }else{
                            redirectHome("you can not proceed this page directly");
                        }
                        echo '</div>';

         }elseif($do == 'delete'){

                        //delete comments page
                        ?>
                        <h1 class="text-center">Delete Comment</h1>
                        <div class="container">
                    <?php
        
                        $commentid =  isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
                        $check = checkItem("commentID","comments",$commentid);
                        
        
                        if ($check > 0) {
                            //delete comment from database
                            $stmt = $con->prepare("DELETE FROM comments WHERE commentID = ?");
                            $stmt->execute(array($commentid));
                            echo '<div class="alert alert-success">You deleted the comment successfuly</div>';
                            header("refresh:2;url=comments.php?do=manage");
                            exit();
                        }else{
                            redirectHome("This id not exist",4);
                        }
        
                    ?>
                        </div>
                    <?php
        

        }elseif($do == 'approve'){

                //approve comment page
                ?>
                <h1 class="text-center">Approve comment</h1>
                <div class="container">
            <?php

                $commentid =  isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0;
                $check = checkItem("commentID","comments",$commentid);

                if ($check > 0) {
                    //approve comment from database
                    $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE commentID = ?");
                    $stmt->execute(array($commentid));
                    echo '<div class="alert alert-success">You approve the comment successfuly</div>';
                    header("refresh:2;url=comments.php?do=manage");
                    exit();
                }else{
                    redirectHome("This id not exist",4);
                }

            ?>
                </div>
            <?php

        }

        include $tpl . 'footer.php';
    
    }else{
        header('Location: index.php');
        exit();
    }
ob_end_flush();
?>