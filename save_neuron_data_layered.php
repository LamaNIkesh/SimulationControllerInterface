<?php
include("head.html")
?>



<?php 

	//function to get model parameters for each model
	function queryDatabaseForParameters($arrayForModelPara,$model){
		$server = 'localhost';
	  	$user = 'root';
	  	$pass = '';
	  	$db = 'WebInterface';

	  	try{
	  		//create connection
		  	$connection = mysqli_connect("$server",$user,$pass,$db);
		  	//$_POST['model'] is the selected model from the previous page
		  	//since the table is named with the same model we can select table with the model name
		  	$result = mysqli_query($connection, "select * from $model") 
					or die("No model found!!!!".mysql_error());
			$loopCounter = 0;
			$noOfFields = 0;
			if(mysqli_num_rows($result)>0){
				while($row = mysqli_fetch_assoc($result)){

					/*
					reading the whole database which will be used to generate xml file with all the parameters.
					The parameter table is
					  [0]     [1]   [2]  [3]    [4]       [5]        [6]       [7]   [8]   [9]   [10]
					ItemID|ModelID|Name|Type|Datatype|IntegerPart|TypicalVal|InLSB|InMSB|OutLSB|OutMSB

					*/

					//echo "Model ID: ".$row['ModelID']."---Model Name: ".$row['Name']." "."<br>";
					$arrayForModelPara[$loopCounter][0] = $row['ItemID']; //first element of 2d array is para name and second column is the typical value
					$arrayForModelPara[$loopCounter][1] = $row['ModelID'];
					$arrayForModelPara[$loopCounter][2] = $row['Name'];
					$arrayForModelPara[$loopCounter][3] = $row['Type']; //first element of 2d array is para name and second column is the typical value
					$arrayForModelPara[$loopCounter][4] = $row['Datatype'];
					$arrayForModelPara[$loopCounter][5] = $row['IntegerPart'];
					$arrayForModelPara[$loopCounter][6] = $row['TypicalVal']; //first element of 2d array is para name and second column is the typical value
					$arrayForModelPara[$loopCounter][7] = $row['InLSB'];
					$arrayForModelPara[$loopCounter][8] = $row['InMSB'];
					$arrayForModelPara[$loopCounter][9] = $row['OutLSB']; //first element of 2d array is para name and second column is the typical value
					$arrayForModelPara[$loopCounter][10] = $row['OutMSB'];

					$loopCounter++;
				}
			}
			return $arrayForModelPara;
			mysqli_close($connection);
		  	}

	  	catch(Exception $e){
	  		echo "Cannot establish connection !!";
	  	}

	}

	//returns model name for the model ID
	function getModelNameFromModelID($modelID){
		$server = 'localhost';
	  	$user = 'root';
	  	$pass = '';
	  	$db = 'WebInterface';

	  	try{
	  		//create connection
		  	$connection = mysqli_connect("$server",$user,$pass,$db);
		  	//$_POST['model'] is the selected model from the previous page
		  	//since the table is named with the same model we can select table with the model name
		  	$result = mysqli_query($connection, "select * from ModelLibrary") 
					or die("No model found!!!!".mysql_error());
			if(mysqli_num_rows($result)>0){
				while($row = mysqli_fetch_assoc($result)){
					//echo "\nrow ".$row['ModelID'];
					//echo "\ninput model id".$modelID;
					if ($row['ModelID'] == intval($modelID)){
						//echo "model id found";
						return $row['ModelName'];

					}
					
				}
			}
			mysqli_close($connection);
		  	}

	  	catch(Exception $e){
	  		echo "Cannot establish connection !!";
	  	}
	}


?>




<div class = "container">
	<div class="col-sm-12">
		<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->NeuronModelParameter-><b>Creating Initialisation File</b></h6></font>
		<?php
		if ($_SESSION['flag']==1){

			$simNum = $_POST['simNum'];
			echo "simulation number is : ".$simNum;

		//--------------------------------

			$userID = $userLogged .'_'.$simNum;
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

	##################################################################################################
	#############################################################################################
	#		ADDED BIT FOR MULTI FPGA SIMULATION 

	$DevicesWithExactNumOfNeurons= intval($_POST['totalNeurons']/8);
	if($_POST['totalNeurons']%8 > 0){
		$extraDevice = 1;
	}
	else{
		$extraDevice = 0;
	}
	$totalRequiredDevice = $DevicesWithExactNumOfNeurons + $extraDevice;

	#echo "total device required: ",($totalRequiredDevice);

	#Multiple devices per simulation
	#calcualting how many devices are required for selected number of neurons
	#each device can only have 8 neurons
	echo "total neurons: ", $_POST['totalNeurons'];
	$arraywithDevNum = array($_POST['totalNeurons']);

	for ($totalNeu=0; $totalNeu <$_POST['totalNeurons'] ; $totalNeu++) { 
		# code...
		#echo $totalNeu;
		#echo "<br>";
		for ($i=$totalRequiredDevice ; $i>0; $i--) { 
			# code...
			#echo "i: ",$i,"<br>";
			if(intval($totalNeu/8) >= intval($i-1)){
				echo "destdev: ",$i,"<br>";
				$destdevice = $i ;
				#cho 'destdevice: ',$destdevice;
				$arraywithDevNum[$totalNeu-1] = $destdevice;
				break;
			}
			#echo "destdevice: ",$destdevice;
		}
	}
	echo "array size: ",sizeof($arraywithDevNum);
	file_put_contents("SimulationXML/".$userLogged . "/DeviceId_" . $userID . ".bin",serialize($arraywithDevNum));
	
	###############################################################################################
	#							SAME MODEL LAYERS
	###############################################################################################



	if ($_POST['samemodel']=='yes'){
		echo "same model";
		echo "totalNeurons, ".$_POST['totalNeurons'];
		$neuronNumber = 0;
		for ($number = 1; $number <= $_POST['noOflayers']; $number++){
			//echo 'passed from previous :'.$_POST['neuron'.$number];
			echo "Model";
			print_r ($_POST['model']);
			$modelName = getModelNameFromModelID($_POST['model']); // model id are passed so it needs to map to correct neuron model database
			$arrayForModelPara = array(array());
			$arrayForModelPara = queryDatabaseForParameters($arrayForModelPara,$modelName);
			//fwrite($myfile, "neuron".$number."\n");
			//fwrite($myfile,'\n');
			if($number == 1){
				echo "neurons is layer 1: ".$_POST['totalNeuronsEachLayer'.$number];
			}
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
				$destdev=$data->createElement("destdevice", $arraywithDevNum[$neuronNumber - 2]);//temporary 1 is the destination fpga
				$packet->appendChild($destdev);
				$sourcedev=$data->createElement("sourcedevice",65532);
				$packet->appendChild($sourcedev);
				
				$simid = $data->createElement("simID",$simNum);
				$packet->appendChild($simid);	

				$command=$data->createElement("command",24);
				$packet->appendChild($command);
				$timestamp=$data->createElement("timestamp",0);
				$packet->appendChild($timestamp);
				$neuronid = $data->createElement("neuronid", $neuronNumber);
				$packet->appendChild($neuronid);
				$modelid=$data->createElement("modelid",$_POST['model']);
				//$modelid=$data->createElement("modelid",$modelName);
				$packet->appendChild($modelid);
				
				/*foreach ($ModelLibrary->neuron as $model){
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
				}*/

				for ($i=0; $i <count($arrayForModelPara) ; $i++) { 
					#--iterating through all the parameters and values for each one 
					$item=$data->createElement("item");
					$itemid=$data->createElement("itemid",$arrayForModelPara[$i][0]);
					$packet->appendChild($itemid);
					$itemtype=$data->createElement("itemtype",$arrayForModelPara[$i][3]); //to understand these numbers, have a look at line 29 which shows
																						  // the table structure in the database
					$packet->appendChild($itemtype);
					$itemdatatype=$data->createElement("itemdatatype",$arrayForModelPara[$i][4]);
					$packet->appendChild($itemdatatype);
					$itemintegerpart=$data->createElement("itemintegerpart",$arrayForModelPara[$i][5]);
					$packet->appendChild($itemintegerpart);
					$inlsb=$data->createElement("inlsb",$arrayForModelPara[$i][7]);
					$packet->appendChild($inlsb);
					$inmsb=$data->createElement("inmsb",$arrayForModelPara[$i][8]);
					$packet->appendChild($inmsb);
					$outlsb=$data->createElement("outlsb",$arrayForModelPara[$i][9]);
					$packet->appendChild($outlsb);
					$outmsb=$data->createElement("outmsb",$arrayForModelPara[$i][10]);
					$packet->appendChild($outmsb);
					$itemvalue=$data->createElement("itemvalue",$arrayForModelPara[$i][6]);
					$packet->appendChild($itemvalue);
				// $packet->appendChild($item);
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
	<input type="hidden" value=<?php echo $simNum; ?> name="simNum">
	<br>
	<input type="submit" value="Create topology">
</form><br><br>
<?php
}

else{
	###############################################################################################
	#							DIFFERENT MODEL PER LAYER
	###############################################################################################


	$neuron_num = 0; //this counts the neuron number and assigns neuron number to it

	for ($number = 1; $number <= $_POST['noOflayers']; $number++){
		echo "Model id: ".$_POST['model'.$number]; //==>Returns model id number for each layer..
		//these model id can be retraced back to model name which will then be used to get the parameters to generate xml files with all the nueron_init file
		$modelName = getModelNameFromModelID($_POST['model'.$number]); // model id are passed so it needs to map to correct neuron model database
		$arrayForModelPara = array(array());
		$arrayForModelPara = queryDatabaseForParameters($arrayForModelPara,$modelName);

		?>
		<input type="hidden" name=<?php echo "totalNeuronsEachLayer".$number; ?> value=<?php echo $_POST["totalNeuronsEachLayer".$number]; ?>>
		<?php 

		for($eachlayer = 1; $eachlayer<=$_POST['totalNeuronsEachLayer'.$number]; $eachlayer++){
			//echo "Iteration: ".$eachlayer."\n";
			//counts the neuron number
			$neuron_num = $neuron_num + 1;
			//echo "neuron number: ".$neuron_num;
			$packet=$data->createElement("packet");
			//$destdev=$data->createElement("destdevice",$_POST['name'.$number]+1);
			$destdev=$data->createElement("destdevice",$arraywithDevNum[$neuron_num - 2]);
			$packet->appendChild($destdev);
			$sourcedev=$data->createElement("sourcedevice",65532);
			$packet->appendChild($sourcedev);
			
			$simID = $data->createElement("simID",$simNum);
			$packet->appendChild($simID);	

			$command=$data->createElement("command",24);
			$packet->appendChild($command);
			$timestamp=$data->createElement("timestamp",0);
			$packet->appendChild($timestamp);
			$neuronid= $data->createElement("neuronid", $neuron_num);
			$packet ->appendChild($neuronid);
			$modelid=$data->createElement("modelid",$_POST['model'.$number] ); // the model number are same model num + . eg if there are 3 same 
			//models then starting index for diff model is 3+1 = 4 and 5,6....
			$packet->appendChild($modelid);
			//adding values as per the model 
			//different model has different fields
			/*foreach ($ModelLibrary->neuron as $model){
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
			}*/

			for ($i=0; $i <count($arrayForModelPara) ; $i++) { 
				#--iterating through all the parameters and values for each one 
				$item=$data->createElement("item");
				$itemid=$data->createElement("itemid",$arrayForModelPara[$i][0]);
				$packet->appendChild($itemid);
				$itemtype=$data->createElement("itemtype",$arrayForModelPara[$i][3]); //to understand these numbers, have a look at line 29 which shows
																					  // the table structure in the database
				$packet->appendChild($itemtype);
				$itemdatatype=$data->createElement("itemdatatype",$arrayForModelPara[$i][4]);
				$packet->appendChild($itemdatatype);
				$itemintegerpart=$data->createElement("itemintegerpart",$arrayForModelPara[$i][5]);
				$packet->appendChild($itemintegerpart);
				$inlsb=$data->createElement("inlsb",$arrayForModelPara[$i][7]);
				$packet->appendChild($inlsb);
				$inmsb=$data->createElement("inmsb",$arrayForModelPara[$i][8]);
				$packet->appendChild($inmsb);
				$outlsb=$data->createElement("outlsb",$arrayForModelPara[$i][9]);
				$packet->appendChild($outlsb);
				$outmsb=$data->createElement("outmsb",$arrayForModelPara[$i][10]);
				$packet->appendChild($outmsb);
				$itemvalue=$data->createElement("itemvalue",$arrayForModelPara[$i][6]);
				$packet->appendChild($itemvalue);
			// $packet->appendChild($item);
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
	<input type="hidden" value=<?php echo $simNum; ?> name="simNum">
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
