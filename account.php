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
		$email=$userData[2];
		$val=strval(intval($userData[4]));
		$simNum=trim(preg_replace('/\s\s+/', ' ', $val));
	}
}
}
if ($flag==1){
	?>
	<h1> Manage the account for <?php echo $userLogged; ?> </h1>
	<p> Your registered email is: <?php echo $email; ?>.<br>
	You are currently working on simulation number: <?php echo $simNum; ?>.</p>
	
	<p> In this page the user should be able to access his account and manage his/her data (username, password and email) and initialisation files.</p> 
<hr>
	<p> <form action="download_files.php" method="post">
	If you wish to download previous initialisation files. <input type="submit" value="See files">
	</form></p>
<hr>
	<p> <form action="download_files2.php" method="post">
	If you wish to download previous results files. <input type="submit" value="See files">
	</form></p>
<hr>
	<p>	<form action="Reset_counter.php" method="post" onsubmit="return confirm('Do you really want to reset?');">
	<input type="hidden" name="id" id = "id" value=<?php echo $simNum ;?>>
	Reset your simulation counter. It will delete previous initialisation files. <input type="submit" value="Reset">
	</form> <br><br>
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