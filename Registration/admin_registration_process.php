<?php
//var declaration
$server = 'localhost';
$user = 'root';
$pass = '';
#$db = 'Registration'; //database name
$db = 'WebInterface';
$flag = 0;
try{
	$connection = new mysqli('localhost', $user, $pass,$db) or die("Unable to connect");
	#echo "Connected to the database";
	#echo $_POST['first_name'];
	#start session to check if the user is logged in 
	
	#echo $_POST['first_name'];

	$fname = $_POST['first_name'];
	$lname = $_POST['last_name'];
	$username = $_POST['user_name'];
	$password = $_POST['user_password'];
	$email = $_POST['email'];
	$password = md5($password); //md5 has for security
	$datetime = date("Y-m-d H:i:s");

	#echo $fname;
	#echo $lname;
	#echo $username;
	#echo $password;
	#echo $email;
	
	#---Inserting into the database, the table Signup is for storing user registration details
	//--Signup table is inside Registration database

	#$sql = "INSERT INTO Signup(Firstname,Lastname,username,password,email) VALUES('$fname','$lname','$username','$password','$email')";
	// inserting into AdminDetails table

	$sql = "INSERT INTO AdminDetails(FirstName, LastName, UserId, Password, Email,DateCreated) VALUES('$fname','$lname','$username','$password','$email','$datetime')";

	if(mysqli_query($connection,$sql)){
		#echo "new Record created successfully";
		//flag indicates user logged in 
		$_SESSION['flag'] = 1;
		#$_SESSION['message'] = "You are now logged in";
		$_SESSION['username'] = $username;
		$_SESSION['useremail'] = $email;
	}
	else{
		echo "Error: ".$sql."<br>".mysqli_error($connection);
		$flag = 0;
	}

	mysqli_close($connection);

//catching the exception
}
catch(PDOException $e)
{
	echo "Connection failed: ". $e->getMessage();
	$flag = 1;
}
?>

<!-- loggin the user in before loading the page -->
<br>
<div class="container">
	<div class="col-sm-12">
		
		<?php  	if($flag==0){
			?>
			<br>
			<fieldset> 	
			<h2> Signup successfull!!!</p>	
			<a href="../home.php">Go to the interface</a>
		<?php	 
		}
		else{
			echo "Signup unsucessful. Try again!!";
		}
		?>
	</fieldset>

	</div>
</div><!-- /.container -->
