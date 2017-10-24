
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

	<p> In this page the user should be able to access and manage their data (username, password and email) and initialisation files.<br> 
	The user should also be able to delete files, download files and reset the counter for the files.</p><br><br>

	<p> List of configured simulation which are currently active</p><br>
	<table width  = "600" border = "1" cellpadding = "1" cellspacing = "1">
	<tr>
	<th>Simulation Number</th>
	<th>Simulation Configured</th>
	<th>Simulation Status</th>
	<tr>

	<?php 
		$server = 'localhost';
		$user = 'root';
		$pass = '';
		$db = 'WebInterface';
		$flag = 0;
		try{
			$connection = mysqli_connect("$server",$user,$pass,$db);
			//echo $_POST['user'];
			$result = mysqli_query($connection, "select * from UserSimulation where UserId ='$userLogged'") 
			or die("No user found!!!!".mysql_error());
			while($row = mysqli_fetch_array($result)){
				
				echo "<tr>";
				echo "<td>".$row['SimulationId']."</td>";
				echo "<td>".$row['TimeConfigured']."</td>";
				echo "<td>"."Configured"."</td>";
				echo "<tr>";
			}
			
		}
		catch (Exception $e) {
			echo "error: ".$e->getMessage();
		}

	?>
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