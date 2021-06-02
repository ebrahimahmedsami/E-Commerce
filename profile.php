<?php 

    session_start();
    $pageTitle = 'Profile';
    include "init.php";
    if (isset($_SESSION['member'])) {
    
        $userinfo = $con->prepare("SELECT * FROM users WHERE username = ?");
        $userinfo->execute(array($_SESSION['member']));
        $row = $userinfo->fetch();



    ?>
    <h1 class="text-center">My Profile</h1>
    
<div class="information block">
    <div class="container">
        <div class="card border-primary">
        <div class="card-header bg-primary">
            My informations
        </div>
        <div class="card-body">
            <p class="card-text"></p>
            <?php
                echo '<span><strong>Name : </strong>'.$row['username'].'</span>';
                echo '<span><strong>Email : </strong>'.$row['email'].'</span>';
                echo '<span><strong>Full Name : </strong>'.$row['fullname'].'</span>';
                echo '<span><strong>Register Date : </strong>'.$row['date'].'</span>';
                echo '<span><strong>Favourite Category : </strong></span>';
            ?>
            </p>
        </div>
        </div>
    </div>
</div>

<div class="ads block">
<div class="container">
            <div class="card-header bg-primary">
                My ads
            </div>
            <div class="row">
                <?php
                if (!empty(getItem('user_id', $row['userID']))) {
                    foreach (getItem('user_id', $row['userID']) as $item) {

                        echo '<div class="text-center col-xs-12 col-sm-6 col-md-4 cat-info">';
                        ?>
                            <div class="card" style="width: 18rem;">
                                <img class="card-img-top" src="layout/images/computer.png" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $item['name']; ?></h5>
                                    <p class="card-text"><?php echo $item['description']; ?></p>
                                    <p class="card-text">
                                        <span>Price:<br><?php echo $item['price']; ?></span>
                                        <span>Country:<br><?php echo $item['countryMade']; ?></span>
                                        <span>Status:<br><?php
                                        if ($item['status'] == 1) {echo 'New';}
                                        if ($item['status'] == 2) {echo 'Like New';}
                                        if ($item['status'] == 3) {echo 'Used';}
                                        if ($item['status'] == 4) {echo 'Very Old';}
                                        ?></span>
                                    </p>
                                </div>
                            </div>
                        <?php
                        echo '</div>';
                        
                    }
                }else{
                    echo '<span class="text-center">There is no ads, Create <a href="newad.php">new ad</a></span>';
                }
                ?>
    </div>
</div>
</div>

<div class="comment block">
    <div class="container">
        <div class="card border-primary">
        <div class="card-header bg-primary">
            My comments
        </div>
        <div class="card-body">
            <p class="card-text">
            <?php
                    $stmt = $con->prepare("SELECT * FROM comments WHERE user_id = ?");
                    $stmt->execute(array($row['userID']));
                    $row1 = $stmt->fetchAll();

                    if (!empty($row1)) {
                        foreach ($row1 as $com) {
                            echo '<span><strong>Comment : </strong>'.$com['comment'].'</span><br>';
                        }
                    }else{
                        echo '<h5 class="text-center">There is no comments</h5>';
                    }
            ?>
            </p>
        </div>
        </div>
    </div>
</div>
    <?php
    }else{
        redirectHome("you can not proceed this page directly",5);
    }
     include $tpl . "footer.php";

?>