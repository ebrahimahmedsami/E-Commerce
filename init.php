<?php
//error reporting
ini_set('siaplay_errors','On');
error_reporting(E_ALL);

include 'admin/connect.php';

//Routes

$tpl = "includes/templates/"; //templates directory
$css = "layout/css/"; //css directory
$js = "layout/js/"; //css directory
$func = "includes/functions/"; //functions directory
//important files

include $func . "functions.php";
include "includes/languages/english.php";
include $tpl . "header.php";