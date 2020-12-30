<?php

require('config.php');
require('common.php');

if (!isset($_POST['submit2']))
{
	require('inc/header.php');
	require('inc/reset.php');
	require('inc/footer.php');
	exit;
}

if (empty($_POST['email']))
{
	exit_message('Vul een email adres in');
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
{
	exit_message('Vul een correct email adres in');
}

// user has entered a valid email and a password
$email = $_POST['email'];
//Get salt
require('db.php');
$user = mysqli_prepare($db, 'SELECT  `salt` FROM `users` WHERE `email` = ?');
mysqli_stmt_bind_param($user, 's', $email);
mysqli_stmt_execute($user);
++$db_queries;
mysqli_stmt_store_result($user);
if (mysqli_stmt_num_rows($user) === 0)
{
	exit_message('Er bestaat geen account met dat email adres');
}
mysqli_stmt_bind_result($user, $salt);
mysqli_stmt_fetch($user);
mysqli_stmt_close($user);

// Generating Password
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
$newpassword = substr( str_shuffle( $chars ), 0, 8 );
$password = hash('sha256', $salt . $newpassword);
//printf("user: %s\n", $email);
//printf("New password: %s\n", $newpassword);

// update user in DB
$change = mysqli_prepare($db, 'UPDATE `users` SET `password` = ? WHERE `email` = ?');
mysqli_stmt_bind_param($change, 'ss', $password, $email);
mysqli_stmt_execute($change);
++$db_queries;
mysqli_stmt_close($change);
mysqli_close($db);

mail($email, 'Nieuw wachtwoord ingesteld.', 'Je nieuwe wachtwoord voor ' . SITE_NAME . ' is: ' .$newpassword, 'FROM: resetpass <'. RESET_EMAIL .'>');

exit_message('Een nieuw wachtwoord is verzonden naar: ' .$email . ' . Controleer ook de spam folder');

