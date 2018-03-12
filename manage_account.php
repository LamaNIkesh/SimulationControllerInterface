
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
	<!-- little style sheet for the table -->
	<style>
		table {
		    border-collapse: collapse;
		    width: 100%;
		}

		th, td {
		    text-align: center;
		    padding: 8px;
		}

		tr:nth-child(even){background-color: #f2f2f2}

		th {
		    background-color: #4CAF50;
		    color: white;
		}
	</style>


	<table width  = "800" border = "1" cellpadding = "1" cellspacing = "1">
	<tr>
	<th>Simulation Number</th>
	<th>Simulation Configured</th>
	<th align = "center">Simulation Status</th>
	<th colspan = "3" width = "350">Action for Simulation</th>
	<tr>


	<?php
	//accessing database to retrieve user simulation information--------- 
	//the user simulation contains information about the simulation when it was configured and what is the status of the simulation
		$server = 'localhost';
		$user = 'root';
		$pass = 'cncr2018';
		$db = 'WebInterface';
		$flag = 0;
		try{
			$connection = mysqli_connect("$server",$user,$pass,$db);
			//echo $_POST['user'];
			$result = mysqli_query($connection, "select * from UserSimulation where UserId ='$userLogged'") 
			or die("No user found!!!!".mysql_error());
			while($row = mysqli_fetch_array($result)){
				//table for populating simulation information
				echo "<tr>";
				echo "<td>".$row['SimulationId']."</td>";
				echo "<td>".$row['TimeConfigured']."</td>";
				echo "<td>".$row['Status']."</td>";

				if ($row['Status'] == 'Configured' or $row['Status'] == 'Stopped') {
					echo "<td>"?>

					<form action="SimStart.php" method="post">  
					<input type="hidden" name = "simId" id = "simId" value = <?php echo $row["SimulationId"]; ?> >
					<input type="submit" value="Start">
					</form>
					<?php
					"</td>";
					echo "<td>"."--"."</td>";	
					echo "<td>"."--"."</td>";
					echo "<tr>";
				}
				elseif ($row['Status'] == 'Running') {
					echo "<td>"."--"."</td>";
					echo "<td>"
					?>

					<form action="SimStop.php" method="post">  
					<input type="hidden" name = "simId" id = "simId" value = <?php echo $row["SimulationId"]; ?> >
					<input type="submit" value="Stop">
					</form>
					<?php
					"</td>";	
					echo "<td>"
					?>

					<form action="SimAbort.php" method="post">  
					<input type="hidden" name = "simId" id = "simId" value = <?php echo $row["SimulationId"]; ?> >
					<input type="submit" value="Abort">
					</form>
					<?php

					"</td>";
					echo "<tr>";
				}
				else{
					echo "<td>"."--"."</td>";
					echo "<td>"."--"."</td>";	
					echo "<td>"."--"."</td>";
					echo "<tr>";
				}

				
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