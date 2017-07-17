	<?php
	include("head.html")
	?>

	<div class = "container">
		<div class="col-sm-12">
			<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->NeuronModelParameter->Creating Initialisation File->Create Topology->Topology Viewer-><b>Save Topology</b></h6></font>
			<?php
			//saves the topology information into a topology initialisation file

			if ($_SESSION['flag']==1){
				$simNum = 1;
				$userID = $userLogged . $simNum;
				$numberOfNeurons = 0; //counts the number of neurons from the topology file
										//this neuron number is used only for the stimulation file.
										//since this is non layered, stimulation can be applied to any neurons.
										//so we throw out all the neurons
				$data = new DOMDocument;
				$data->formatOutput = true;
				$dom=$data->createElement("Topology_Initialisation");
	// $xml = simplexml_load_file($userLogged . "/" . $userID . ".xml");

				$Topology = fopen("SimulationXML/".$userLogged . "/Topology.txt", "r") or die("Unable to open file!");
				//reads topology txt file created earlier and use that to generate topology initialisation file
				while(! feof($Topology))
	  			{
	  				$gettingLine= fgets($Topology);
	  				//to avoid any null values at the end
	  				if($gettingLine == NULL){break;}
	  				//echo $gettingLine;
	  				//separates numbers from spaces and put into an array from the file
	  				$spaceSeparatedConnections = explode(" ",$gettingLine); 
	  				//testing purpose
	  				/*for($i = 0; $i<sizeof($spaceSeparatedConnections);$i++){
	  					echo $i;
	  					echo $spaceSeparatedConnections[$i];
	  					echo "\n";
	  				}*/
	  				$packet=$data->createElement("packet");
					$destdev=$data->createElement("destdevice",$spaceSeparatedConnections[0]);
					$packet->appendChild($destdev);
					$sourcedev=$data->createElement("sourcedevice",65532);
					$packet->appendChild($sourcedev);
					$command=$data->createElement("command",11);
					$packet->appendChild($command);
					$timestamp=$data->createElement("timestamp",0);
					$packet->appendChild($timestamp);
					
					//this loops from value 1 since value 0 is the desitnation device so we are only
					//interested on the synpases it receives from
					for ($connect = 1; $connect < sizeof($spaceSeparatedConnections); $connect++){
						
							$itemid=$data->createElement("preneuronid",$spaceSeparatedConnections[$connect]);
							//echo $spaceSeparatedConnections[$connect];
							$packet->appendChild($itemid);
					
					}
					$dom->appendChild($packet);
					$numberOfNeurons++;


				}

				fclose($Topology);

				$data->appendChild($dom);
				$filename="SimulationXML/".$userLogged . "/Topo_Ini_file_" . $userID . ".xml";
				$data->save($filename);

				echo "Topology initialisation data has been saved as ", "Topo_Ini_file_" . $userID . ".xml";
				?>
				<br><br>
				<p>Other initialisation files could be added before sending the data, such as muscle and stimulation. These features would be eventually added.</p>
				<p> In the case of adding other initialisation files, these buttons will send the user to the adequate page. This procedure might change. </p>
				<form action="select_stim_neurons.php" method="post">
					<br><input type="submit" value="Add stimulus initialisation data">
					<input type="hidden" name="topology" id = "topology" value='nonlayered' ?>>
					<input type = "hidden" name = "noOfNeurons" id = "noOfNeurons" value = <?php echo $numberOfNeurons; ?> >
				</form><br>
				<form action="initialisation_file.php" method="post">
					<input type="hidden" name='topology' id = 'topology' value='nonlayered'>
					<br><input type="submit" value="Create initialisation file">
				</form><br>
				<form action = ""
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