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

	#opening the xml initialisation file and extracts packets to publish separately 
	$filePath = $_POST['filenameXML'];
	//providing absolute path
	$filePath = '/home/nikesh/Documents/WebServer/SimulationControllerInterface/'.$filePath;
	echo $filePath;
	#$pathToFile = "/home/nikesh/Documents/WebServer/SimulationControllerInterface";
	$python_command = "python /home/nikesh/Documents/WebServer/SimulationControllerInterface/publisher_packet.py 2>&1";
	#echo $python_command;
	#publishing results to the mqtt topic
	#lsit of topics
	#topic=["neurons/broadcast","neurons/bitstreams/","neurons/bitstreams/ack","neurons/post/","neurons/post/ack/",
	#"neurons/get", "neurons/errors","webapp/post","webapp/post/ack","webapp/get","webapp/get/ack","im/errors","im/msgs","im/warnings"]
	#publisher.py publishes to webapp/get topic
	try {
		#executing python code that publishes the packets
		#shell_exec($python_command);		
		#echo shell_exec($python_command);
		#$output = shell_exec('python3 /home/nikesh/Documents/WebServer/SimulationControllerInterface/test/mkdir.py 2>&1');
		#$output = shell_exec('python /home/nikesh/Documents/WebServer/SimulationControllerInterface/publisher_packet.py 2>&1');
		$output = shell_exec('sudo -u daemon python /home/nikesh/Documents/WebServer/SimulationControllerInterface/publisher_packet.py 2>&1 '.$filePath);
		echo "<pre>$output</pre>";
		#echo shell_exec("python3 -V 2>&1");
		echo "All the packets successfully published to the Interface Manager.\nYou will receive a notification when the simulation is complete";
	} catch (Exception $e) {
		echo "Error: Could not publish to the topic";
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