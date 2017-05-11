<?php
include("head.html")
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$pass=0;
	$name = $_POST["user"];
	$nameErr = "";
     // check if name only contains letters and whitespace
     if (!preg_match("/^[a-zA-Z]*$/",$name)) {
       $nameErr = "Only letters allowed";
	   $pass=1;
     }
	$email = $_POST["email"];
	$emailErr = "";
     // check if e-mail address is well-formed
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$emailErr = "Invalid email format"; 
	   $pass=2;
	}
	$pass = $_POST["pass"];
	$passErr = "";
	$passconf = $_POST["passconf"];
	if ($pass != $passconf){
		$passErr = "Passwords do not match";
	   $pass=3;
	}
	if ($pass == 0) {
		?>
		<p> Data validated </p>
		<form action="registered.php" method="post">
		<input type="hidden" name="user" value= <?php echo $name; ?>>
		<input type="hidden" name="pass" value= <?php echo $pass; ?>>
		<input type="hidden" name="email" value= <?php echo $email; ?>>
		<input type="submit" value="Next"> <br><br>
		</form>
		<?php
	}
	else {
		?>
		<p> There was an error in the data submitted:</p>
		<?php 
		if ($pass ==1){
		?>
		<p> Only letters allowed in the user name</p>
		<?php
		}
		if ($pass ==2){
		?>
		<p> Invalid email format</p>
		<?php
		}
		if ($pass ==3){
		?>
		<p> Passwords do not match</p><br>
		<?php
		}
		?> 
		<form action="register.php" method="post">
		<input type='hidden' name='notsubmitted' id='notsubmitted' value='1'/>
		<input type="submit" value="Try again">
		</form>
		<br><br>
		<?php
	}
}
else{
?>
<p> Data not submitted</p>
<form action="register.php" method="post">
<input type='hidden' name='notsubmitted' id='notsubmitted' value='1'/>
<input type="submit" value="Try again">
</form>
<?php
}
?>
<?php
include("end_page.html")
?>