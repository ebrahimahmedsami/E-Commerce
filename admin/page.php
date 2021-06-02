<?php

$do = '';

if(isset($_GET['do']))
{
    $do = $_GET['do'];
}else{
    $do = 'manage';
}

// if main page
if ($do =='manage') {

    echo 'Welcome In Manage Category Page';
    echo '<a href="?do=add">Add New Category</a>';

}elseif($do == 'add'){

    echo 'Welcome In Add Category Page';

}elseif($do == 'insert'){

    echo 'Welcome In Insert Category Page';

}else{
    echo 'There is no page with this name';
}