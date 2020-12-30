<?php

if (!defined('IN_SCRIPT'))
{
	header('location: ../index.php');
	exit;
}
?>
	<p class="title">Instellingen</p>
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
<form action="settings.php" method="post">
<label>Socket server ip:</label> <input type="text" name="atemhost" value="<?php echo $hostname ?>" /><br>
<label>Ingang 1:</label> <input type="text" name="input1" value="<?php echo $inputname1 ?>" /><br>
<label>Ingang 2:</label> <input type="text" name="input2" value="<?php echo $inputname2 ?>" /><br>
<label>Ingang 3:</label> <input type="text" name="input3" value="<?php echo $inputname3 ?>" /><br>
<label>Ingang 4:</label> <input type="text" name="input4" value="<?php echo $inputname4 ?>" /><br>
<label>Ingang 5:</label> <input type="text" name="input5" value="<?php echo $inputname5 ?>" /><br>
<label>Ingang 6:</label> <input type="text" name="input6" value="<?php echo $inputname6 ?>" /><br>
<label>Ingang 7:</label> <input type="text" name="input7" value="<?php echo $inputname7 ?>" /><br>
<label>Ingang 8:</label> <input type="text" name="input8" value="<?php echo $inputname8 ?>" /><br><br>
<input type="submit" name="submit" value="Update" />
</div>
</form>





