
<?php
include("head.html")
?>

<div class = "container">
	<div class="col-sm-12">
		<h6><font color = "#52a25e">System Builder->Simulation Parameters-><b>Saving Simulation Parameters</b></h6></font>
		<?php
		if ($_SESSION['flag']==1){

			$userLogged = $_SESSION['username'];

			/*
			---------------------------------------------------------------------------------------------------------------------------
			This section to assign simulation number to this particular simulation
			Reading database for simulation id, assigning unused simid to the user and update the database
			*/
			//database connection params
			$server = 'localhost';
			$user = 'root';
			$pass = 'cncr2018';
			$db = 'WebInterface';

			try{
				$connection = mysqli_connect("$server",$user,$pass,$db);
				$result = mysqli_query($connection, "select * from SImulation");

				while($simulation = mysqli_fetch_assoc($result)){
					#echo $simulation['id'];
					#echo $simulation['SimulationId'];
					#echo $simulation['Engage'];
					#Checking if a particular simualtion number is free to use
					#after being assigned the sim number engage field is set to 1, so that it cannot be reassigned to another simulation
					#until it is free again
					if($simulation['Engage'] == 0){
						$simNum = $simulation['SimulationId'];
						#$simulation['Engage'] = 1;
						break;
					}
				}
			}
			catch (Exception $e) {
					echo "error: ".$e->getMessage();
			}
			//------------------------------------------------------------------------------------------------------	
			echo "\nsimulation ID assigned:".$simNum;

			$totalNeurons = 0;
			//echo $_POST['model4'];
			//writing the neurons into a text file for future use
			if($_POST['samemodel'] == 'yes'){
				$neuronlist = fopen("SimulationXML/".$userLogged . "/Layered/neuronlist.txt", "w");
			//this stores model for each layer
				$LayerModel = fopen("SimulationXML/".$userLogged."/Layered/layerModel.txt","w");
			//$noOfneuronsEachLayer = fopen("SimulationXML/".$userLogged."/noOfneuronsEachLayer.txt","w");
			}
			else{
				$neuronlist = fopen("SimulationXML/".$userLogged . "/Layered/neuronlist.txt", "w");
			//this stores model for each layer
				$LayerModel = fopen("SimulationXML/".$userLogged."/Layered/layerModel.txt","w");
			//$noOfneuronsEachLayer = fopen("SimulationXML/".$userLogged."/Layered/noOfneuronsEachLayer.txt","w");
			}
			$totalNeuronsEachLayer = 0;
			$totalneurons = 0;
			//$totalNeurons
			//$neuronlist = fopen($neuronlistPath, "w"); 
			//calculating the total no of neurons
			//checking no of neurons for each layer and adding them up
			for ($i=1; $i <= $_POST['noOflayers']; $i++) { 
				# code...
				//fwrite($neuronlist, $i);
				for ($j=1; $j <= $_POST['totalNeuronsLayer'.$i] ; $j++) {
					if($i == 1){

					fwrite($neuronlist,$j." "); 
					$totalNeuronsEachLayer++;
					$totalneurons = $totalNeuronsEachLayer;
				}
					else{
						echo "TotalNeuronsEachLayer: ".$totalneurons;
						$totalneurons++;
						fwrite($neuronlist, $totalneurons." ");
						//$totalNeuronsEachLayer++;
						
					}
					
					# code...
				}

				//passing no fo neurons each layer to the next file to be read
				?>
				<input type ="hidden" name=<?php echo "totalNeuronsEachLayer".$i ?> value= <?php echo $totalNeuronsEachLayer ?> required>
				<?php 
				//echo "no of neurons each layer:".$totalNeuronsEachLayer;
				$totalNeuronsEachLayer = 0;
				if($_POST['samemodel'] == 'no'){
					fwrite($LayerModel,$_POST["model".$i]);
				}

				$totalNeurons +=$_POST['totalNeuronsLayer'.$i];
				fwrite($neuronlist,"\n");
				fwrite($LayerModel,"\n");
			}

			//$neuronlistPath = "SimulationXML/".$userLogged . "/NeuronList.txt";
			//$neuronlist = fopen($neuronlistPath, "w"); 
			//echo "no of neurons: ",$totalNeurons;

			//Create new document
			$dom = new DOMDocument('1.0', 'UTF-8');
			//$dom->formatOutput = true;

			if(isset($_POST['submit']))
			{	
				try {
					if(!empty($totalNeurons) and !empty($_POST["simtime"]) and !empty($_POST["watchdog"]))
					{
						?>
						<p>Fields submitted successfully</p>		
						<?php
						$destdevice = 0;
						$sourcedevice=65532;
						$command=14;
						$timestamp = 0;
						$timestepsize = $_POST['simunits'];
						$simtime = $_POST['simtime'];
						$userID = $userLogged .'_'.$simNum;
						$watchdog = $_POST['watchdog']; 

						if ($timestepsize=='ms'){
							$cycles=1;
							$cyclesNum=$simtime*$cycles;
						}
						if ($timestepsize=='s'){
							$cycles=1000;
							$cyclesNum=$simtime*$cycles;
						}
		//Create the xml tag input where every other tags goes into
		//$input = $dom->createElement("input");
	//Create input tag and place value gotten from form into it
						$input1 = $dom->createElement("Sim_Meta");
						$input = $dom->createElement("packet");
						$destDevice=$dom->createElement("destdevice", $destdevice);
						$input->appendChild($destDevice);
						$sourceDevice=$dom->createElement("sourcedevice", $sourcedevice);
						$input->appendChild($sourceDevice);

						$simID=$dom->createElement("simID", $simNum);
						$input->appendChild($simID);
						
						$command=$dom->createElement("command", $command);
						$input->appendChild($command);
						$tmsp=$dom->createElement("timestamp", $timestamp);
						$input->appendChild($tmsp);
						$tmspSize=$dom->createElement("timestepsize", $cycles);
						$input->appendChild($tmspSize);
						$cycle=$dom->createElement("cyclesNum", $cyclesNum);
						$input->appendChild($cycle);
						$watchdog=$dom->createElement("timeout", $watchdog);
						$input->appendChild($watchdog);
						$totalneurons=$dom->createElement("neuronsnum", $totalNeurons);
						$input->appendChild($totalneurons);
						//Not using muscle, only for c elegans
						//$j=$dom->createElement("musclesnum", $_POST['muscle']);
						//$input->appendChild($j);
						$input1->appendChild($input);
						$dom->appendChild($input1);

			//Save generated xml file as build_input.xml
						$filename="SimulationXML/".$userLogged . "/Layered/Sim_Ini_file_" . $userID . ".xml";
						//echo $filename;
						$dom->save($filename);	

						echo "A metadata initialisation file has been generated and saved as ", "Sim_Ini_file_" . $userID . ".xml in your folder SimulationXML/".$userLogged;
						

					} 
				}
				catch (Exception $e) {
					echo "error: ".$e->getMessage();

				}
				?>

				<form action="save_neuron_layered.php" method="POST">
					<br>
					<input type ="hidden" name= "noOflayers" value=<?php echo $_POST['noOflayers']; ?> required>
					<input type ="hidden" name= "samemodel" value=<?php echo $_POST['samemodel']; ?> required>
					<input type="hidden" value=<?php echo $simNum; ?> name="simNum">
					<?php 
					if($_POST['samemodel'] == 'yes') {
					?>
						<input type ="hidden" name= "model" value=<?php echo $_POST['model']; ?> required>
						<?php } 
					else{
						?>
						<input type ="hidden" name= "model" value='0' required>
						<?php 
					} 
					?>

					<input type = "hidden" name = "totalNeurons" value = <?php  echo $totalNeurons; ?> require>

					<?php 

					for ($i=1; $i <= $_POST['noOflayers']; $i++) { 

							//passing no fo neurons each layer to the next file to be read
						?>
						<input type ="hidden" name=<?php echo "totalNeuronsEachLayer".$i ?> value= <?php echo $_POST['totalNeuronsLayer'.$i]; ?> required>
						<?php 
					}



					?>



					<input type="submit" value="Next" name="submit">
					</form>


					<?php
				}

				else
				{
					?>
					<p>At least one field is empty</p>
					<form action="build.php" method="POST">
						<br>
						<input type="submit" value="Try again" name="submit">
					</form>
					<br>
					<form action="logged2.php" method="POST">
						<input type="submit" value="Cancel" name="submit">
					</form>	
					<br><br>		
					<?php
				}

				?>
				<br><br>
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