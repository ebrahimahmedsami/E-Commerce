<?php

    ob_start();
    session_start();
    $pageTitle = 'Items';
    if (isset($_SESSION['username'])) {
        include 'init.php';

        $do = isset($_GET['do']) ?  $_GET['do'] : 'manage';

        if ($do == 'manage') {
                         //manage items page
                         //select all items
            
                         $stmt = $con->prepare("SELECT items.*,categories.name AS category_name,
                         users.username AS user_name  FROM items
                         INNER JOIN categories ON categories.ID = items.cat_id
                         INNER JOIN users ON users.userID = items.user_id
                         ");
                         $stmt->execute();
                         $items = $stmt->fetchAll();
                         ?>
            
                        <h1 class="text-center">Manage items</h1>
                        <div class="container">
            
                            <div class="table-responsive text-center">
                                <table class="table table-striped table-bordered table-dark">
                                    <tr>
                                        <td>#ID</td>
                                        <td>Name</td>
                                        <td>Description</td>
                                        <td>Price</td>
                                        <td>Add Date</td>
                                        <td>Country</td>
                                        <td>Category</td>
                                        <td>Username</td>
                                        <td>Control</td>
                                    </tr>
                                    <?php 
                                    
                                    foreach ($items as $val) {
                                       ?>
                                        <tr>
                                            <td><?php echo $val['itemID']; ?></td>
                                            <td><?php echo $val['name']; ?></td>
                                            <td><?php echo $val['description']; ?></td>
                                            <td><?php echo $val['price']; ?></td>
                                            <td><?php echo $val['addDate']; ?></td>
                                            <td><?php echo $val['countryMade']; ?></td>
                                            <td><?php echo $val['category_name']; ?></td>
                                            <td><?php echo $val['user_name']; ?></td>
                                            <td>
                                                <a href="items.php?do=edit&itemid=<?php echo $val['itemID']; ?>" class="btn btn-success">Edit</a>
                                                <a href="items.php?do=delete&itemid=<?php echo $val['itemID']; ?>" class="btn btn-danger confirm">Delete</a>
                                                <?php
                                                if ($val['approve'] == 0) {
                                            ?>
                                                <a href="items.php?do=approve&itemid=<?php echo $val['itemID']; ?>" class="btn btn-info">Approve</a>
                                            <?php
                                                }
                                            ?>                                            </td>
                                        </tr>
                                       <?php
                                    }
                                    
                                    ?>
                                </table>
                            </div>
            
                            <a href="items.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New item</a>
                        </div>
            
                         <?php

                    }elseif($do == 'add'){

                        //add items page
                        ?>
                        <h1 class="text-center">Add item page</h1>
                        <div class="container">
                                <form class="form-horizontal" action="?do=insert" method="POST">
                                <input type="hidden" name="userid">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Item Name</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <input type="text" name="itemname" class="form-control" required="required">
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Item Desc</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <input type="text" name="itemdesc" class="form-control" required="required">
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Price</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <input type="text" name="itemprice" class="form-control" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Country Made</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <input type="text" name="itemcountrymade" class="form-control" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Item Status</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <select class="form-control" name="itemstatus">
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Very Old</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Member</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <select class="form-control" name="itemmember">
                                                <?php
                                                     $stmt = $con->prepare("SELECT * FROM users");
                                                     $stmt->execute();
                                                     $users = $stmt->fetchAll();
                                                     foreach ($users as $user) {
                                                         echo '<option value="'.$user['userID'].'">'.$user['username'].'</option>';
                                                     }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Category</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <select class="form-control" name="itemcategory">
                                                <?php
                                                     $stmt2 = $con->prepare("SELECT * FROM categories");
                                                     $stmt2->execute();
                                                     $categories = $stmt2->fetchAll();
                                                     foreach ($categories as $category) {
                                                         echo '<option value="'.$category['ID'].'">'.$category['name'].'</option>';
                                                     }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <input type="submit" value="Add Item" class="btn btn-primary">
                                        </div>
                                    </div>
                                </form>
                            </div>
            
                        <?php

        }elseif($do == 'insert'){
            echo "<h1 class=\"text-center\">Insert Item</h1>";
                
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                //get variables from the form
                $itemname = $_POST['itemname'];
                $itemdesc = $_POST['itemdesc'];
                $itemprice = $_POST['itemprice'];
                $itemcountrymade = $_POST['itemcountrymade'];
                $itemstatus =  $_POST['itemstatus'];

                $itemmember =  $_POST['itemmember'];
                $itemcategory =  $_POST['itemcategory'];

                //insert new item into database
                $stmt = $con->prepare("INSERT INTO items (name, description, price, countryMade, status, addDate, cat_id, user_id)
                VALUES (:itemname, :itemdesc, :itemprice, :itemcountrymade, :itemstatus,NOW(), :itemcategory, :itemmember)");
                $stmt->execute(array(
                    'itemname' => $itemname,
                    'itemdesc' => $itemdesc,
                    'itemprice' => $itemprice,
                    'itemcountrymade' => $itemcountrymade,
                    'itemstatus' => $itemstatus,
                    'itemcategory' => $itemcategory,
                    'itemmember' => $itemmember
                )); 

                echo '<div class="alert alert-success">You add new item successfuly</div>';
                header("refresh:2;url=items.php?do=manage");
                exit();

            }else{
                redirectHome("you can not proceed this page directly",5);
            }

        }elseif($do == 'edit'){
                        
           $itemid =  isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            
           $stmt = $con->prepare("SELECT * FROM items WHERE itemID = ?");
           $stmt->execute(array($itemid));
           $row = $stmt->fetch();
           $count = $stmt->rowCount();

           if ($count > 0) { ?>
       
               <h1 class="text-center">Edit Item</h1>
               <div class="container">
                   <form class="form-horizontal" action="?do=update" method="POST">
                        <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Item Name</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <input type="text" name="itemname" class="form-control" value="<?php echo $row['name']; ?>" required="required">
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Item Desc</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <input type="text" name="itemdesc" class="form-control" value="<?php echo $row['description']; ?>" required="required">
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Price</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <input type="text" name="itemprice" class="form-control" value="<?php echo $row['price']; ?>" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Country Made</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <input type="text" name="itemcountrymade" class="form-control" value="<?php echo $row['countryMade']; ?>" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Item Status</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <select class="form-control" name="itemstatus">
                                                <option value="1" <?php if($row['status'] == 1){echo 'selected';} ?>>New</option>
                                                <option value="2"<?php if($row['status'] == 2){echo 'selected';} ?>>Like New</option>
                                                <option value="3"<?php if($row['status'] == 3){echo 'selected';} ?>>Used</option>
                                                <option value="4"<?php if($row['status'] == 4){echo 'selected';} ?>>Very Old</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Member</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <select class="form-control" name="itemmember">
                                                <?php
                                                     $stmt = $con->prepare("SELECT * FROM users");
                                                     $stmt->execute();
                                                     $users = $stmt->fetchAll();
                                                     foreach ($users as $user) {
                                                         echo '<option value="'.$user['userID'].'">'.$user['username'].'</option>';
                                                     }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Category</label>
                                        <div class="col-sm-10 col-lg-4">
                                            <select class="form-control" name="itemcategory">
                                                <?php
                                                     $stmt2 = $con->prepare("SELECT * FROM categories");
                                                     $stmt2->execute();
                                                     $categories = $stmt2->fetchAll();
                                                     foreach ($categories as $category) {
                                                         echo '<option value="'.$category['ID'].'">'.$category['name'].'</option>';
                                                     }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <input type="submit" value="Edit Item" class="btn btn-primary">
                                        </div>
                                    </div>
                   </form>

                    <?php
                    //show all comments in edit item
    
                    $stmt = $con->prepare("SELECT comments.*,users.username AS user_name
                    FROM comments
                        INNER JOIN users ON users.userID = comments.user_id
                        WHERE item_id = ?");
                    $stmt->execute(array($itemid));
                    $row1 = $stmt->fetchAll();

                    if (!empty($row1)) {
                        
                    
                    ?>
    
                <h3 class="text-center">Manage [ <?php echo $row['name']; ?> ] Comments</h3>
    
                    <div class="table-responsive text-center">
                        <table class="table table-striped table-bordered table-dark">
                            <tr>
                                <td>Comment</td>
                                <td>User</td>
                                <td>Add Date</td>
                                <td>Control</td>
                            </tr>
                            <?php 
                            
                            foreach ($row1 as $val) {
                                ?>
                                <tr>
                                    <td><?php echo $val['comment']; ?></td>
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
                    <?php
                        }else{
                            echo '<h5 class="text-center" style="
                            margin-bottom:20px;
                            border-radius:5px;
                            padding:20px;
                            background:#791212;
                            color:#fff">No comments</h5>';
                        }
                    ?>

               </div>
       
      <?php 
           }else{
               redirectHome("no such id",4);
           }

        }elseif($do == 'update'){
            //update page
            echo "<h1 class=\"text-center\">Item informations</h1>";
            echo '<div class="container">';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

               //get variables from the form
               $itemid = $_POST['itemid'];
               $itemname = $_POST['itemname'];
               $itemdesc = $_POST['itemdesc'];
               $itemprice = $_POST['itemprice'];
               $itemcountrymade = $_POST['itemcountrymade'];
               $itemstatus = $_POST['itemstatus'];
               $itemmember = $_POST['itemmember'];
               $itemcategory = $_POST['itemcategory'];

                //update the database
                $stmt = $con->prepare("UPDATE items SET name = ?,description = ?, price = ?,
                countryMade = ?, status = ?, cat_id = ?, user_id = ?
                 WHERE itemID = ?");
                $stmt->execute(array($itemname,$itemdesc,$itemprice,$itemcountrymade,$itemstatus,
                $itemcategory,$itemmember,$itemid));
                echo '<div class="alert alert-success">Your informations is updated successfuly</div>';
                ?>
                <ul class="list-group">
                    <li class="list-group-item active" aria-current="true"><strong>Item informations</strong></li>
                    <li class="list-group-item"><?php echo $itemname; ?></li>
                    <li class="list-group-item"><?php echo $itemdesc; ?></li>
                    <li class="list-group-item"><?php echo $itemprice; ?></li>
                    <li class="list-group-item"><?php echo $itemcountrymade; ?></li>
                    <li class="list-group-item"><?php echo $itemstatus; ?></li>
                    <li class="list-group-item"><?php echo $itemcategory; ?></li>
                    <li class="list-group-item"><?php echo $itemmember; ?></li>
                </ul>

                <?php                                

            }else{
                redirectHome("you can not proceed this page directly");
            }
            echo '</div>';

        }elseif($do == 'delete'){
                //delete item page
                ?>
                <h1 class="text-center">Delete item</h1>
                <div class="container">
            <?php

                $itemid =  isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
                $check = checkItem("itemID","items",$itemid);

                if ($check > 0) {
                    //delete item from database
                    $stmt = $con->prepare("DELETE FROM items WHERE itemID = ?");
                    $stmt->execute(array($itemid));
                    echo '<div class="alert alert-success">You deleted the item successfuly</div>';
                    header("refresh:2;url=items.php?do=manage");
                    exit();
                }else{
                    redirectHome("This id not exist",4);
                }

            ?>
                </div>
            <?php
    

        }elseif($do == 'approve'){
                //activate item page
                ?>
                <h1 class="text-center">Approve item</h1>
                <div class="container">
            <?php

                $itemid =  isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
                $check = checkItem("itemID","items",$itemid);

                if ($check > 0) {
                    //activate item from database
                    $stmt = $con->prepare("UPDATE items SET approve = 1 WHERE itemID = ?");
                    $stmt->execute(array($itemid));
                    echo '<div class="alert alert-success">You ativated the item successfuly</div>';
                    header("refresh:2;url=items.php?do=manage");
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