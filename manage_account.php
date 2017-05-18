
<?php
include("head.html")
?>


<div class = "container">
		<div class="col-sm-12">
<?php
$userFilename = "SimulationXML/".$userLogged;

if ($_SESSION['flag']==1){
	?>
	
	<p> Your user name is: <?php echo $userLogged; ?>.<br>
	Your registered email is: <?php echo $_SESSION['useremail']; ?>.<br>
	<!--You are currently working on simulation number: <?php echo $simNum; ?>.</p>-->

	<p> In this page the user should be able to access his account and manage his/her data (username, password and email) and initialisation files.<br> 
	The user should also be able to delete files, download files and reset the counter for the files.</p><br><br>

	<a href=<?php echo $userFilename ?>> Click here to view your simulation and results files</a>
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

</div></div>

<?php
include("end_page.html")
?>