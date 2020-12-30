<?php

require('config.php');
require('common.php');
if (!isset($_SESSION['admin']))
{
	exit_message('You are not authorised to access this page. Please log in.');
}

if (!isset($_SESSION['user']))
{
	exit_message('You are not authorised to access this page. Please log in.');
}
if(!isset($_POST['submit']))
{
	require('db.php');
	$accounts = mysqli_prepare($db, 'SELECT `id`, `email`, `admin`, `banned`, `sources` FROM `users`');
	mysqli_stmt_execute($accounts);
	++$db_queries;
	mysqli_stmt_store_result($accounts);
	mysqli_stmt_bind_result($accounts, $id, $email, $admin, $bannned, $sources);
	mysqli_stmt_fetch($accounts);
	mysqli_stmt_close($accounts);
	mysqli_close($db);

	//print_r($hostname);

	//exit_message('Je bent aangemeld');
	//header("Location: switcher.php");

	require('inc/header.php');
	require('inc/accounts.php');
	require('inc/footer.php');

} else {

	$hostname = $_POST['atemhost'];
	$inputname1 = $_POST['input1'];
	$inputname2 = $_POST['input2'];
	$inputname3 = $_POST['input3'];
	$inputname4 = $_POST['input4'];
	$inputname5 = $_POST['input5'];
	$inputname6 = $_POST['input6'];
	$inputname7 = $_POST['input7'];
	$inputname8 = $_POST['input8'];

	require('db.php');
	$edit = mysqli_prepare($db, 'UPDATE `host` SET `hostname`= ?, `inputname1`= ?, `inputname2`= ? , `inputname3`= ?, `inputname4`= ?, `inputname5`= ?, `inputname6`= ?, `inputname7`= ?, `inputname8`= ? WHERE `ID`= 1');
	mysqli_stmt_bind_param($edit, 'sssssssss', $hostname, $inputname1, $inputname2, $inputname3, $inputname4, $inputname5, $inputname6, $inputname7, $inputname8);
	mysqli_stmt_execute($edit);
	++$db_queries;
	mysqli_stmt_close($edit);
	mysqli_close($db);

	require('inc/header.php');
	require('inc/accounts.php');
	require('inc/footer.php');
}
?>