	<?php
	include("head.html")
	?>

	<div class = "container">
		<div class="col-sm-12">
			<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->NeuronModelParameter->Creating Initialisation File->Create Topology->Topology Viewer-><b>Save Topology</b></h6></font>
			
			<?php
			//saves the topology information into a topology initialisation file

			if ($_SESSION['flag']==1){
				//echo "no of neurons in layer 1: ", $_POST['neurons_layer1']."<br>";
				
				$simNum = $_POST['simNum'];

				echo "simulation number is : ".$simNum;

				//--------------------------------
								


				$userID = $userLogged .'_'. $simNum;
				$data = new DOMDocument;
				$data->formatOutput = true;
				$dom=$data->createElement("Topology_Initialisation");
	// $xml = simplexml_load_file($userLogged . "/" . $userID . ".xml");
				if($_POST['topology_type'] == 'FullTopology'){
				$Topology = fopen("SimulationXML/".$userLogged . "/Layered/FullTopology.txt", "r") or die("Unable to open file!");
				}
				else{
					$Topology = fopen("SimulationXML/".$userLogged . "/Layered/RandomTopology.txt", "r") or die("Unable to open file!");
				}
				//reads topology txt file created earlier and use that to generate topology initialisation file

				#########################################################################################
				#reading the saved array from save_neuron-data file, it contains the device id number for each neuron
				$deviceidarray = unserialize(file_get_contents("SimulationXML/".$userLogged . "/DeviceId_" . $userID . ".bin"));
				$totalNeurons = sizeof($deviceidarray);
				#print_r($deviceidarray);
				############################################################################################
				$numberOfNeurons = 0;


				while(! feof($Topology))
	  			{
	  				$gettingLine= fgets($Topology);
	  				//to avoid any null values at the end
	  				if($gettingLine == NULL){break;}
	  				//echo $gettingLine;
	  				//separates numbers from spaces and put into an array
	  				$spaceSeparatedConnections = explode(" ",$gettingLine);
	  				print_r($spaceSeparatedConnections);
	  				//testing purpose
	  				/*for($i = 0; $i<sizeof($spaceSeparatedConnections);$i++){
	  					echo $i;
	  					echo $spaceSeparatedConnections[$i];
	  					echo "\n";
	  				}*/
	  				$packet=$data->createElement("packet");
					$destdev=$data->createElement("destdevice",$deviceidarray[$numberOfNeurons]); // since the indexing starts from zero
					$packet->appendChild($destdev);
					$sourcedev=$data->createElement("sourcedevice",65532);
					$packet->appendChild($sourcedev);
					$simID = $data->createElement("simID", $simNum);
					$packet->appendChild($simID);
					
					$command=$data->createElement("command",11);
					$packet->appendChild($command);
					$timestamp=$data->createElement("timestamp",0);
					$packet->appendChild($timestamp);
					$neuronid = $data->createElement("neuronid", $spaceSeparatedConnections[0]);
					$packet->appendChild($neuronid);
					$numberofneurons = $data->createElement("numberofneurons", $totalNeurons); //passed on from topo generate
					$packet->appendChild($numberofneurons);

					//this loops from value 1 since value 0 is the desitnation device so we are only
					//interested on the synpases it receives from

					//if there are 10 neurons then last element of $spaceSeparatedConnections will be \n which is the line break.
					//to avoid inputting line break, loop is run not until the last element
					for ($connect = 1; $connect < sizeof($spaceSeparatedConnections) - 1; $connect++){
						
							$itemid=$data->createElement("preneuronid",$spaceSeparatedConnections[$connect]);
							//echo $spaceSeparatedConnections[$connect];
							$packet->appendChild($itemid);
					
					}
					$dom->appendChild($packet);

					$numberOfNeurons++;

				}

				fclose($Topology);

				$data->appendChild($dom);
				$filename="SimulationXML/".$userLogged . "/Layered/Topo_Ini_file_" . $userID . ".xml";
				$data->save($filename);

				echo "Topology initialisation data has been saved as ", "Topo_Ini_file_" . $userID . ".xml";
				?>
				<br><br>
				<p>Other initialisation files could be added before sending the data, such as muscle and stimulation. These features would be eventually added.</p>
				<p> In the case of adding other initialisation files, these buttons will send the user to the adequate page. This procedure might change. </p>
				<form action="select_stim_neurons.php" method="post">
					<br><input type="submit" value="Add stimulus initialisation data">
					<input type="hidden" name='topology' id = 'topology' value='layeredTopology'>
					<input type ="hidden" name = "totalNeurons" id ="totalNeurons" value = <?php echo $_POST['totalNeurons']; ?>>
					<input type = "hidden" name = 'inputlayer' id= 'inputlayer' value = <?php echo $_POST['neurons_layer1'];?>>
					<input type="hidden" value=<?php echo $simNum; ?> name="simNum">
				</form><br>
				<form action="initialisation_file.php" method="post">
					<input type="hidden" name='topology' id = 'topology' value='layeredTopology'>
					<input type ="hidden" name = "totalNeurons" id ="totalNeurons" value = <?php echo $_POST['totalNeurons']; ?>>
					<input type="hidden" value=<?php echo $simNum; ?> name="simNum">
					<br><input type="submit" value="Create initialisation file">
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
		</div></div>

		<?php
		include("end_page.html")
		?>
