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
$id = $_GET['id'];

if(!isset($_POST['submit']))
{
	require('db.php');
	$user = mysqli_prepare($db, 'SELECT `email`, `admin`, `banned`, `sources` FROM `users` WHERE `id`= ?');
	mysqli_stmt_bind_param($user, 's', $id);
	mysqli_stmt_execute($user);
	++$db_queries;
	mysqli_stmt_store_result($user);
	mysqli_stmt_bind_result($user, $email, $admin, $banned, $sources);
	mysqli_stmt_fetch($user);
	mysqli_stmt_close($user);
	mysqli_close($db);

	//print_r($hostname);

	require('inc/header.php');
	require('inc/delete.php');
	require('inc/footer.php');

} else {

	$email = $_POST['email'];
	
	require('db.php');
	$edit = mysqli_prepare($db, 'DELETE FROM `users` WHERE `email`= ?');
	mysqli_stmt_bind_param($edit, 's',  $email);
	mysqli_stmt_execute($edit);
	++$db_queries;
	mysqli_stmt_close($edit);
	mysqli_close($db);

	require('inc/header.php');
	require('inc/accounts.php');
	require('inc/footer.php');
}
?>