<?php
include("head.html")
?>
<?php
$flag=0;
if (file_exists ("Libraries/database.txt")){
$data= file("Libraries/database.txt");
for ($line = 0; $line < count($data); ++$line){
	$userData=explode(" ",$data[$line]);
	if ($userData[3]=="1"){
		$flag=1;
		$userLogged=$userData[0];
	}
}
}
if ($flag==0){
?>
<div class= "container" >
<br><br>
<form action="loginProcess.php" method="post">
<fieldset>
<legend>Log in</legend>
<br>User name:<br><input type="text" name="user" required><br><br>
Password:<br><input type="password" name="pass" required><br><br>
<input type="submit" value = "login">
</fieldset>
</form>
<br><br>
</div>
<?php
}
else{
	?>
	<p>User already logged in.</p>
	<form action="logged2.php" method="post">
	<input type="submit" value="Go to Builder/Viewer">
	</form>
	</p>
	<br><br>
	<?php
}
?>
<?php
include("end_page.html")
?>