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
		$val=strval(intval($userData[4]));
		$simNum=trim(preg_replace('/\s\s+/', ' ', $val));
	}
}
}
if ($flag==1){
	?>
	<h1> Initialisation files</h1>
	<h3> This is a list of the initialisation files created </h3>
	<?php
	if ($simNum==0){
		?>
		<hr>
		<p> There are no initialisation files to display</p>
		<hr>
		<?php
	}
	for ($i = 1; $i < $simNum+1; ++$i){
	?>
	<hr>
	<a id="file" href=<?php echo $userLogged . "/Initialisation_file_" . $userLogged . $i . ".xml" ;?> download="Initialisation_file.xml">Save initialisation file <?php echo $i; ?> to your computer.</a>
	<br>
	<?php
	} 
	?>
	<br>
	<form action="account.php" method="post">
	<input type="submit" value="Go back to your account">
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