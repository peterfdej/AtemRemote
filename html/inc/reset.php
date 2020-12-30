<?php

if (!defined('IN_SCRIPT'))
{
	header('location: ../index.php');
	exit;
}

?>

<div class="box">

	<p class="title">Aanvragen nieuw wachtwoord</p>

	<p>Vul je E-mail in voor een nieuw wachtwoord.</p>

	<form name="reset" method="POST" action="reset.php">
		<input name="email" type="text" placeholder="[email...]" />
		<input name="submit2" type="submit" value="aanvragen nieuw wachtwoord" />
	</form>

</div>