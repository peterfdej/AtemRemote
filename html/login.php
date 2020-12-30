<?php

require('config.php');
require('common.php');

if (!isset($_POST['submit']))
{
	require('inc/header.php');
	require('inc/login.php');
	require('inc/footer.php');
	exit;
}

if (empty($_POST['email']) || empty($_POST['password']))
{
	exit_message('Vul zowel email adres als wachtwoord in');
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
{
	exit_message('Vul een correct email adres in');
}

// user has entered a valid email and a password
$email = $_POST['email'];
//$password = $_POST['password'];
//Get salt
require('db.php');
$user = mysqli_prepare($db, 'SELECT `salt` FROM `users` WHERE `email` = ?');
mysqli_stmt_bind_param($user, 's', $email);
mysqli_stmt_execute($user);
++$db_queries;
mysqli_stmt_store_result($user);
mysqli_stmt_bind_result($user, $salt);
mysqli_stmt_fetch($user);
mysqli_stmt_close($user);
mysqli_close($db);
//printf("Salt: %s\n", $salt);

$password = hash('sha256', $salt . $_POST['password']);
require('db.php');
//printf("Pass: %s\n", $password);
$user = mysqli_prepare($db, 'SELECT `id`, `admin`, `banned`, `sources` FROM `users` WHERE `email` = ? AND `password` = 
?');
mysqli_stmt_bind_param($user, 'ss', $email, $password);
mysqli_stmt_execute($user);
++$db_queries;
mysqli_stmt_store_result($user);

if (mysqli_stmt_num_rows($user) === 0)
{
	exit_message('Er bestaat geen account met dat email adres en wachtwoord');
}

mysqli_stmt_bind_result($user, $id, $admin, $banned, $sources);
mysqli_stmt_fetch($user);
mysqli_stmt_close($user);
mysqli_close($db);

if ($banned === '1')
{
	// user is banned ($banned will return 1);
	exit_message('Dit account is geblokkeerd');
}

$_SESSION['user'] = $id;
$_SESSION['email'] = $email;
$_SESSION['sources'] = $sources;

if ($admin === '1')
{
	// ONLY set this variable if user is an admin ($admin will return 1)
	$_SESSION['admin'] = true;
	//printf("Admin: %s\n", $admin);

}
$ip = $_SERVER['REMOTE_ADDR'];
$logtext = 'LOGIN';
//add logrequire('db.php');
require('db.php');
$log = mysqli_prepare($db, 'INSERT INTO `log` (`ip`, `email`, `logtext`) VALUES (?, ?, ?)');
mysqli_stmt_bind_param($log, 'sss', $ip, $email, $logtext);
mysqli_stmt_execute($log);
++$db_queries;
mysqli_stmt_close($log);
mysqli_close($db);
//exit_message('Je bent aangemeld');
header("Location: switcher.php");

