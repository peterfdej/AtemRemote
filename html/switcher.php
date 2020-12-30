<?php

require('config.php');
require('common.php');

if (!isset($_SESSION['user']))
{
	exit_message('You are no authorised to access this page. Please log in.');
}

$user = $_SESSION['user'];
$email = $_SESSION['email'];
$sources = $_SESSION['sources'];

require('db.php');
$switcher = mysqli_prepare($db, 'SELECT `hostname`, `inputname1`, `inputname2`, `inputname3`, `inputname4`, `inputname5`, `inputname6`, `inputname7`, `inputname8` FROM `host` WHERE `ID`=1');
mysqli_stmt_execute($switcher);
++$db_queries;
mysqli_stmt_store_result($switcher);
mysqli_stmt_bind_result($switcher, $hostname, $inputname1, $inputname2, $inputname3, $inputname4, $inputname5, $inputname6, $inputname7, $inputname8);
mysqli_stmt_fetch($switcher);
mysqli_stmt_close($switcher);
mysqli_close($db);
//print_r("host".$hostname);

require('inc/header.php');
require('inc/switcher.php');
require('inc/footer.php');

?>