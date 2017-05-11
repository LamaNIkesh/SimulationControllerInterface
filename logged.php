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
		$flag=2;
		$islogged=1;
		$userLogged=$userData[0];
	}
}
if ($flag==0){
$file = fopen("database.txt","w");	
for ($line = 0; $line < count($data); ++$line){
	$userData=explode(" ",$data[$line]);
	$name=$userData[0];
	$password=$userData[1];
	if ($name == $_POST["user"] and $password == $_POST["pass"]) {
		$flag=1;
		$name1=$userData[0];
		$email1=$userData[2];
		$userData[3]="1";
		$islogged=1;
		$newline=implode(" ",$userData);
		fwrite($file,$newline);
	}
	else{
		$islogged=$userData[3];
		fwrite($file,$data[$line]);
		}
}
fclose($file);
}
}
if ($flag == 1){
	?>
<h1>
Information submitted:
</h1>
<p>

Your user name is: <?php echo $name1; ?><br>
Your email is: <?php echo $email1; ?><br>
Other user information can be added here.
<br><br>

<form action="logged2.php" method="post">
<input type="submit" value="Go to Builder/Viewer">
</form>
</p>
<br><br>
<?php
}
elseif ($flag == 2){
	?>
<p>
User <?php 	echo $userLogged;?> already logged in.<br>Log out to change user.<br>
Other user information can be added here.
<form action="logged2.php" method="post">
<input type="submit" value="Go to Builder/Viewer">
</form>
</p>
<br><br>
<?php
}
else{
	?>
	<p>The user does not exist or the password does not match</p>
	<form action="login.php" method="post">
	<input type="submit" value="Log in again">
	</form>
	</p>
	<br><br>
	<?php
}
?>
<?php
include("end_page.html")
?>