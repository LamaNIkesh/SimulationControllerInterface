<?php
include("head.html")
?>


<div class = "container">
	<div class="col-sm-12">

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

if ($_POST['samemodel']=='yes' and $_POST['no_of_diff_neurons']==0){
	for ($number = 1; $number < $_POST['neuron']+1; ++$number){
		$packet=$data->createElement("packet");
		//$destdev=$data->createElement("destdevice",$_POST['name'.$number]+1);
		$destdev=$data->createElement("destdevice", 1);//temporary
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
			if ($model	neuronid==$_POST['model']){
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
	$filename="SimulationXML/".$userLogged . "/Neuron_Ini_file_" . $userID . ".xml";
	$data->save($filename);
}
{
 	//echo $_POST['name'.$number];
	for ($number = 1; $number < $_POST['neuron']+1; ++$number){
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
		$modelid=$data->createElement("modelid",$_POST['model' . $number]);
		$packet->appendChild($modelid);

		foreach ($ModelLibrary->neuron as $model){
			if ($model->neuronid==$_POST['model' . $number]){
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
					$itemvalue=$data->createElement("itemvalue",$_POST["neuron" . $number . "item" . $modelitem->itemid]);
					$packet->appendChild($itemvalue);
				// $packet->appendChild($item);
				}
			}
		}
		$dom->appendChild($packet);
	}
	$data->appendChild($dom);
	$filename="SimulationXML/".$userLogged . "/Neuron_Ini_file_" . $userID . ".xml";
	$data->save($filename);	
}
echo "Neuronal initialisation data has been saved as ", "Neuron_Ini_file_" . $userID . ".xml";




?>
<br>
<form action="topology.php" method="post">
	<input type="hidden" name="neuron" value=<?php echo $_POST['neuron']; ?>>
		<!--
		<?php
		for ($number = 1; $number < $_POST['neuron']+1; ++$number){
			?>
			<input type="hidden" name=<?php echo "name".$number?> value=<?php echo $_POST['name'.$number]; ?>>
			<?php
		}
		?>
	-->
	<br>
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

</div>
</div>

<?php
include("end_page.html")
?>