<?php

if (!defined('IN_SCRIPT'))
{
	header('location: ../index.php');
	exit;
}
?>
	<p class="title">Gebruikers</p>
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
table, th, td {
	border: 1px solid black;
	border-collapse: collapse;
}	
</style>
<div class="box">

<table border="1">
	<tr>
		<td style="width:300px">e-mail</td>
		<td style="width:50px">admin</td>
		<td style="width:60px">bannned</td>
		<td style="width:150px">sources</td>
		<td style="width:50px">Edit</td>
		<td style="width:50px">Delete</td>
		<td style="width:50px"></td>
	</tr>
<?php
require('db.php');
$records = mysqli_query($db,"select * from users");
while($data = mysqli_fetch_array($records))
{
?>
	<tr>
		<td><?php echo $data['email']; ?></td>
		<td><?php echo $data['admin']; ?></td>
		<td><?php echo $data['banned']; ?></td>
		<td><?php echo $data['sources']; ?></td>
		<td><a href="user.php?id=<?php echo $data['id']; ?>">Edit</a></td>
		<td><a href="delete.php?id=<?php echo $data['id']; ?>">Delete</a></td>
		<td><a href="changeuser.php?id=<?php echo $data['id']; ?>">Wachtwoord</a></td>
	</tr>
<?php
}
?>
</table>
</div>

</form>





