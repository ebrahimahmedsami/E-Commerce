<?php 
    ob_start();

    session_start();
    if(isset($_SESSION['username'])){
        
        $pageTitle = 'Dashboard';
        include 'init.php';


        /* start dashboard page */
        ?>

        <div class="container home-stats">
            <h1 class="text-center">Dshboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat text-center st-members">
                        Total Members
                        <span><a href="members.php"><?php echo countItems('userID','users'); ?></a></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat text-center st-pending">
                        Pending Members
                        <span><a href="members.php?do=manage&page=pending"><?php echo countItems('regstatus','users WHERE regstatus = 0'); ?></a></span>
                    </div>
                </div>
                <div class="col-md-3">
                        <div class="stat text-center st-items">
                            Total Items
                            <span><a href="items.php"><?php echo countItems('itemID','items'); ?></a></span>
                        </div>
                </div>
                <div class="col-md-3">
                        <div class="stat text-center st-comments">
                            Total Comments
                            <span><a href="comments.php"><?php echo countItems('commentID','comments'); ?></a></span>
                        </div>
                </div>
            </div>
        </div>
        <div class="container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                        <i class="fa fa-users"></i> Latest <?php echo $latestUsers = 5; ?> Users
                        <span class="toggle-info float-right">
                            <i class="fa fa-plus"></i>
                        </span>
                        </div>
                        <div class="card-body">
                            <?php 
                            $thelatest = getLatest('*','users','userID',$latestUsers);
                            foreach ($thelatest as $val) {
                                $usernow = $val['username'];
                                $admin = $val['groupID'];
                                if ($admin == 1) {
                                   $usernow = $usernow . '<span style="
                                    position: absolute;
                                    right: 100px;
                                    display: inline-block;
                                    background: #a91111;
                                    color: #fff;
                                    padding: 5px;
                                    border-radius: 12px;
                                    font-size: 11px;">Admin</span>';
                                }else{
                                    $usernow = $usernow . '<span style="
                                    position: absolute;
                                    right: 100px;
                                    display: inline-block;
                                    background: #116511;
                                    color: #fff;
                                    padding: 5px;
                                    border-radius: 12px;
                                    font-size: 11px;">Member</span>';
                                }

                                echo '<ul class="list-unstyled list-group"></ul>';
                                echo '<li class="list-group-item">' . $usernow .
                                     '<a href="members.php?do=edit&userid='.$val['userID'].'">
                                     <span class="btn btn-info float-right" style="
                                     font-size: 13px;
                                     padding: 5px 18px 5px 15px;
                                     font-weight: 700;"><i class="fa fa-edit"></i>Edit</span></a>'
                                . '</li>';
                                echo '</ul>';
                            }
                             ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-tag"></i> Latest Added Items
                        <span class="toggle-info float-right">
                            <i class="fa fa-plus"></i>
                        </span>
                        </div>
                        <div class="card-body">
                        <?php 
                            $thelatest = getLatest('*','items','itemID');
                            foreach ($thelatest as $val) {
                                $itemnow = $val['name'];
                                echo '<ul class="list-unstyled list-group"></ul>';
                                    echo '<li class="list-group-item">' . $itemnow .
                                        '<a href="items.php?do=edit&itemid='.$val['itemID'].'">
                                        <span class="btn btn-info float-right" style="
                                        font-size: 13px;
                                        margin-left:5px;
                                        padding: 5px 18px 5px 15px;
                                        font-weight: 700;"><i class="fa fa-edit"></i>Edit</span></a>';
                                        if ($val['approve'] == 0) {
                                        echo '<a href="items.php?do=approve&itemid='.$val['itemID'].'" 
                                        class="btn btn-warning float-right"><i class="fa fa-check"></i>Approve</a>';
                                        }
                                    echo '</li>';
                                echo '</ul>';
                            }
                             ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-comments"></i> Latest Comments
                        <span class="toggle-info float-right">
                            <i class="fa fa-plus"></i>
                        </span>
                        </div>
                        <div class="card-body">
                        <?php 
                            $stmt = $con->prepare("SELECT comments.*,users.username AS user_name
                            FROM comments
                                INNER JOIN users ON users.userID = comments.user_id");
                            $stmt->execute();
                            $row = $stmt->fetchAll();
                            foreach ($row as $val) {
                                $comment = $val['comment'];
                                $user = $val['user_name'];
                                echo '<div class="comment">';
                                    echo '<span style="display:block;"><h5>'.$user.'</h5>' 
                                    . $comment . '</span>';
                                echo '</div>';
                            }
                             ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php

        include $tpl . "footer.php";

    }else{
        header('Location: index.php');
        exit();
    }

    ob_end_flush();
?>