	<?php
	include("head.html")
	?>


	<div class = "container">
		<div class="col-sm-12">
			<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->NeuronModelParameter-><b>Creating Initialisation File</b></h6></font>
			<?php
			if ($_SESSION['flag']==1){
				//needs to be changed later on
				$simNum = 1;
				$userID = $userLogged . $simNum;
				$data = new DOMDocument;
				$data->formatOutput = true;
				$dom=$data->createElement("Neuron_Initialisation");
	// $xml = simplexml_load_file($userLogged . "/" . $userID . ".xml");

	if(file_exists('Libraries/ModelLibrary_metadata.xml')){ #Load XML file
		$ModelLibrary = simplexml_load_file ("Libraries/ModelLibrary_metadata.xml");
	}
	else {
		exit ('Could not load the file...');
	}
	?>
	<?php
	//$neuronlistPath = "SimulationXML/".$userLogged . "/Layered/neuronlist.txt";
	//$myfile = fopen($neuronlistPath, "w") or die("Unable to open file!");
	?>

	<form action="topology_layered.php" method="post">
		<?php
		if ($_POST['samemodel']=='yes'){
			echo "same model";
			echo "totalNeurons, ".$_POST['totalNeurons'];
			$neuronNumber = 0;
			for ($number = 1; $number <= $_POST['noOflayers']; $number++){
				//echo 'passed from previous :'.$_POST['neuron'.$number];
				
				//fwrite($myfile, "neuron".$number."\n");
				//fwrite($myfile,'\n');
				?>
				<input type="hidden" name=<?php echo "totalNeuronsEachLayer".$number; ?> value=<?php echo $_POST["totalNeuronsEachLayer".$number]; ?>>

				<?php
				for($eachlayer = 1; $eachlayer<=$_POST['totalNeuronsEachLayer'.$number]; $eachlayer++){

					if($number == 1){
						//increasing neuron number
					$neuronNumber = $eachlayer;
					echo "Neuron Number for first layer: ".$neuronNumber;
					}
					else{
						$neuronNumber++; ;
						echo "Neuron Number after first layer: ".$neuronNumber;
					}
					$packet=$data->createElement("packet");
				//$destdev=$data->createElement("destdevice",$_POST['name'.$number]+1);
				$destdev=$data->createElement("destdevice", $neuronNumber);//temporary
				$packet->appendChild($destdev);
				$sourcedev=$data->createElement("sourcedevice",65532);
				$packet->appendChild($sourcedev);
				$command=$data->createElement("command",24);
				$packet->appendChild($command);
				$timestamp=$data->createElement("timestamp",0);
				$packet->appendChild($timestamp);
				$modelid=$data->createElement("modelid",$_POST['model']);
				$packet->appendChild($modelid);
				
				foreach ($ModelLibrary->neuron as $model){
					if ($model->neuronid==$_POST['model']){
						foreach ($model->item as $modelitem){
						// $item=$data->createElement("item");
							$itemid=$data->createElement("itemid",$modelitem->itemid);
							$packet->appendChild($itemid);
							$itemtype=$data->createElement("itemtype",$modelitem->type);
							$packet->appendChild($itemtype);
							$itemdatatype=$data->createElement("itemdatatype",$modelitem->datatype);
							$packet->appendChild($itemdatatype);
							$itemintegerpart=$data->createElement("itemintegerpart",$modelitem->integerpart);
							$packet->appendChild($itemintegerpart);
							$inlsb=$data->createElement("inlsb",$modelitem->inlsb);
							$packet->appendChild($inlsb);
							$inmsb=$data->createElement("inmsb",$modelitem->inmsb);
							$packet->appendChild($inmsb);
							$outlsb=$data->createElement("outlsb",$modelitem->outlsb);
							$packet->appendChild($outlsb);
							$outmsb=$data->createElement("outmsb",$modelitem->outmsb);
							$packet->appendChild($outmsb);
							$itemvalue=$data->createElement("itemvalue",$_POST["item" . $modelitem->itemid]);
							$packet->appendChild($itemvalue);
						// $packet->appendChild($item);
						}
					}
				}
				$dom->appendChild($packet);
			}
		}
		$data->appendChild($dom);
		$filename="SimulationXML/".$userLogged . "/Layered/Neuron_Ini_file_" . $userID . ".xml";
		$data->save($filename);
		?>
		<br>
		<input type="hidden" name="totalNeurons" value=<?php echo $_POST['totalNeurons']; ?>>
		<input type ="hidden" name="noOflayers" value = <?php echo $_POST['noOflayers']; ?>>
		<br>
		<input type="submit" value="Create topology">
	</form><br><br>
	<?php
	}

	else{
		//echo "differnt model layers";
		//echo $_POST['totalNeuronsEachLayer'];
		//writing for different models
		//echo $_POST['name'.$number];
		//echo $_POST['noOflayers'];
		for ($number = 1; $number <= $_POST['noOflayers']; $number++){
			//write($myfile, "neuron".($number+$subtractedSameModel)."\n");
			//echo "no fo neurons of layer: ".$_POST['totalNeuronsEachLayer'.$number];
			//$modelid = $_POST['model'.$number];
			//echo "model id: ".$_POST['model'.$number];?>
			<input type="hidden" name=<?php echo "totalNeuronsEachLayer".$number; ?> value=<?php echo $_POST["totalNeuronsEachLayer".$number]; ?>>
			<?php 

			for($eachlayer = 1; $eachlayer<=$_POST['totalNeuronsEachLayer'.$number]; $eachlayer++){
				//echo "Iteration: ".$eachlayer."\n";
				$packet=$data->createElement("packet");
				//$destdev=$data->createElement("destdevice",$_POST['name'.$number]+1);
				$destdev=$data->createElement("destdevice",1);
				$packet->appendChild($destdev);
				$sourcedev=$data->createElement("sourcedevice",65532);
				$packet->appendChild($sourcedev);
				$command=$data->createElement("command",24);
				$packet->appendChild($command);
				$timestamp=$data->createElement("timestamp",0);
				$packet->appendChild($timestamp);
				$modelid=$data->createElement("modelid",$_POST['model'.$number] ); // the model number are same model num + . eg if there are 3 same 
				//models then starting index for diff model is 3+1 = 4 and 5,6....
				$packet->appendChild($modelid);
				//adding values as per the model 
				//different model has different fields
				foreach ($ModelLibrary->neuron as $model){
					if ($model->neuronid == $_POST['model'.$number] ){
						foreach ($model->item as $modelitem){
				// $item=$data->createElement("item");
							$itemid=$data->createElement("itemid",$modelitem->itemid);
							$packet->appendChild($itemid);
							$itemtype=$data->createElement("itemtype",$modelitem->type);
							$packet->appendChild($itemtype);
							$itemdatatype=$data->createElement("itemdatatype",$modelitem->datatype);
							$packet->appendChild($itemdatatype);
							$itemintegerpart=$data->createElement("itemintegerpart",$modelitem->integerpart);
							$packet->appendChild($itemintegerpart);
							$inlsb=$data->createElement("inlsb",$modelitem->inlsb);
							$packet->appendChild($inlsb);
							$inmsb=$data->createElement("inmsb",$modelitem->inmsb);
							$packet->appendChild($inmsb);
							$outlsb=$data->createElement("outlsb",$modelitem->outlsb);
							$packet->appendChild($outlsb);
							$outmsb=$data->createElement("outmsb",$modelitem->outmsb);
							$packet->appendChild($outmsb);
							$itemvalue=$data->createElement("itemvalue",$_POST[ "item" . $modelitem->itemid]);
							$packet->appendChild($itemvalue);
				// $packet->appendChild($item);
						}
					}
				}
				$dom->appendChild($packet);
			}
		}
		$data->appendChild($dom);
		$filename="SimulationXML/".$userLogged . "/Layered/Neuron_Ini_file_" . $userID . ".xml";
		$data->save($filename);	

		?>
		<br>
		<input type="hidden" name="totalNeurons" value=<?php echo $_POST['totalNeurons']; ?>>
		<input type="hidden" name = "noOflayers" value = <?php echo $_POST['noOflayers']; ?>>
		<br>
		<input type="submit" value="Create topology">
	</form><br><br>
	<?php
	}
	//fclose($myfile);
	echo "Neuronal initialisation data has been saved as ", "Neuron_Ini_file_" . $userID . ".xml";

	?>

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

	</div>
	</div>

	<?php
	include("end_page.html")
	?>