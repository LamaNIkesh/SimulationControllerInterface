<!-- 
			GRAND FINALE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! THIS IS WHERE EVERYTHING COMES TOGETHER........OR DOES IT? 
			So all the different packets such as siminit, neuroninit, simulationpara etc etc are merged together to form a single initialisation file in xml
-->

<?php
include("head.html")
?>

<div class = "container">
	<div class="col-sm-12">
		<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->NeuronModelParameter->Creating Initialisation File->Create Topology->Topology Viewer->Save Topology-><b>Save Initialisation file</b></h6></font>
<?php
//to avoid loding problem, not sure of the cause but this seems to get rid of loading entity issu
//still looking into it
libxml_disable_entity_loader(false);

//functino to return a unique multidimensional array
function super_unique($array)
{
  $result = array_map("unserialize", array_unique(array_map("serialize", $array)));

  foreach ($result as $key => $value)
  {
    if ( is_array($value) )
    {
      $result[$key] = super_unique($value);
    }
  }

  return $result;
}

#####################################################################################################################################################
#--------------------------------------------LIST OF FUNCTIONS---------------------------------------------------------------------------------------
#####################################################################################################################################################

function generateFPGAConfigurationXML($fileLocationOfNeuronInit){
	/*This function looks into neuron initialisation file and extracts which FPGA devices are 
	  used for what neuron models. Based on these information, a packet for each FPGA to configure
	  it with correct model.
	  #TODO: Check if allocated FPGA device has some faults, if any then replace with next available FPGA
	*/

	
	//load the neuron initialisation file which contains information for each neuron and what FPGA device each neuron 
	//is destined for. 
	if(file_exists($fileLocationOfNeuronInit)){ #Load XML file
		$xmlNeuronInit = simplexml_load_file ($fileLocationOfNeuronInit);
		//echo "test";
	}
	else {
		exit ('Could not load the file...');
	}

	$numberOfPackets = 0;
	//these are just to check FPGA device num and corresponding model id 
	$FPGAdevice = 0;
	$previousFPGAdevice = -1;
	$modelid = 0;
	$previousmodelid = -1;
	//counter for loop
	$counter = 0;
	//counter for array indexing, only increased when array is updated
	$arrayIndexing = 0;
	//array to store FPGA device and corresponding model for it
	$arrayForFPGAdeviceModelid = array(array());

	foreach($xmlNeuronInit->packet as $packet){
		//echo $packet->destdevice;
		//echo $packet->modelid;
		if ($counter == 0){
			echo "counter zero <br>";
			//Assignment for the first iteration
			$previousFPGAdevice = intval($packet->destdevice);
			$previousmodelid = intval($packet->modelid);
			$arrayForFPGAdeviceModelid[$arrayIndexing][0] = $previousFPGAdevice;
			$arrayForFPGAdeviceModelid[$arrayIndexing][1] = $previousmodelid;
			$arrayIndexing++;
		}
		else{
			$FPGAdevice = intval($packet->destdevice);
			$modelid = intval($packet->modelid);
			echo "FPGAdevice: ".$FPGAdevice."<br>";
			echo "prevFPGAdevice: ".$previousFPGAdevice."<br>";
			echo "modelid: ".$modelid."<br>";
			echo "prevmodelid: ".$previousmodelid."<br>";
			//had to do intval because, it is of type simle xml which couldnt do any caomparison
			if(intval($FPGAdevice) == intval($previousFPGAdevice) && intval($previousmodelid) == intval($modelid)){
				echo "same results---neuron ".$counter;
			}
			else{
				echo "<br> No match <br>";
				$arrayForFPGAdeviceModelid[$arrayIndexing][0] = $FPGAdevice;
				$arrayForFPGAdeviceModelid[$arrayIndexing][1] = $modelid;
				//Now lets make these values as previous values for next comaprison
				$previousFPGAdevice = $FPGAdevice;
				$previousmodelid = $modelid;
				$arrayIndexing++;
			}
		}
		$counter++;

	}
	//print_r ($arrayForFPGAdeviceModelid);
	//echo "<br> Length of the array is :".count($arrayForFPGAdeviceModelid)."<br>";
	//echo "<br>Elements: ".$arrayForFPGAdeviceModelid[0][0]. $arrayForFPGAdeviceModelid[0][1]."<br>";
	//echo "<br>Elements: ".$arrayForFPGAdeviceModelid[1][0]. $arrayForFPGAdeviceModelid[1][1]."<br>";
	//echo "<br>Elements: ".$arrayForFPGAdeviceModelid[0][0]. $arrayForFPGAdeviceModelid[0][1]."<br>";
	//echo "<br>Elements: ".$arrayForFPGAdeviceModelid[0][0]. $arrayForFPGAdeviceModelid[0][1]."<br>";
	echo "<br> Target FPGA array<br>";
	print_r($arrayForFPGAdeviceModelid);
	echo "<br>Unique<br>";
	//There is a repetition of same FPGA device, we need to get rid of that and return only the unique ones
	//for eg. if there are 5 entries of 3 unique FPGA devices, then we return only the 3. 
	$uniquearray = super_unique($arrayForFPGAdeviceModelid);
	$uniquearray = array_values($uniquearray); // This resets the index. eg. 
												//[3] => Hello
												//	[7] => Moo
												//	[45] => America
												// To 
												//	[0] => Hello
												//	[1] => Moo
												//	[2] => America
	print_r($uniquearray);
	//print_r(array_unique($arrayForFPGAdeviceModelid));
	echo "<br>";
	return $uniquearray;

}


function createFPGAConfigurationXML($arrayForFPGAdeviceModelid,$storingLocation,$simNum){


	//Lets get FPGA device info from saved array with FPGA assignment into each neuron

	//$FinalSortedNeuronsFPGAArray = unserialize(file_get_contents("SimulationXML/".$userLogged . "/FinalSortedNeuronsFPGAArray_" . $userID . ".bin"));

	//Once, we have the array with FPGA number and model id, we can generate packet to program the FPGA
	$data  = new DOMDocument;
	$data ->formatOutput = true;
	$dom=$data->createElement("sUploadSof");



	for ($i=0; $i < count($arrayForFPGAdeviceModelid) ; $i++) { 
		# code...
		$packet=$data->createElement("packet");
		$destdev=$data->createElement("destdevice",65535); //Destined for IM 
		$packet->appendChild($destdev);
		$sourcedev=$data->createElement("sourcedevice",65532); // Needs to specify the source; is the NC??
		$packet->appendChild($sourcedev);
		$simID = $data->createElement("simID", $simNum);
		$packet->appendChild($simID);
		$command=$data->createElement("command",30);
		$packet->appendChild($command);
		$timestamp=$data->createElement("timestamp",0);
		$packet->appendChild($timestamp);
		$targetfpga=$data->createElement("targetfpga",$arrayForFPGAdeviceModelid[$i][0]); //select the FPGA id
		$packet->appendChild($targetfpga);

		//lets get url and filename for the model
		$arrayStoringUrlFilename = getModelURLandFilenameForFPGA($arrayForFPGAdeviceModelid[$i][1]); // since, model id is stored at second index i.e.1
		//the function returns an array of two elements, [0] = location url, [1] = filename

		$filename=$data->createElement("filename",$arrayStoringUrlFilename[1]);	//stores filename for model assigned for this particular FPGA
		$packet->appendChild($filename);
		$url=$data->createElement("url",$arrayStoringUrlFilename[0]); // Same logic as earlier part
		$packet->appendChild($url);

		$dom->appendChild($packet);
	}
	$data->appendChild($dom);
	$data->save($storingLocation);
}

//This functino is called inside createFPGAConfigurationXML(..) function
function getModelURLandFilenameForFPGA($modelId){
	//returns correct neuron model from the databse for the FPGA based on which neuron was assigned for which FPGA
	$server = 'localhost';
	$user = 'root';
	$pass = 'cncr2018';
	$db = 'WebInterface';
	$location_filename_array= array(2);//just storing url and filename
	try{
		$connection = mysqli_connect("$server",$user,$pass,$db);
		
		$result = mysqli_query($connection, "select * from ModelLibrary where ModelID ='$modelId'") 
			or die("No user found!!!!".mysql_error());
		//$row = mysqli_fetch_array($result);
		if(mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				//storing url and filename into an array	
				$location_filename_array[0] = $row['LocationURL'];
				$location_filename_array[1] = $row['Filename'];
				return $location_filename_array;
			}
		}
	}
	catch (Exception $e) {
		echo "error: ".$e->getMessage();
					}


}

function ParseNeuronInitXMLtoArrayForModelId($neuronInitXMLFile, $userLogged,$topology, $userID){
	/*
		So heres the story. The item id in synapse packet needs to (neuron number + last item id from that neuron).
		Not clear!!--> for example if Izh have 6 parameters then synpase item id for incoming synpase from neuron 5 is (5+6)=11. 
		So that everything falls into a sequential order for hardware implementation. I do as I am told. 

		So here we only try to extract neuron id, model id for that neuron and number of parameters for that mode.

		When we create synpase packet we will add that value after comparing with the neuron id. 

		Here we cut open old neuron initialisation packet and extract those data to be stored in an array. 
	*/
	$neuronInitPacket = simplexml_load_file($neuronInitXMLFile) or die("Error: Cannot create object");

	$ModelIdParaNeuron = array(array());
	$neuronIndex = 0;
	$fieldsIndex = 0;

	foreach($neuronInitPacket->packet as $packet){
		#lets dig into values
		$numberOfItemsForEachModel = ($packet->count() - 8)/2; // First 8 items upto timesstepsize are not part of actual neuron item.
														  		//subtracting those and diving remaining by 2 gives how many itesmare there.	
		foreach ($packet as $key => $value){
			/*Sample packet.
			<packet>
			    <destdevice>175</destdevice>						[0]
			    <sourcedevice>65532</sourcedevice>					[1]
			    <simID>3</simID>									[2]
			    <command>24</command>								[3]
			    <timestamp>0</timestamp>							[4]	
			    <neuronid>19</neuronid>								[5]
			    <modelid>1</modelid>								[6]
			    <timestepsize>1000</timestepsize>					[7]
			    <itemid>1</itemid>									[8]
			    <itemvalue>5</itemvalue>							[9]
			    <itemid>2</itemid>									[10]
			    <itemvalue>50</itemvalue>							[11]
			    <itemid>3</itemid>									[12]
			    <itemvalue>2</itemvalue>							[13]
			    <itemid>4</itemid>									[14]
			    <itemvalue>2</itemvalue>							[15]
		    </packet>
		    */
			
			if($fieldsIndex == 5){
				$ModelIdParaNeuron[$neuronIndex][0] = intval($value); # index 5 is for neuron id
			}
			elseif($fieldsIndex == 6){ //at index 6 is for model id. Based on this model id we know how many parameters are there for each neuron
				$ModelIdParaNeuron[$neuronIndex][1] = intval($value);	
				$fieldsIndex++;
				$ModelIdParaNeuron[$neuronIndex][2] = $numberOfItemsForEachModel;
				break;
			}
			else{

			}

			$fieldsIndex++;
		}
		$fieldsIndex = 0;
		$neuronIndex++;		
	}

	echo "<br>--------------Neuron Data Array---------------<br>";
	print_r($ModelIdParaNeuron);
	echo "Neurons count: ".count($ModelIdParaNeuron);
	echo "Each neuron fields:".count($ModelIdParaNeuron[0]);

	return $ModelIdParaNeuron;
}


function ParseSynapseXMLtoArray($TopologyXMLFile,$userLogged,$topology, $userID,$ModelIdParaNeuron){
	# From topology file, a new packet is generated which consists of all synapse information for each neuron
	# Weights are randomly generated to start the simulation
	
	#$synapsePacket = new DOMDocument();
	#$synapsePacket->load("SimulationXML/".$userLogged . $topology. "/Topo_Ini_file_" . $userID . ".xml");

	/*<packet>
		<destdevice>140</destdevice>					[0]
		<sourcedevice>65532</sourcedevice>				[1]
		<simID>3</simID>								[2]	
		<command>11</command>							[3]
		<timestamp>0</timestamp>						[4]
		<neuronid>2</neuronid>							[5]
		<numberofneurons>4</numberofneurons>			[6]
		<preneuronid>4</preneuronid>					[7]
		<preneuronid>1</preneuronid>					[8]
	</packet>*/


	$synapsePacket = simplexml_load_file($TopologyXMLFile) or die("Error: Cannot create object");
	#var_dump($synapsePacket);
	#print_r("<br>------------SynapsePacket: ".$synapsePacket."<br>");

	$synapseInfoArray = array(array());
	$neuronIndex = 0;
	$fieldsIndex = 0;
	//$itemID = 0; //for numbering synpases
	foreach ($synapsePacket->packet as $packet) {
		# code...
		echo "####Number of children: ".$packet->count();
		$numberOfSynapses = $packet->count() - 7; # since the actual header before synpases are 7. Subtracting it from total count gives how many incoming
												  #synapses are there.	
		foreach ($packet as $key => $value) {
			# code...
			#echo "<br>.........$key->".$value."<br>";
			
			if($fieldsIndex == 3){
				$synapseInfoArray[$neuronIndex][$fieldsIndex] = 28; #command for synapses
			}
			elseif($fieldsIndex == 6){ //at index 6 <numberofneurons> tag will be replaced with <numberofsynapses>
				$synapseInfoArray[$neuronIndex][$fieldsIndex] = $numberOfSynapses;	
			}
			elseif($fieldsIndex > 6){ //so basically going through each <preneuronid> and assigning weight to the synapses
				$synapseInfoArray[$neuronIndex][$fieldsIndex] = intval($value);
				//Next tag will be actual weight of the synpase, since it is a next tag lets increase the $fieldsIndex 
				$fieldsIndex++;
				$synapseInfoArray[$neuronIndex][$fieldsIndex] = 1; //arbitrary value of 1 for now
			}
			else{
				$synapseInfoArray[$neuronIndex][$fieldsIndex] = intval($value);	
			}

			$fieldsIndex++;
		}
		$fieldsIndex = 0;
		$neuronIndex++;
	}
	echo "<br>--------------Synapse Array---------------<br>";
	print_r($synapseInfoArray);
	echo "Neurons count: ".count($synapseInfoArray);
	echo "Each neuron fields:".count($synapseInfoArray[0]);

	$userLogged = $userLogged;
	$topology = $topology;
	$userID = $userID;

	#FEEDING INTO FUNCTION WHICH ACTUALLY CREATES THE XML FILE FOR SYNAPSES....At the end it also returns the file location the synapse xml.
	#This filelocation is used to send the xml file to the IM.
	$synapseFileLocation = generateXMLFromParsedArray($synapseInfoArray,$userLogged,$topology, $userID, $ModelIdParaNeuron);

	
	//returning synapse xml file location
	return $synapseFileLocation;
}

function generateXMLFromParsedArray($xmlParsedArray,$userLogged,$topology, $userID,$ModelIdParaNeuron){
	#generating xml from 2D array parsed from an xml from ParseSynapseXMLtoArray() function
		/*
	$ModelIdParaNeuron array contains info about neuron number with its corresponding model id and number of items 
	
	STRUCTUREOF $xmlParsedArray is, 
    
    																					  (neuronid+itemCount)           (synapseweigthVal)
																									^                           ^
    index           destDev   | sourceDev  | simID  | cmd     |  tmsp  | neuronid | noOfSynps | itemID | itemVal | itemID  | itemVal | itemID  | itemVal | itemID  | itemVal 

	[4] => Array ( [0] => 236 [1] => 65532 [2] => 3 [3] => 28 [4] => 0 [5] => 5   [6] => 4    [7] => 1 [8] => 1  [9] => 2  [10] => 1 [11] => 3 [12] => 1 [13] => 4 [14] => 1 ) 	



	*/
	$synData = new DomDocument();
	$synData->formatOutput = true;
	$domDoc=$synData->createElement("synapsepacket");
	
	$neuronIndex = 0;
	$fieldsIndex = 0;

	$neuronId = 0;

	for ($i=0; $i < count($xmlParsedArray); $i++) { 
		# code...
		//getting fields from each neuron
		$packet=$synData->createElement("packet");
		for ($j=0; $j < count($xmlParsedArray[$i]); $j++) { 
			# Now we are in a row, where we go through each column to basically get the value and put it in the xml format
			
			if($j == 0){
				$destDevice=$synData->createElement("destdevice", $xmlParsedArray[$i][$j]);
				$packet->appendChild($destDevice);

			}
			elseif ($j == 1){
				$sourceDevice=$synData->createElement("sourcedevice", $xmlParsedArray[$i][$j]);
				$packet->appendChild($sourceDevice);
			}
			elseif ($j == 2){
				$simID=$synData->createElement("simID", $xmlParsedArray[$i][$j]);
				$packet->appendChild($simID);
			}
			elseif($j == 3){
				$command=$synData->createElement("command", $xmlParsedArray[$i][$j]);
				$packet->appendChild($command);
			}
			elseif ($j == 4){
				$timestamp=$synData->createElement("timestamp", $xmlParsedArray[$i][$j]);
				$packet->appendChild($timestamp);
			}
			elseif ($j == 5){
				$neuronid=$synData->createElement("neuronid", $xmlParsedArray[$i][$j]);
				$packet->appendChild($neuronid);
				$neuronId = $xmlParsedArray[$i][$j];
				echo "<br>--------Neuron ID-------->".$neuronId."<br>";
				//$addedItemNum = $neuronId; //THis is a long story, just tryna make it work .... why am i so stupid?!!!!
			}
			elseif ($j == 6){
				$numberofsynapses=$synData->createElement("numberofsynapses", $xmlParsedArray[$i][$j]);
				$packet->appendChild($numberofsynapses);
			}

			else{
				//Now we add itemid and synaptic weights
				//item id is last item number plus incoming synpase from neuron
				//for eg for Izh model, last item number is 9, so if neuron is receiving input from neuron 10 then item id is (10+9) = 19

				$neuronId = $xmlParsedArray[$i][$j]; //This neuron Id is neuron id of the incoming synapse neuron. 
				echo "<br>------Input synpase neuron------->".$neuronId."<br>";
				echo "---------Model Para count-------->".$ModelIdParaNeuron[$neuronId-1][2]."<br>";
				//ITEM ID IS ITEM NUMBER FROM A NEURON MODEL + INCOMING SYNPASE NEURON
				$itemid=$synData->createElement("itemid", $neuronId + $ModelIdParaNeuron[$neuronId-1][2]); // this array has model para num at index 3
				$packet->appendChild($itemid);
				//$neuronId++;
				$j++; //Next column is itemvalue, hence the increment. 
				$itemvalue=$synData->createElement("itemvalue", $xmlParsedArray[$i][$j]);
				$packet->appendChild($itemvalue);
			}
		}
		$domDoc->appendChild($packet);
	}
	$synData->appendChild($domDoc);
	$filename="SimulationXML/".$userLogged . $topology. "/Initialisation_file_Synapse_" . $userID . ".xml";
	echo "<br>Filename is :".$filename."<br>";
	$synData->save($filename);

	$synapseFileLocation = $filename;
	return $synapseFileLocation;

}

#####################################################################################################################################################
#--------------------------------------------END OF FUNCTIONS---------------------------------------------------------------------------------------
#####################################################################################################################################################


if ($_SESSION['flag']==1){

	//this section appends all three xml files; simulation, neuron initialisation, topology and stimulation (if present)
	//into a single simulation file that is ready to send to the IM 
	$simNum = $_POST['simNum'];
	//Reading simulation id from the database

	echo "simulation number is : ".$simNum;
	//------------------------------------------------
	
	$topo=false;
	$stim=false;
	
	$userID = $userLogged .'_'.$simNum;
	echo $userID;
	//$doc1=file($userLogged . "/" . $userLogged . $simNum . ".xml");
	//$doc2=file($userLogged . "/Neuron_Ini_file_" . $userLogged . $simNum . ".xml");
	$topology = '';
	#echo "topology: ".$_POST['topology'];
	if($_POST['topology'] == 'layeredTopology'){
		$topology = '/Layered';
	}
	else{	
		$topology = '';
	}

	$fileLocation = "SimulationXML/".$userLogged . $topology."/Neuron_Ini_file_" . $userID. ".xml";
	
	//First thing first , Neuron infor i.e.which model to which FPGA. This info will be used to generate configuration xml for each FPGA with
	//correct neuron model
	//This function returns an array with FPGA to model map where element[0] is FPGA num and element [1] is the model id
	$arrayForFPGAdeviceModelid = generateFPGAConfigurationXML($fileLocation);
	//well this array along with where to store temporarily, which will be read again, broken down and added at the begining of the Initialisation file
	//little detour but keeping it this way as debuggin is easier.
	$storingLocation = "SimulationXML/".$userLogged . $topology."/cUploadSof_" . $userID. ".xml";
	//actually creating xml and storing
	createFPGAConfigurationXML($arrayForFPGAdeviceModelid,$storingLocation,$simNum);



	##################################################################################################
	# Update UserSimulation database with number of neurons, this will be used for results xml parsing
	##################################################################################################

	#Get total number of neurons from Sim_init file already created at the early stage of the network creation
	if(file_exists("SimulationXML/".$userLogged .$topology. "/Sim_Ini_file_" . $userID. ".xml")){ #Load XML file
		$SimInitXML = simplexml_load_file ("SimulationXML/".$userLogged .$topology. "/Sim_Ini_file_" . $userID. ".xml");
		//echo "test";
	}
	#Gives the total neurons  
	echo $SimInitXML->packet->neuronsnum;
	#reading total neuron numbers
	$totalNeurons = $SimInitXML->packet->neuronsnum;
	#reading simulation duration in ms, 
	$simulationTime = $SimInitXML->packet->cyclesNum;

	#print_r($xmlDoc_totalneurons);

	$server = 'localhost';
	$user = 'root';
	$pass = 'cncr2018';
	$db = 'WebInterface';
	try{
		$connection = mysqli_connect("$server",$user,$pass,$db);
		
		$updateNeuronNum = "UPDATE UserSimulation SET NoOfNeurons = '$totalNeurons', SimTime_ms = '$simulationTime' WHERE SimulationId = '$simNum'";
		#mysqli_query($sql);
		if(mysqli_query($connection,$updateNeuronNum) === TRUE){
			echo "Record updated successfully";
		}	
		else{
			echo "Error updating the record: ".$connection->error;
			}	
	}
	catch (Exception $e) {
		echo "error: ".$e->getMessage();
					}


	#######################################################################################################################################
	#	Lets get to some operation now. Get gloves and scissors.
	# 	SimInit, NeuronInit, TopoInit files are opened and merged together with synpase file as well. XMl version of HumanCentipede!!!!????				
	#				


	$xmlDoc = new DomDocument();
	$xmlDoc ->load($storingLocation); //where the .sof configuration packet is stored
	unlink($storingLocation);

	$xmlDoc1 = new DOMDocument();
	$xmlDoc1->load("SimulationXML/".$userLogged .$topology. "/Sim_Ini_file_" . $userID. ".xml");
	
	unlink("SimulationXML/".$userLogged .$topology. "/Sim_Ini_file_" . $userID. ".xml");
	$xmlDoc2 = new DOMDocument();

	$xmlDoc2->load("SimulationXML/".$userLogged . $topology."/Neuron_Ini_file_" . $userID. ".xml");
	
	//extractiong model number and number of parameters for each model to be used for synpase packet
	$neuronInitXMLLocation = ("SimulationXML/".$userLogged . $topology."/Neuron_Ini_file_" . $userID. ".xml");
	$ModelIdParaNeuron = array(array());
	//ParseNeuronInitXMLtoArrayForModelId function returns a 2D array with each row for neuron with columns being neuron id, model id and parameter count
	$ModelIdParaNeuron = ParseNeuronInitXMLtoArrayForModelId($neuronInitXMLLocation, $userLogged,$topology, $userID);
	print_r($ModelIdParaNeuron);


	unlink("SimulationXML/".$userLogged . $topology. "/Neuron_Ini_file_" . $userID . ".xml");
	
	if (file_exists("SimulationXML/".$userLogged .$topology. "/Topo_Ini_file_" . $userID . ".xml")){
		$xmlDoc3 = new DOMDocument();
		$xmlDoc3->load("SimulationXML/".$userLogged . $topology. "/Topo_Ini_file_" . $userID . ".xml");
		$TopoXMLLocation = "SimulationXML/".$userLogged . $topology. "/Topo_Ini_file_" . $userID . ".xml";
		#lets generate synapse xml
		$synapseFileLocation = ParseSynapseXMLtoArray($TopoXMLLocation,$userLogged,$topology, $userID,$ModelIdParaNeuron);
		echo "<br>The synapse File location is".$synapseFileLocation."<br>";
		$topo=true;
		unlink("SimulationXML/".$userLogged . $topology. "/Topo_Ini_file_" . $userID . ".xml");
	}
	
	if (file_exists("SimulationXML/".$userLogged . $topology."/Stim_Ini_file_" . $userID . ".xml")){
		$xmlDoc5 = new DOMDocument();
		$xmlDoc5->load("SimulationXML/".$userLogged . $topology. "/Stim_Ini_file_" . $userID . ".xml");
		$stim=true;
		unlink("SimulationXML/".$userLogged . $topology. "/Stim_Ini_file_" . $userID . ".xml");
	}
	
	$dom = new DOMDocument("1.0", "ISO-8859-15");
	$dom->formatOutput = true;
	$data=$dom->createElement("newSimulation");
	// Append first packet----Reset packet----------not needed anymore
	//$pack=$dom->createElement("packet");
	//$el1=$dom->createElement("destdevice", 0);
	//$pack->appendChild($el1);
	//$el2=$dom->createElement("sourcedevice", 65532);
	//$pack->appendChild($el2);
	//$el3=$dom->createElement("command", 15);
	//$pack->appendChild($el3);
	//$el4=$dom->createElement("timestamp", 0);
	//$pack->appendChild($el4);
	//$data->appendChild($pack);

	//append xmlDoc -- which is for .sof configuration
	$uploadsof = $xmlDoc ->getElementsByTagName("packet");
	foreach($uploadsof as $packet){
		$packet = $dom->importNode($packet, true);
		$data->appendChild($packet);
	}

	// Append xmlDoc1
	$meta = $xmlDoc1->getElementsByTagName("packet");
	foreach($meta as $packet){
		$packet = $dom->importNode($packet, true);
		$data->appendChild($packet);
	}

	// Append xmlDoc2
	$neuronmeta = $xmlDoc2->getElementsByTagName("packet");
	foreach($neuronmeta as $packet){
		$packet = $dom->importNode($packet, true);
		$data->appendChild($packet);
	}
	
			// Append xmlDoc4
	
		// Append xmlDoc3
	if ($topo){
		$topometa = $xmlDoc3->getElementsByTagName("packet");
		foreach($topometa as $packet){
			$packet = $dom->importNode($packet, true);
			$data->appendChild($packet);
		}
	}

	if ($stim){
		$stimmeta = $xmlDoc5->getElementsByTagName("packet");
		foreach($stimmeta as $packet){
			$packet = $dom->importNode($packet, true);
			$data->appendChild($packet);
		}
	}
	
	$dom->appendChild($data);
	$filename="SimulationXML/".$userLogged . $topology. "/Initialisation_file_" . $userID . ".xml";
	$dom->save($filename);


	?>
	<p> The metadata and neuronal XML files will be merged here. The file should be able to be downloaded.</p>
	<a id="cont" href=<?php echo "SimulationXML/".$userLogged . $topology. "/Initialisation_file_" . $userID. ".xml" ;?> download= <?php echo "Initialisation_file_" . $userID. ".xml"?>>Save initialisation file to your computer</a>
	<br><br>

	<p> The next button will send the file to the server to transform it into HEX and start the simulation.</p>
		
	<form action="PublishToTopic.php" method="post">
	<input type="submit" value="Send initialisation data to server">
	<input type="hidden" name="filenameHEX" id = "filenameHEX" value=<?php echo $userLogged . $topology."/Initialisation_file_" . $userID . ".hex" ?>>
	<input type="hidden" name="filenameXML" id = "filenameXML" value=<?php echo $filename ?>>
	<input type="hidden" value=<?php echo $simNum; ?> name="simNum">
	</form>	
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

</div>
</div>
<?php
include("end_page.html")
?>
