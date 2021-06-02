<?php

    ob_start();
    session_start();
    $pageTitle = 'Categories';

    if (isset($_SESSION['username'])) {
        include 'init.php';

        $do = isset($_GET['do']) ?  $_GET['do'] : 'manage';

        if ($do == 'manage') {

             //manage ccategories page
             //chhose the order of categories
             $sort = 'ASC';
             $sort_array = array('ASC','DESC');
             if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
                 $sort = $_GET['sort'];
             }
             //select all categories
             $chooseOrdering = 'ASC';
             $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY ordering $sort");
             $stmt2->execute();
             $row = $stmt2->fetchAll();
             ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-dark">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Visibility</td>
                            <td>Allow Comments</td>
                            <td>Allow Ads</td>
                            <td>Control</td>
                        </tr>
                        <?php 
                        
                        foreach ($row as $val) {
                           ?>
                            <tr>
                                <td><?php echo $val['ID']; ?></td>
                                <td><?php echo $val['name']; ?></td>
                                <td><?php  if (empty($val['description'])) {
                                    echo 'No description';
                                }else{
                                    echo $val['description'];
                                }  ?></td>
                                <td><?php  if ($val['visibility'] == 0) {
                                    echo 'Visible ';
                                    echo '<i class="fa fa-eye"></i>';
                                }else{
                                    echo 'Hidden ';
                                    echo '<i class="fa fa-eye-slash"></i>';
                                }  ?></td>
                                <td><?php  if ($val['allowComment'] == 0) {
                                    echo 'Comments Enabled ';
                                    echo '<i class="fa fa-comment"></i>';
                                }else{
                                    echo 'Comments Disabled ';
                                    echo '<i class="fa fa-comment-slash"></i>';
                                }  ?></td>
                                <td><?php  if ($val['allowAds'] == 0) {
                                    echo 'Ads Enabled ';
                                    echo '<i class="fa fa-unlock"></i>';
                                }else{
                                    echo 'Ads Disabled ';
                                    echo '<i class="fa fa-lock"></i>';
                                }  ?></td>
                                <td>
                                <a href="categories.php?do=edit&catid=<?php echo $val['ID']; ?>" class="btn btn-success">Edit</a>
                                <a href="categories.php?do=delete&catid=<?php echo $val['ID']; ?>" class="btn btn-danger confirm">Delete</a>

                                </td>
                            </tr>
                           <?php
                        }
                        
                        ?>
                    </table>
                </div>

                <a href="categories.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New category</a>
                        <a href="?sort=ASC" class="btn btn-info">Ascending</a>
                        <a href="?sort=DESC" class="btn btn-info">Descending</a>
            </div>

             <?php
            
        }elseif($do == 'add'){
            //add categories page
            ?>
            <h1 class="text-center">Add category page</h1>
            <div class="container">
                    <form class="form-horizontal" action="?do=insert" method="POST">
                    <input type="hidden" name="userid">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category Name</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="name" class="form-control" autocomplete="off" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category Desc</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="desc" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="ordering" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Visible</label>
                            <div class="col-sm-10 col-lg-4">
                                <div>
                                    <input id="vis-yes" type="radio" name="visible" value="0" checked> 
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visible" value="1"> 
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Allow Commenting</label>
                            <div class="col-sm-10 col-lg-4">
                                <div>
                                    <input id="com-yes" type="radio" name="comment" value="0" checked> 
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="comment" value="1"> 
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Allow Ads</label>
                            <div class="col-sm-10 col-lg-4">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" checked> 
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1"> 
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Add Category" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>

            <?php

        }elseif($do == 'insert'){


            echo "<h1 class=\"text-center\">Insert Category</h1>";
                
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                //get variables from the form
                $name = $_POST['name'];
                $desc = $_POST['desc'];
                $ordering = $_POST['ordering'];
                $visible = $_POST['visible'];
                $comment = $_POST['comment'];
                $ads = $_POST['ads'];

                //check if user exist in database
                $check = checkItem("name","categories",$name);

                if ($check == 0) {
                //insert new category into database
                $stmt = $con->prepare("INSERT INTO categories (name, description, ordering, visibility, allowComment, allowAds)
                VALUES (:name, :desc, :ordering, :visible, :comment, :ads)");
                $stmt->execute(array(
                    'name' => $name,
                    'desc' => $desc,
                    'ordering' => $ordering,
                    'visible' => $visible,
                    'comment' => $comment,
                    'ads' => $ads
                )); 

                echo '<div class="alert alert-success">You add new category successfuly</div>';
                header("refresh:2;url=categories.php?do=add");
                exit();

                }else{
                    echo '<div class="alert alert-danger">This category already exist</div>';
                }
            }else{
                redirectHome("you can not proceed this page directly",5);
            }

        }elseif($do == 'edit'){
            $catid =  isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
            
            $stmt3 = $con->prepare("SELECT * FROM categories WHERE ID = ? LIMIT 1");
            $stmt3->execute(array($catid));
            $row = $stmt3->fetch();
            $count = $stmt3->rowCount();

            if ($count > 0) { ?>
        
                <h1 class="text-center">Edit Category</h1>
                <div class="container">
                <form class="form-horizontal" action="?do=update" method="POST">
                    <input type="hidden" name="catid"  value="<?php echo $catid; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category Name</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="name" value="<?php echo $row['name']; ?>" class="form-control" autocomplete="off" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category Desc</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="desc" value="<?php echo $row['description']; ?>" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="ordering" value="<?php echo $row['ordering']; ?>" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Visible</label>
                            <div class="col-sm-10 col-lg-4">
                                <div>
                                    <input id="vis-yes" type="radio" name="visible" value="0"
                                     <?php if ($row['visibility'] == 0) {
                                        echo 'checked';
                                    } ?>> 
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visible" value="1"
                                    <?php if ($row['visibility'] == 1) {
                                        echo 'checked';
                                    } ?>> 
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Allow Commenting</label>
                            <div class="col-sm-10 col-lg-4">
                                <div>
                                    <input id="com-yes" type="radio" name="comment" value="0"
                                    <?php if ($row['allowComment'] == 0) {
                                        echo 'checked';
                                    } ?>> 
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="comment" value="1"
                                    <?php if ($row['allowComment'] == 1) {
                                        echo 'checked';
                                    } ?>> 
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Allow Ads</label>
                            <div class="col-sm-10 col-lg-4">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0"
                                    <?php if ($row['allowAds'] == 0) {
                                        echo 'checked';
                                    } ?>> 
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1"
                                    <?php if ($row['allowAds'] == 1) {
                                        echo 'checked';
                                    } ?>> 
                                    <label for="ads-no">No</label>
                                </div>
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
            echo "<h1 class=\"text-center\">Category informations</h1>";
            echo '<div class="container">';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

               //get variables from the form
               $catid = $_POST['catid'];
               $name = $_POST['name'];
               $desc = $_POST['desc'];
               $ordering = $_POST['ordering'];
               $visible = $_POST['visible'];
               $comment = $_POST['comment'];
               $ads = $_POST['ads'];
               
                //update the database
                $stmt4 = $con->prepare("UPDATE categories SET name = ?, description = ?, ordering = ?, visibility = ?, allowComment = ?, allowAds = ?
                 WHERE ID = ? LIMIT 1");
                $stmt4->execute(array($name, $desc,$ordering,$visible,$comment,$ads,$catid));
                echo '<div class="alert alert-success">Your informations is updated successfuly</div>';
                ?>
                <ul class="list-group">
                    <li class="list-group-item active" aria-current="true"><strong>Category informations</strong></li>
                    <li class="list-group-item"><?php echo $name; ?></li>
                    <li class="list-group-item"><?php echo $desc; ?></li>
                    <li class="list-group-item"><?php echo $ordering; ?></li>
                    <li class="list-group-item"><?php echo $visible; ?></li>
                    <li class="list-group-item"><?php echo $comment; ?></li>
                    <li class="list-group-item"><?php echo $ads; ?></li>
                </ul>

                <?php                                

            }else{
                redirectHome("you can not proceed this page directly");
            }
            echo '</div>';

        }elseif($do == 'delete'){
            //delete category page
            ?>
                <h1 class="text-center">Delete category</h1>
                <div class="container">
            <?php

                $catid =  isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
                $check = checkItem("ID","categories",$catid);

                if ($check > 0) {
                    //delete member from database
                    $stmt = $con->prepare("DELETE FROM categories WHERE ID = ?");
                    $stmt->execute(array($catid));
                    echo '<div class="alert alert-success">You deleted the category successfuly</div>';
                    header("refresh:2;url=categories.php?do=manage");
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