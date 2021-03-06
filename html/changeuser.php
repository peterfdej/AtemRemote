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
if (!isset($_POST['submit']))
{
	$_SESSION['id'] = $id;
	require('inc/header.php');
	require('inc/changeuser.php');
	require('inc/footer.php');
	exit;
}

if (empty($_POST['password']) || empty($_POST['password-confirm']))
{
	exit_message('Vul beide velden in');
}

if (strlen($_POST['password']) < 8)
{
	exit_message('Wachtwoord is te kort (8 karakters minimaal)');
}

if ($_POST['password'] !== $_POST['password-confirm'])
{
	exit_message('Het wachtwoord en de bevesting komen niet overeen');
}
$id = $_SESSION['id'];
require('db.php');
$query = mysqli_prepare($db, 'SELECT `salt` FROM `users` WHERE `id` = ?');
mysqli_stmt_bind_param($query, 'i', $id);
mysqli_stmt_execute($query);
++$db_queries;
mysqli_stmt_store_result($query);
mysqli_stmt_bind_result($query, $salt);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);
mysqli_close($db);
//printf("Salt: %s\n", $salt);

$password = hash('sha256', $salt . $_POST['password']);
require('db.php');

// update user in DB
$change = mysqli_prepare($db, 'UPDATE `users` SET `password` = ? WHERE `id` = ?');
mysqli_stmt_bind_param($change, 'si', $password, $id);
mysqli_stmt_execute($change);
++$db_queries;
mysqli_stmt_close($change);
mysqli_close($db);

exit_message('Nieuw wachtwoord is ingesteld');