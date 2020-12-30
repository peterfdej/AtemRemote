<?php

if (!defined('IN_SCRIPT'))
{
	header('location: ../index.php');
	exit;
}

?>

<div class="box">

	<p class="title">Verander wachtwoord</p>

	<form name="change" method="POST" action="change.php">
		<input name="password" type="password" placeholder="[wachtwoord... (8 karakters minimaal)]" />
		<input name="password-confirm" type="password" placeholder="[bevestig wachtwoord... (8 karakters minimaal)]" />
		<input name="submit" type="submit" value="Bevestig" />
	</form>

</div>
