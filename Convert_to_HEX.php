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
<h2> This page is a test.</h2>
<h3> The file should have been sent to the IM for conversion and simulation.</h3>
<h3> If there is an error in the conversion it'll be shown in the next page.</h3>

<p> This page runs the Python program DEC2HEX from the IM with the Initialisation file created.</p>
<p> It should display any errors found in the creation of the initialisation file or the conversion. </p>

<?php
$nameHEX= $_POST['filenameHEX'];
$nameXML = $_POST['filenameXML'];
$output = exec("python XML2HEXLib.py -h $nameXML $nameHEX");
// echo $output;
?>
<hr>
<?php
$ErrorLog = simplexml_load_file ('ErrorLog.xml');
foreach ($ErrorLog->Errors as $error){
	echo $error;
}
rename( 'ErrorLog.xml' , $userLogged . "/" . 'ErrorLog' . $userID . '.xml');
?>
<hr>

<p> The file created, <?php echo $nameXML ?>, has been converted to HEX. </p>
<br><br>
<form action="Send_to_SC.php" method="post">
<input type="submit" value="Send initialisation data to server">
<input type="hidden" name="filenameHEX" id = "filenameHEX" value=<?php echo $nameHEX ?>>
<input type="hidden" name="filenameXML" id = "filenameXML" value=<?php echo $nameXML ?>>
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