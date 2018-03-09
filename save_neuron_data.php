<?php
include("head.html")
?>


<?php 
	//query database to get all the parameters.
	function queryDatabaseForParameters($arrayForModelPara,$model){
		$server = 'localhost';
	  	$user = 'root';
	  	$pass = 'cncr2018';
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

	} //end of function


	function getFPGADevice($simNum){
		/*
		Queries FPGAPool database to get available FPGA for device assignment. 
		The first available FPGA are utilized. Sometimes, FPGA may be down for maintenance or some other simulation might be
		running so to avoid that this approach is useful
		*/
		$server = 'localhost';
	  	$user = 'root';
	  	$pass = 'cncr2018';
	  	$db = 'WebInterface';
	  	$availableFPGANum = 0;
	  	try{
	  		//create connection
		  	$connection = mysqli_connect("$server",$user,$pass,$db);
		  	//$_POST['model'] is the selected model from the previous page
		  	//since the table is named with the same model we can select table with the model name
		  	$FPGAQuery = mysqli_query($connection, "select FPGANumber from FPGAPool where Maintenance = '0' and OnlineStatus = '0'") 
					or die("No model found!!!!".mysql_error());
			$loopCounter = 0;
			if(mysqli_num_rows($FPGAQuery)>0){
				while($availableFPGA = mysqli_fetch_assoc($FPGAQuery)){
					//getting the very first available FPGA 
					$availableFPGANum = $availableFPGA['FPGANumber'];

					//Now that we have the available FPGA, lets mark it as on for OnlineStatus so that it wont be assigned to another simulation
					//during the process

					$updateStatus = "UPDATE FPGAPool SET OnlineStatus = '1', Simulationid = '$simNum', ConfigureDate = 'now()'  WHERE FPGANumber = '$availableFPGANum'";
					#mysqli_query($sql);
					if(mysqli_query($connection,$updateStatus) === TRUE){
						//echo "Record updated successfully";
					}	
					else{
						//echo "Error updating the record: ".$connection->error;
					}	
					//end of updating online status

					break;
				}
			}
			
			mysqli_close($connection);
		  	}

	  	catch(Exception $e){
	  		echo "Cannot establish connection !!";
	  	}
	  	return $availableFPGANum;
	} //end of function getFPGADevice



 ?>

<div class = "container">
	<div class="col-sm-12">
		<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->NeuronModelParameter-><b>Creating Initialisation File</b></h6></font>
		<?php
		if ($_SESSION['flag']==1){
			$simNum = $_POST['simNum'];
			echo "simunation id: ", $simNum;
				//--------------------------------
			//echo getFPGADevice($simNum);
			$userID = $userLogged . '_'.$simNum;
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
				$neuronlistPath = "SimulationXML/".$userLogged . "/NeuronList.txt";
				$myfile = fopen($neuronlistPath, "w") or die("Unable to open file!");
			?>

			<form action="topology.php" method="post">
				<?php
				##################################################################################################
				$DevicesWithExactNumOfNeurons= intval($_POST['totalNeurons']/8);
				if($_POST['totalNeurons']%8 > 0){
					$extraDevice = 1;
				}
				else{
					$extraDevice = 0;
				}
				$totalRequiredDevice = $DevicesWithExactNumOfNeurons + $extraDevice;

				echo "total device required: ",($totalRequiredDevice);
				
				#Multiple devices per simulation
				#calcualting how many devices are required for selected number of neurons
				#each device can only have 8 neurons
				echo "total neurons: ", $_POST['totalNeurons'];
				$arraywithDevNum = array($_POST['totalNeurons']);
				echo "<br>";
				$totalNeuronsPerFPGGA = 8;
				$destinationFPGA = 0;
				for ($neuronNum=0; $neuronNum < $_POST['totalNeurons'] ; $neuronNum++) { 
					# code...
					
					# iterate through each Neuron and check for available FPGA only at multiples of $totalNueronsPerFPGA
					#i.e. for eg if there are 8 neurons per FPGA then, 0 - 7 neurons get one FPGA and similary 8-15 gets next FPGA
					#since the FPGA are to be checked in the database for availability, we can only check at these multiples. so that each check will
					#result in 1 FPGA wcich will be engou for next 7 neurons resultign in 8 neurons per FPGA or anyother number depending 
					//echo "Intval: ",$neuronNum/($totalNeuronsPerFPGGA+1);
					//echo "<br>";
					if (($neuronNum == 0) || is_int($neuronNum/($totalNeuronsPerFPGGA))){
						//calling functions that gets the FPGA, simid is argument to update the table
						//New FPGA is queried only at the begining and when the FPGA capacity gets full
						echo "<br> Condition Met at Neuron !!",$neuronNum;
						echo "<br>";
						$destinationFPGA = getFPGADevice($simNum);
						echo "destinationFPGA: ",$destinationFPGA;
					}
					//echo "<br>first for loop : ",$neuronNum;
					//echo "<br>";
					//stores destination device ID for corresponding neuron indicated with index. device at index 0 if for neuron 1
					$arraywithDevNum[$neuronNum] = $destinationFPGA;

					
				/*for ($i=$totalRequiredDevice ; $i>0; $i--) { 
					# code...
					echo "iteration i: ",$i,"<br>";
					if(intval($totalNeu/8) >= intval($i-1)){
						echo "destdev: ",$i,"<br>";
						$destdevice = $i ;
						#echo 'destdevice: ',$destdevice;
						$arraywithDevNum[$totalNeu-1] = $destdevice;
						break;
					}
					#echo "destdevice: ",$destdevice;
				}*/
				}
				print_r($arraywithDevNum);

				echo "<br> array size: ",sizeof($arraywithDevNum);
				file_put_contents("SimulationXML/".$userLogged . "/DeviceId_" . $userID . ".bin",serialize($arraywithDevNum));
				
				###############################################################################################
				#						ALL SAME MODEL
				###############################################################################################
				if ($_POST['samemodel']=='yes' and $_POST['totalDiffModelNeurons']==0){

					//query database for parameter values which are stored in a 2D array
					$arrayForModelPara = array(array());
					$arrayForModelPara = queryDatabaseForParameters($arrayForModelPara,$_POST['model']);


					for ($number = 1; $number <= $_POST['totalNeurons']; $number++){
						#echo 'passed from previous :'.$_POST['neuron'.$number];
						echo "neuron number",$number,"<br>";
						fwrite($myfile, "neuron".$number."\n");
						//fwrite($myfile,'\n');

						#if($DevicesWithExactNumOfNeurons)

						?>
						<input type="hidden" value=<?php echo $_POST['neuron'.$number]; ?> name=<?php echo "neuron".$number; ?>>
						<?php

						$packet=$data->createElement("packet");
						$destdev=$data->createElement("destdevice", $arraywithDevNum[$number-1]);//neurons are numbered as 1,2 but index are 0,1,2 so to get index
						$packet->appendChild($destdev);
						$sourcedev=$data->createElement("sourcedevice",65532);
						$packet->appendChild($sourcedev);
						$simID = $data->createElement("simID",$simNum);
						$packet->appendChild($simID);
						$command=$data->createElement("command",24);
						$packet->appendChild($command);
						$timestamp=$data->createElement("timestamp",0);
						$packet->appendChild($timestamp);
						$neuronid = $data->createElement("neuronid", $number); #neuron number
						$packet->appendChild($neuronid);
						//$numberofneurons = $data->createElement("numberofneurons", $_POST['totalNeurons']);
						//$packet->appendChild($numberofneurons);
						
						//$modelid=$data->createElement("modelid",$_POST['model']);
						$modelid=$data->createElement("modelid",$arrayForModelPara[0][1]); //model ID is the second element in the 2d array
																						//all parameters are under the same model id so we choose [0][1] this arbitrarily
						$packet->appendChild($modelid);
						$timestepsize = $data->createElement("timestepsize",1000);
						$packet->appendChild($timestepsize);
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
						//reading from the array from database and inserting values 
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
					$data->appendChild($dom);
					$filename="SimulationXML/".$userLogged . "/Neuron_Ini_file_" . $userID . ".xml";
					$data->save($filename);
					?>



				<br>
				<input type="hidden" name="totalNeurons" value=<?php echo $_POST['totalNeurons']; ?>>
				<input type="hidden" value=<?php echo $simNum; ?> name="simNum">
				<br>
				<input type="submit" value="Create topology">
			</form><br><br>
			<?php
			}

			else{
				//for mixed group of same  and different neurons
				$subtractedSameModel = 0;

				###############################################################################################
				#						SOME SAME MODEL, SOME DIFFERENT
				###############################################################################################

				if($_POST['totalNeurons']>$_POST['totalDiffModelNeurons']){

					###############################################################################################
					#						SOME SAME MODEL FROM MIX OF MODELS
					###############################################################################################

					//FIRST THING FIRST, LETS READ THE DATABASE
					//query database for parameter values which are stored in a 2D array
					$arrayForModelPara = array(array());
					$arrayForModelPara = queryDatabaseForParameters($arrayForModelPara,$_POST['model']);

					//echo "nxt stage";
					$subtractedSameModel= $_POST['totalNeurons'] - $_POST['totalDiffModelNeurons'];


					for ($number = 1; $number < $subtractedSameModel + 1; $number++){
						fwrite($myfile,"neuron".$number."\n");
						//fwrite($myfile,'\n');
						?>

						<input type="hidden" value=<?php echo "neuron".$number; ?> name=<?php echo "neuron".$number; ?>>
						<?php 
						$packet=$data->createElement("packet");
						//$destdev=$data->createElement("destdevice",$_POST['name'.$number]+1);
						$destdev=$data->createElement("destdevice",  $arraywithDevNum[$number-1]);//temporary
						$packet->appendChild($destdev);
						$sourcedev=$data->createElement("sourcedevice",65532);
						$packet->appendChild($sourcedev);
						$simID = $data->createElement("simID",$simNum);
						$packet->appendChild($simID);
						$command=$data->createElement("command",24);
						$packet->appendChild($command);
						$timestamp=$data->createElement("timestamp",0);
						$packet->appendChild($timestamp);
						$neuronid = $data->createElement("neuronid", $number); #neuron number
						$packet->appendChild($neuronid);
						$modelid=$data->createElement("modelid",$arrayForModelPara[0][1]); //SAME LOGIC AS LINE 207
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

						//reading from the array from database and inserting values 
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
					$data->appendChild($dom);
					$filename="SimulationXML/".$userLogged . "/Neuron_Ini_file_" . $userID . ".xml";
					$data->save($filename);

				}//end of if statement

				//writing for different models
				###############################################################################################
				#			DIFFERENT MODELS - DIFFERENT MODELS ARE IDENTIFIED WITH NEURON NUMBERS
				###############################################################################################

				//SLIGHTLY DIFFERENT FOR DIFFERENT MODELS
				// THE MODELS ARE IDENTIFIED WITH MODEL.NUM FOR DIFFERENT MODELS

				//echo $_POST['name'.$number];
				for ($number = 1; $number < $_POST['totalDiffModelNeurons'] + 1; $number++){
					fwrite($myfile, "neuron".($number+$subtractedSameModel)."\n");
					$neuronidNum= $number+$subtractedSameModel;
					echo "neronid," ,$neuronidNum;
					//fwrite($myfile,'\n');
					$arrayForModelPara = array(array());
					$arrayForModelPara = queryDatabaseForParameters($arrayForModelPara,$_POST['model'.$neuronidNum]); // this will direct to the right model for that neuron

					//READING DATABASE FOR THE MODEL


					?>
					
					<input type="hidden" value=<?php echo "neuron".($number+$subtractedSameModel); ?> name=<?php echo "neuron".($number+$subtractedSameModel); ?>>
					<?php 
					$packet=$data->createElement("packet");
				//$destdev=$data->createElement("destdevice",$_POST['name'.$number]+1);
					$destdev=$data->createElement("destdevice", $arraywithDevNum[$neuronidNum-1]);
					$packet->appendChild($destdev);
					$sourcedev=$data->createElement("sourcedevice",65532);
					$packet->appendChild($sourcedev);
					
					$simID = $data->createElement("simID",$simNum);
					$packet->appendChild($simID);

					$command=$data->createElement("command",24);
					$packet->appendChild($command);
					$timestamp=$data->createElement("timestamp",0);
					$packet->appendChild($timestamp);
					$neuronid = $data->createElement("neuronid", $neuronidNum); #neuron number
					$packet->appendChild($neuronid);
					$modelid=$data->createElement("modelid",$arrayForModelPara[0][1]); //same logic as line 29 // the model number are same model num + . eg if there are 3 same 
					//models then starting index for diff model is 3+1 = 4 and 5,6....
					$packet->appendChild($modelid);

					/*foreach ($ModelLibrary->neuron as $model){
						if ($model->neuronid==$_POST['model' . ($number+$subtractedSameModel)]){
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
								$itemvalue=$data->createElement("itemvalue",$_POST["neuron" . ($number+$subtractedSameModel) . "item" . $modelitem->itemid]);
								$packet->appendChild($itemvalue);
					// $packet->appendChild($item);
							}
						}
					}*/

					//reading from the array from database and inserting values 
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
				}//END OF MAIN FOR LOOP
				$data->appendChild($dom);
				$filename="SimulationXML/".$userLogged . "/Neuron_Ini_file_" . $userID . ".xml";
				$data->save($filename);	

				?>
				<br>
				<input type="hidden" name="totalNeurons" value=<?php echo $_POST['totalNeurons']; ?>>
				<input type="hidden" value=<?php echo $simNum; ?> name="simNum">
				<br>
				<input type="submit" value="Create topology">
				</form><br><br>
				<?php
				}
				fclose($myfile);
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
