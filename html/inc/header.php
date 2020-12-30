<?php

if (!defined('IN_SCRIPT'))
{
	header('location: ../index.php');
	exit;
}

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo SITE_NAME; ?></title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="header">
		<ul id="navbar">
<?php
if (isset($_SESSION['user']))
{
	if ($_SESSION['admin'])
	{
?>
			<li><a href="register.php">registreren</a></li>
			<li><a href="accounts.php">gebruikers</a></li>
			<li><a href="settings.php">instellingen</a></li>
<?php
	}
?>
			<li><a href="switcher.php">switcher</a></li>
			<li><a href="change.php">verander wachtwoord</a></li>
			<li><a href="logout.php">afmelden</a></li>

<?php
}
else
{
?>
			<li><a href="login.php">inloggen</a></li>
			<!--li><a href="register.php">registeren</a></li-->
<?php
}
?>

		</ul>
		<div id="logo"><?php echo SITE_NAME; ?></div>
	</div>
	
	<div id="subheader">
		<img src="/images/skv_135x65.png">
	</div>
	<div id="main">

