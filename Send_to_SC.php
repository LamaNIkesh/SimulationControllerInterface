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
		$userID = $userLogged . $simNum;
	}
}
}
if ($flag==1){
	?>
	<p> This page should have sent the HEX file to the Simulation Controller. Since the XML2HEX should happen in the IM already, this is still not done.</p>
	<p> Once the SC calculates the simulation, it will send the results back. The results back should contain the name of the file.</p>
	<form action="plot_data_test2.php" method="post">
	<input type="hidden" name="plotfile" id = "plotfile" value=<?php echo "/Spike_train_" . $userLogged . "1" . ".xml" ;?>>
	<input type="submit" value="Plot the results">
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