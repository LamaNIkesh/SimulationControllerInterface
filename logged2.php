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
if ($flag==1){
?>
<h1>Select an option:</h1>
<form action="visualise2.php" method="post">
<input type="submit" name="option" value="Plot results"><br><br>
</form>
<form action="build.php" method="post">
<input type="submit" name="option" value="Build your own topology"><br><br>
</form>
<form action="use.php" method="post">
<input type="submit" name="option" value="Use the C. elegans topology"><br>
</form>
<br><br>
<?php
}
else{
	?>
	<p>You need to log in to see this page:</p>
	<form action="login.php" method="post">
	<input type="submit" value="Log in">
	</form>
	<br><br>
<?php
}
?>

<?php
include("end_page.html")
?>