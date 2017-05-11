<?php
include("head.html")
?>
<?php
$file = fopen("Libraries/database.txt","ar") or die("Unable to open database");
$flag=0;
foreach (file("Libraries/database.txt") as $line){
	$name=explode("\t",$line);
	$val= $name[0]== $_POST["user"];
	if ($val) {
		$flag=1;
	}
} 
if ($flag==0){
		fwrite($file, $_POST["user"]);
		fwrite($file, " ");
		fwrite($file, $_POST["pass"]);
		fwrite($file, " ");
		fwrite($file, $_POST["email"]);
		fwrite($file, " ");
		fwrite($file, "0");
		fwrite($file, " ");
		fwrite($file, "0");
		fwrite($file, "\n");
		echo "\n Data file created!";
		$foldername="./" . $_POST["user"];
		if (!mkdir($foldername)){
			die("Fail to creat user folder");
		}
}
else{echo "User already registered";}
fclose($file);
?>
<h1>
Information registered:
</h1>
<p>
Your user name is: <?php echo $_POST["user"]; ?><br>
Your password is: <?php echo $_POST["pass"]; ?><br>
Your email is: <?php echo $_POST["email"]; ?><br>
Other user information can be added here.
Information needs to be added to the data base.
<br><br>

<form action="login.php" method="post">
<input type="submit" value="Go to Log in">
</form>
</p>
<br><br>

<?php
include("end_page.html")
?>