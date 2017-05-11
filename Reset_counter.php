<?php
include("head.html")
?>
<?php
$flag=0;
if (file_exists ("Libraries/database.txt")){
$data= file("Libraries/database.txt");
$file = fopen("Libraries/database.txt","w");	
for ($line = 0; $line < count($data); ++$line){
	$userData=explode(" ",$data[$line]);
	if ($userData[3]=="1"){
		$flag=1;
		$userLogged=$userData[0];
		$userData[4]=0;
		$val=strval(intval($userData[4]));
		$simNum=trim(preg_replace('/\s\s+/', ' ', $val));
		$newline=implode(" ",$userData);
		fwrite($file,$newline);
	}
}
}
if ($flag==1){
	?>
	<?php
	for ($i = 1; $i < $_POST["id"]+1; ++$i){
		$name= $userLogged . "/Initialisation_file_" . $userLogged . $i . ".xml";
		unlink($name);
	} 
	?>
	<p> Your counter has been reset. Previous Initialisation files will be overwritten. <br>
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