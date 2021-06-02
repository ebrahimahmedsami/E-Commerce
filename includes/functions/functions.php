<?php
//functions from main work
/* functions to get categories from database */
function getCat(){
    global $con;

    $getcat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");    
    $getcat->execute();
    return $getcat->fetchAll();
}

/* functions to get items from database */
function getItem($where, $value){
    global $con;

    $getitem = $con->prepare("SELECT * FROM items WHERE $where = ? ORDER BY itemID DESC");    
    $getitem->execute(array($value));
    return $getitem->fetchAll();
}

//function check user status
function checkUserStatus($user){
    global $con;

        $stmtx = $con->prepare("SELECT username, regstatus FROM users
        WHERE username = ? AND regstatus = 0");
        $stmtx->execute(array($user));
        $status = $stmtx->rowCount();
        return $status;
}

//functions from admin work
/* title function */
function getTitle(){
    global $pageTitle;

    if (isset($pageTitle)) {
        echo $pageTitle;
    }else{
        echo 'Defult';
    }
}

/*redirect function if there any error*/
function redirectHome($errorMsg,$seconds = 3){
    echo '<div class="container">';
    echo "<div class='alert alert-danger'>$errorMsg</div>";
    echo "<div class='alert alert-info'>You will be directed to home page after $seconds seconds</div></div>";
    echo '</div>';
    header("refresh:$seconds;url=login.php");
    exit();
}

/*function to check item in database*/
function checkItem($select, $from, $value){
    global $con;

    $stmt2 = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $stmt2->execute(array($value));
    $count = $stmt2->rowCount();

    return $count;
}

/* function to calculate the number of items */
function countItems($item, $table){
    global $con;
    
    $stmt3 = $con->prepare("SELECT COUNT($item) FROM $table");
    $stmt3->execute();
    return $stmt3->fetchColumn();
}

/* functions to get latest records */
function getLatest($select, $table, $order, $limit = 5){
    global $con;

    $getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");    
    $getstmt->execute();
    return $getstmt->fetchAll();
}