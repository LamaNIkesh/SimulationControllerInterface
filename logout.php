<?php
include("head.html")
?>


<!--<p align="right" style="color:black; margin-right:300px" -->

<?php 
if (session_status() == PHP_SESSION_NONE){
			session_start();
			$_SESSION['username'] = "";
			$_SESSION['password'] = "";
			$_SESSION['flag'] = 0;
			?><p>User successfully Logged out</p><?php
		}
//echo $userLogged; 
if ($_SESSION['flag'] == 1){
	//echo $_SESSION['username'];
	session_destroy();
	session_start();
	$_SESSION['username'] = "";
	$_SESSION['password'] = "";
	$_SESSION['flag'] = 0;
	?>
	<?php header("Refresh:0");c?>
	<p>User successfully logged out</p><?php
	
}
else{
	?><p>User already logged out</p><?php
}
?>


<?php
include("end_page.html")
?>