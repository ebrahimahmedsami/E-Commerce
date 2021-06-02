<?php

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
    echo "<div class='alert alert-danger'>$errorMsg</div>";
    echo "<div class='alert alert-info'>You will be directed to home page after $seconds seconds</div></div>";
    header("refresh:$seconds;url=index.php");
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