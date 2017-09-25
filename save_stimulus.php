<?php
include("head.html")
?>

<div class = "container">
		<div class="col-sm-12">
			<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->
										NeuronModelParameter->Creating Initialisation File->
										Create Topology->Topology Viewer->Save Topology->Add Stimulus-><b>Save stimulus</b></h6></font>
			<h3>The metadata and neuronal XML files will be merged here. You can download the file in your local drive</h3>
			<br>
			<?php
			//saves the topology information into a topology initialisation file

			if ($_SESSION['flag']==1){
				//$userID = $userLogged . $simNum;

				//echo $_POST['nameid6'];
				$simNum =1;
				$userID = $userLogged . $simNum;
				$data = new DOMDocument;
				$data->formatOutput = true;
				$dom=$data->createElement("Preconfigured_stimulus");
				//fopen("SimulationXML/".$userLogged . "/Layered/FullTopology.txt", "r") or die("Unable to open file!");
				// $xml = simplexml_load_file($userLogged . "/" . $userID . ".xml");
				for ($number = 1; $number <= $_POST['stimNeurons']; $number++){
					
					if(isset($_POST['nameid'.$number])){
						//echo "index with stim neurons: ".$number ." the neuron num is".$_POST['nameid'.$number];
						//echo "<br>";
						$packet=$data->createElement("packet");
						$destdev=$data->createElement("destdevice",1); // Needs to specify the destination; is the neuron??
						$packet->appendChild($destdev);
						$sourcedev=$data->createElement("sourcedevice",65532); // Needs to specify the source; is the NC??
						$packet->appendChild($sourcedev);
						$simID = $data->createElement("simID", 1);
						$packet->appendChild($simID);
						$command=$data->createElement("command",19);
						$packet->appendChild($command);
						$timestamp=$data->createElement("timestamp",$_POST['start'.$number]);
						$packet->appendChild($timestamp);
						$neuronid = $data->createElement("neuronid", $_POST['nameid'.$number]);
						$packet->appendChild($neuronid);
						//$numberofneurons = $data->createElement("numberofneurons", $_POST['totalNeurons']);
						//$packet->appendChild($numberofneurons);
						$endtimestamp=$data->createElement("endtimestamp",$_POST['end'.$number]);
						$packet->appendChild($endtimestamp);
						$itemID=$data->createElement("itemID",65523); // 655523 is electrical stimulation, check SCRP document for differnt IDs
						$packet->appendChild($itemID);
						//variables of a quadratic equation ax^2 + bx + c
						//for step current, item value a and b are set to zero so c = value
						$itemValuea = $data->createElement("itemValuea", 0.0);
						$packet->appendChild($itemValuea);

						$itemValueb = $data->createElement("itemValueb", 0.0);
						$packet->appendChild($itemValueb);

						$itemValuec=$data->createElement("itemValuec",$_POST['value'.$number]);
						$packet->appendChild($itemValuec);
							
						$dom->appendChild($packet);
						}
					
				}
				$data->appendChild($dom);
				//$filename=$userLogged . "/Stim_Ini_file_" . $userLogged . ".xml";
				if($_POST['topology'] == 'layeredTopology'){
					//checking if the topology is layered or not
					//layered topology files are stored in /layered folder whereas non layered are stored in a folder before layered
					$filename = "SimulationXML/".$userLogged . "/Layered/Stim_Ini_file_" . $userID . ".xml";
				}
				else{
				$filename = "SimulationXML/".$userLogged . "/Stim_Ini_file_" . $userID . ".xml";
				}
				$data->save($filename);	
				echo "Preconfigured stimulus data has been saved as ", "Stim_Ini_file_" . $userID . ".xml";
				?>
				</form><br>
				<form action="initialisation_file.php" method="post">
				<br>
				
				<input type="hidden" name='topology' id = 'topology' value=<?php echo $_POST['topology']; ?>>   
				<input type="hidden" name = 'stimulation' id = 'stimulation' name ='yes' >
				<input type="submit" value="Create initialisation file">
				
				
				</form><br>
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
<?php
include("end_page.html")
?>
