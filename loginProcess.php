<?php
include("head.html")
?>
<div id = "content">
<?php
 
	$flag = 0;
	$username = $_POST['user'];
	$password = $_POST['pass'];

//to prevent sql injection
//$username = stripclashes($username);
//$password = stripclashes($password);
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);

//connect to the server  and select database

	mysql_connect("localhost", "root","");
	mysql_select_db("login");

//query the database for user
	$result = mysql_query("select * from  users where username ='$username' and password = '$password'") 
			or die("No user found!!!!".mysql_error());
	$row = mysql_fetch_array($result);
	
	if($row['username']== $username && $row['password'] == $password){
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$flag = 1;
		$_SESSION['flag'] = $flag;
		//header("Refresh:0");
		header("Refresh:0; url=home.php");
		echo "Login successful ";
		
	}
	else{
	echo "Login unsucessful!!!. Please check your username and password";
}
echo $_SESSION['username'];



?>
<?php
include("end_page.html")
?>