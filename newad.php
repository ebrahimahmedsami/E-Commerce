<?php 

    session_start();
    $pageTitle = 'New item';
    include "init.php";
    if (isset($_SESSION['member'])) {
    
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

               $itemname = filter_var($_POST['itemname'], FILTER_SANITIZE_STRING);
               $itemdesc = filter_var($_POST['itemdesc'], FILTER_SANITIZE_STRING);
               $itemprice = filter_var($_POST['itemprice'], FILTER_SANITIZE_NUMBER_INT);
               $itemcountrymade = filter_var($_POST['itemcountrymade'], FILTER_SANITIZE_STRING);
               $itemstatus = $_POST['itemstatus'];
               $itemcategory = $_POST['itemcategory'];
               $itemmember = $_SESSION['id'];

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
                header("refresh:2;url=newad.php");
                exit();

            }


            


    ?>
                        
    <h1 class="text-center">Create new item</h1>
    <div class="container">
            <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="POST">
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

    }else{
        redirectHome("you can not proceed this page directly",5);
    }
     include $tpl . "footer.php";

?>