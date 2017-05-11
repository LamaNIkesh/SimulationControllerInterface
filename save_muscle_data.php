<?php
include("head.html")
?>
<?php
$flag=0;
if (file_exists ("Libraries/database.txt")){
$data= file("Libraries/database.txt");
for ($line = 0; $line < count($data); ++$line){
	$userData=explode(" ",$data[$line]);
	if ($userData[3]=="1"){
		$flag=1;
		$userLogged=$userData[0];
		$val=strval(intval($userData[4]));
		$simNum=trim(preg_replace('/\s\s+/', ' ', $val));
	}
}
}
if ($flag==1){
$userID = $userLogged . $simNum;
$data = new DOMDocument;
$data->formatOutput = true;
$dom=$data->createElement("Muscle_Initialisation");
// $xml = simplexml_load_file($userLogged . "/" . $userID . ".xml");

if(file_exists('Libraries/ModelLibrary_metadata_muscles.xml')){ #Load XML file
	$ModelLibrary = simplexml_load_file ('Libraries/ModelLibrary_metadata_muscles.xml');
}
else {
	?>
	<p>There is no metadata file yet created for the muscle models. The muscles and their models will NOT be added to the initialisation file.</p>
	<form action="topology.php" method="post">
	<input type="hidden" name="neuron" value=<?php echo $_POST['neuron']; ?>>
	<input type="hidden" value=<?php echo $_POST['muscle']; ?> name="muscle">

	<?php
	for ($number = 1; $number < $_POST['neuron']+1; ++$number){
	?>
		<input type="hidden" name=<?php echo "name".$number?> value=<?php echo $_POST['name'.$number]; ?>>
	<?php
	}
	for ($number = 1; $number < $_POST['muscle']+1; ++$number){
	?>
		<input type="hidden" name=<?php echo "musclename".$number?> value=<?php echo $_POST['musclename'.$number]; ?>>
	<?php
	}
	?>
	<input type="submit" value="Create topology">
	</form><br><br>
	<?php
 	exit ('Could not load the file...');

}

if ($_POST['musclesamemodel']=='yes'){
	for ($number = 1; $number < $_POST['muscle']+1; ++$number){
		$packet=$data->createElement("packet");
		$destdev=$data->createElement("destdevice",$_POST['musclename'.$number]+303);
		$packet->appendChild($destdev);
		$sourcedev=$data->createElement("sourcedevice",65532);
		$packet->appendChild($sourcedev);
		$command=$data->createElement("command",20);
		$packet->appendChild($command);
		$timestamp=$data->createElement("timestamp",0);
		$packet->appendChild($timestamp);
		$modelid=$data->createElement("modelid",$_POST['model']);
		$packet->appendChild($modelid);
		
		foreach ($ModelLibrary->muscle as $model){
		if ($model->muscleid==$_POST['model']){
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
	$data->appendChild($dom);
	$filename=$userLogged . "/Muscle_Ini_file_" . $userID . ".xml";
	$data->save($filename);
}
else{
/* 	for ($number = 1; $number < $_POST['muscle']+1; ++$number){
		$packet=$data->createElement("packet");
		$destdev=$data->createElement("destdevice",$_POST['name'.$number]+1);
		$packet->appendChild($destdev);
		$sourcedev=$data->createElement("sourcedevice",65532);
		$packet->appendChild($sourcedev);
		$command=$data->createElement("command",20);
		$packet->appendChild($command);
		$timestamp=$data->createElement("timestamp",0);
		$packet->appendChild($timestamp);
		$modelid=$data->createElement("modelid",$_POST['model' . $number]);
		$packet->appendChild($modelid);

		foreach ($ModelLibrary->muscle as $model){
		if ($model->muscleid==$_POST['model' . $number]){
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
				$itemvalue=$data->createElement("itemvalue",$_POST["muscle" . $number . "item" . $modelitem->itemid]);
				$packet->appendChild($itemvalue);
				// $packet->appendChild($item);
			}
		}
		}
		$dom->appendChild($packet);
	}
	$data->appendChild($dom);
	$filename=$userLogged . "/Muscle_Ini_file_" . $userID . ".xml";
	$data->save($filename);	 */
}
	echo "Muscle initialisation data has been saved as ", "Muscle_Ini_file_" . $userID . ".xml";
	?>
	<br><br>
	<form action="topology.php" method="post">
	<input type="hidden" name="neuron" value=<?php echo $_POST['neuron']; ?>>
	<input type="hidden" name="muscle" value=<?php echo $_POST['muscle']; ?>>

	<?php
	for ($number = 1; $number < $_POST['neuron']+1; ++$number){
	?>
		<input type="hidden" name=<?php echo "name".$number?> value=<?php echo $_POST['name'.$number]; ?>>
	<?php
	}
	for ($number = 1; $number < $_POST['muscle']+1; ++$number){
	?>
		<input type="hidden" name=<?php echo "musclename".$number?> value=<?php echo $_POST['musclename'.$number]; ?>>
	<?php
	}
	?>
	<input type="submit" value="Create topology">
	</form><br><br>
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