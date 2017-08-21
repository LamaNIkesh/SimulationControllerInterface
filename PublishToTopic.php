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
	echo $filePath;
	#echo 
	#publishing results to the mqtt topic
	#lsit of topics
	#topic=["neurons/broadcast","neurons/bitstreams/","neurons/bitstreams/ack","neurons/post/","neurons/post/ack/",
	 #      "neurons/get", "neurons/errors","webapp/post","webapp/post/ack","webapp/get","webapp/get/ack","im/errors","im/msgs","im/warnings"]
	#publisher.py publishes to webapp/get topic
	try {
		#executing python code that publishes the packets
		exec('python publisher.py ',$filePath);
		echo "Successfully published";
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