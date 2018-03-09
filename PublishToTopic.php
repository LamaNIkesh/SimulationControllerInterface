<?php
include("head.html")
?>

<div class = "container">
	<div class="col-sm-12">
		<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->
			NeuronModelParameter->Creating Initialisation File->Create Topology->Topology Viewer->
			Save Topology->Save Initialisation file-><b>Publishing for simulation</b></h6></font>
<?php
if ($_SESSION['flag']==1){
	# code...
	$simNum = $_POST['simNum'];

	/*
	* This part of the code take care of running python script that publishes
	* the intiilisation xml files as packets and awaits acknowledgement from the 
	* webapp/get/ack topic.
	* 
	*/


	#opening the xml initialisation file and extracts packets to publish separately 
	#reads filename and path for the initialisation file
	$filePath = $_POST['filenameXML'];
	//providing absolute path
	$filePath = '/home/nikesh/Documents/WebServer/SimulationControllerInterface/'.$filePath;
	#echo $filePath;
	
	#publishing results to the mqtt topic
	#lsit of topics
	#topic=["neurons/broadcast","neurons/bitstreams/","neurons/bitstreams/ack","neurons/post/","neurons/post/ack/",
	#"neurons/get", "neurons/errors","webapp/post","webapp/post/ack","webapp/get","webapp/get/ack","im/errors","im/msgs","im/warnings"]
	#publisher.py publishes to webapp/get topic
	try {
		#executing python code that publishes the packets
		#--------------------------------------------------------------------------------
		#-------Critical region---------------------- Initial issues with running python script
		#-------from php
		#sudo -u ->allows sudo user privelege
		#daemon is the group that apache2 falls into. Normally is www-data but since
		#the web directory is not in default place, the user group is www-data
		#also no password privelege is added to sudoer file for daemon users
		
		$output = shell_exec('sudo -u daemon python /home/nikesh/Documents/WebServer/SimulationControllerInterface/tcpSend/send_packet_tcp.py 2>&1 '.$filePath);
		echo "<pre>$output</pre>";
		echo "All the packets successfully published to the Interface Manager.\nYou will receive a notification when the simulation is complete";

		//Once the packet has been sent to the IM server, the database is updated to show configured simulations for each users
		$server = 'localhost';
		$user = 'root';
		$pass = 'cncr2018';
		$db = 'WebInterface';
		try{
			$connection = mysqli_connect("$server",$user,$pass,$db);
			$engage_flag = 1;
			$updateEngage = "UPDATE SImulation SET Engage = '$engage_flag' WHERE SimulationId = '$simNum'";
			#mysqli_query($sql);
			if(mysqli_query($connection,$updateEngage) === TRUE){
				echo "Record updated successfully";
			}	
			else{
				echo "Error updating the record: ".$connection->error;
			}
			//once the boolean engage field of simulation is updated, new table UserSimulation is updated too to keep track of
			//which user gets what simulation number
			$status = 'Configured';
			$engage = 1;
			$datetime = date("Y-m-d H:i:s");
			$insert_userSim = "INSERT INTO UserSimulation(UserId, SimulationId,Status,TimeConfigured,Engage) VALUES('$userLogged','$simNum','$status','$datetime','$engage')";
			//pass the query
			mysqli_query($connection,$insert_userSim);	
			}

			catch (Exception $e) {
					echo "error: ".$e->getMessage();
			}
			//end of try catch for database update
	} 
	catch (Exception $e) {
		echo "Error: Could not publish to the server";
	}
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