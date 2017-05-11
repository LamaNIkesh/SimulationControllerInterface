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
	<h1> Spike result files</h1>
	<p> This is a list of the result files to download from your profile.</p>
	<?php
	$files = scandir($userLogged);
	$num_files = count($files)-2;
	for ($i = 1; $i < $num_files+1; ++$i){
		if (file_exists($userLogged . "/Spike_train_" . $userLogged . $i . ".xml")){
		?>
			<hr>
			<a id="file" href=<?php echo $userLogged . "/Spike_train_" . $userLogged . $i . ".xml" ;?> download="Spike_train.xml">Spike train file <?php echo $i; ?>.</a>
			<br>
		<?php
		}
	} 
	?>
	<hr>
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