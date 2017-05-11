<?php
include("head.html")
?>
<?php
$flag=0;
if (file_exists ("database.txt")){
$data= file("database.txt");
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
<h3>Upload a XML file with the results from a simulation to be plotted. </h3>

<form action="visualise_action.php" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Select XML file to upload:</legend>
<br>
<input type="file" name="uploadfile" accept=".xml">
<br><br>
<input type="submit" value="Upload file" name="submit"><br>
</fieldset>
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