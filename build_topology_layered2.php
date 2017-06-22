
<?php
include("head.html")
?>

<div class = "container">
	<div class="col-sm-12">
		<h6><font color = "#52a25e">System Builder->Simulation Parameters-><b>Saving Simulation Parameters</b></h6></font>
		<?php
		if ($_SESSION['flag']==1){

			$userLogged = $_SESSION['username'];
	//increases the simNum for the file name for multiple file 
			//$_SESSION['simNum']++;
			//$simNum =$_SESSION['simNum'];
			$simNum = 1;
			$totalNeurons = 0;
			//echo $_POST['model4'];
			//writing the neurons into a text file for future use
			if($_POST['samemodel'] == 'yes'){
				$neuronlist = fopen("SimulationXML/".$userLogged . "/neuronlist.txt", "w");
			//this stores model for each layer
				$LayerModel = fopen("SimulationXML/".$userLogged."/layerModel.txt","w");
			//$noOfneuronsEachLayer = fopen("SimulationXML/".$userLogged."/noOfneuronsEachLayer.txt","w");
			}
			else{
				$neuronlist = fopen("SimulationXML/".$userLogged . "/Layered/neuronlist.txt", "w");
			//this stores model for each layer
				$LayerModel = fopen("SimulationXML/".$userLogged."/Layered/layerModel.txt","w");
			//$noOfneuronsEachLayer = fopen("SimulationXML/".$userLogged."/Layered/noOfneuronsEachLayer.txt","w");
			}
			$totalNeuronsEachLayer = 0;
			//$neuronlist = fopen($neuronlistPath, "w"); 
			//calculating the total no of neurons
			//checking no of neurons for each layer and adding them up
			for ($i=1; $i <= $_POST['noOflayers']; $i++) { 
				# code...
				fwrite($neuronlist, $i);
				for ($j=1; $j <= $_POST['totalNeuronsLayer'.$i] ; $j++) {
					fwrite($neuronlist," ".$i.".".$j); 
					$totalNeuronsEachLayer++;
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

			$neuronlistPath = "SimulationXML/".$userLogged . "/NeuronList.txt";
			$neuronlist = fopen($neuronlistPath, "w"); 
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
						$userID = $userLogged .$simNum;
						$watchdog = $_POST['watchdog']; 
						//$watchdog = 2;
						if ($timestepsize=='us'){
							$cycles=1;
							$cyclesNum=$simtime*$cycles;
						}
						if ($timestepsize=='ms'){
							$cycles=1000;
							$cyclesNum=$simtime*$cycles;
						}
						if ($timestepsize=='s'){
							$cycles=1000000;
							$cyclesNum=$simtime*$cycles;
						}
		//Create the xml tag input where every other tags goes into
		//$input = $dom->createElement("input");
	//Create input tag and place value gotten from form into it
						$input1 = $dom->createElement("Sim_Meta");
						$input = $dom->createElement("packet");
						$a=$dom->createElement("destdevice", $destdevice);
						$input->appendChild($a);
						$b=$dom->createElement("timestamp", $sourcedevice);
						$input->appendChild($b);
						$c=$dom->createElement("command", $command);
						$input->appendChild($c);
						$d=$dom->createElement("timestamp", $timestamp);
						$input->appendChild($d);
						$e=$dom->createElement("timestepsize", $cycles);
						$input->appendChild($e);
						$f=$dom->createElement("cyclesNum", $cyclesNum);
						$input->appendChild($f);
						$g=$dom->createElement("simID", $simNum);
						$input->appendChild($g);
						$h=$dom->createElement("watchdogPeriod", $watchdog);
						$input->appendChild($h);
						$i=$dom->createElement("neuronsnum", $totalNeurons);
						$input->appendChild($i);
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
					<?php if($_POST['samemodel'] == 'yes') {?>
					<input type ="hidden" name= "model" value=<?php echo $_POST['model']; ?> required>
					<?php } 
					else{
						?>
						<input type ="hidden" name= "model" value='0' required>
						<?php } ?>

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