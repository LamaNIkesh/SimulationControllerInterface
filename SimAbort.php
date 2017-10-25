<?php
include("head.html")
?>


<div class = "container">
		<div class="col-sm-12">
			<?php
			//saves the topology information into a topology initialisation file

			if ($_SESSION['flag']==1){
				
				$simNum = $_POST['simId'];
				$userID = $userLogged .'_'.$simNum;

				//Saving the start command xml 
				$data = new DOMDocument;
				$data->formatOutput = true;
				$root = $data->createElement("Abort");
				$packet=$data->createElement("packet");
				$destdev=$data->createElement("destdevice",1); // Needs to specify the destination; is the neuron??
				$packet->appendChild($destdev);
				$sourcedev=$data->createElement("sourcedevice",65532); // Needs to specify the source; is the NC??
				$packet->appendChild($sourcedev);
				$simID = $data->createElement("simID", $simNum);
				$packet->appendChild($simID);
				$command=$data->createElement("command",0);
				$packet->appendChild($command);
				$timestamp=$data->createElement("timestamp",0);
				$packet->appendChild($timestamp);
				$root->appendChild($packet);
				$data->appendChild($root);
				$filename = "SimulationXML/".$userLogged ."/CurrentSimulations/Sim_Abort_" . $userID . ".xml";
				
				$data->save($filename);	

				//sending to the IM server to start the simulation
				$fileSentFlag = 0;
				//providing absolute path
				$filePath = '/home/nikesh/Documents/WebServer/SimulationControllerInterface/'.$filename;
				#echo $filePath;
				try {
					#executing python code that publishes the packets	
					$output = shell_exec('sudo -u daemon python /home/nikesh/Documents/WebServer/SimulationControllerInterface/tcpSend/send_packet_tcp.py 2>&1 '.$filePath);
					echo "<pre>$output</pre>";
					#echo shell_exec("python3 -V 2>&1");
					echo "Start Command has been sent to the Interface Manager.Your simulation is now running";
					$fileSentFlag = 1;
				} 
				catch (Exception $e) {
					echo "Error: Could not publish to the topic";
				}

				

				//---Updating the database if the start command has been successfully sent

				if ($fileSentFlag == 1) {
					$server = 'localhost';
					$user = 'root';
					$pass = '';
					$db = 'WebInterface';

					try{
						$connection = mysqli_connect("$server",$user,$pass,$db);
						$status = 'Aborted';
						$updateStatus = "UPDATE UserSimulation SET Status = '$status' WHERE SimulationId = '$simNum'";
						#mysqli_query($sql);
						if(mysqli_query($connection,$updateStatus) === TRUE){
							echo "Record updated successfully";
						}	
						else{
							echo "Error updating the record: ".$connection->error;
						}	
					}
					catch (Exception $e) {
							echo "error: ".$e->getMessage();
					}
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