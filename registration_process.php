<?php
include("head.html")
?>
<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'Registration';
try{
$db = new mysqli('localhost', $user, $pass,$db) or die("Unable to connect");
echo "Connected to the database";
echo $_POST['first_name'];

session_start();
	echo $_POST['first_name'];

	$fname = $_POST['first_name'];
	$lname = $_POST['last_name'];
	$username = $_POST['user_name'];
	$password = $_POST['user_password'];
	$email = $_POST['email'];
	$password = md5($password); //md5 has for security


	echo $fname;
	echo $lname;
	echo $username;
	echo $password;
	echo $email;
	


	$sql = "INSERT INTO Registration(fname,lname,username,password,email) VALUES('$fname','$lname','$username','$password','$email')";

	if(mysqli_query($db,$sql)){
		echo "new Record created successfully";
	}
	else{
		echo "Error: ".$sql."<br>".mysqli_error($conn);
	}

	mysqli_close($db);

	$_SESSION['message'] = "You are now logged in";
	$_SESSION['username'] = $username;


}
catch(PDOException $e)
{
	echo "Connection failed: ". $e->getMessage();
}

	


?>

<div class="container">
<div class="col-sm-12">
	Registration process...
</div>
</div><!-- /.container -->
<?php
include("end_page.html")
?>