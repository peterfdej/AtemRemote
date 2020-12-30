<?php

if (!defined('IN_SCRIPT'))
{
	header('location: ../index.php');
	exit;
}
?>
	<p class="title">Gebruiker</p>
<?php
//print_r($hostname);
?>
<style>

form {
	margin: 10px;
	padding: 0px;
}
input {
	width: 200px;
	margin: 5px;
	padding: 5px;
	border: 2px;
	border-style: solid;
	background-color : #f3f3f3; 
}
label {
    display: inline-block;
    width:120px;
    text-align: left;
}		
</style>
<div class="box">
<form action="user.php" method="post">
<label>Email:</label> <input type="text" name="email" value="<?php echo $email ?>" /><br>
<label>Admin:</label> <input type="text" name="admin" value="<?php echo $admin ?>" /><br>
<label>Banned:</label> <input type="text" name="banned" value="<?php echo $banned ?>" /><br>
<label>Sources:</label> <input type="text" name="sources" value="<?php echo $sources ?>" /><br><br>
<input type="submit" name="submit" value="Update" />
</div>
</form>





