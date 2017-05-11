<?php
include("head.html")
?>
<?php
//Create new document
$dom = new DOMDocument("1.0");

$dom->formatOutput = true;

//header("Content-Type: text/plain");

if(isset($_POST['submit']))
{
	if(!empty($_POST["itemvalue"]))
	{
	//Get the values from the form
	$neuronid = $_POST['neuronid'];
	$sourcedevice = $_POST['sourcedevice'];
	$command = $_POST['command'];
	$timestamp = $_POST['timestamp'];
	$modelid = $_POST['modelid'];

	$num = count($_POST['itemvalue']);

	$itemvalue = $_POST['itemvalue'];
	$itemid = $_POST['itemid'];
	$type = $_POST['type'];
	$datatype = $_POST['datatype'];
	$integerpart = $_POST['integerpart'];
	$inlsb = $_POST['inlsb'];
	$inmsb = $_POST['inmsb'];
	$outlsb = $_POST['outlsb'];
	$outmsb = $_POST['outmsb'];

	//Create the xml tag packet where every other tags goes into
	$packet = $dom->createElement("packet");

	//Create destdevice tag and place value gotten from form into it
	$destdevice = $dom->createElement("destdevice", $neuronid);
	$packet->appendChild($destdevice); 

	//Create sourcedevice tag and place value gotten from form into it
	$sourcedevice = $dom->createElement("sourcedevice", $sourcedevice);
	$packet->appendChild($sourcedevice);

	//Create command tag and place value gotten from form into it
	$command = $dom->createElement("command", $command);
	$packet->appendChild($command);

	//Create timestamp tag and place values gotten from form into it
	$timestamp = $dom->createElement("timestamp", $timestamp);
	$packet->appendChild($timestamp);

	//Create modelid tag and place values gotten from form into it
	$modelid = $dom->createElement("modelid", $modelid);
	$packet->appendChild($modelid);
	
	//Create item properties tags and place values gotten from the form into them for each of the the item tags
	for($i=0; $i<$num; $i++) 
	{
		$item = $dom->createElement("item");

		$id = $dom->createElement("itemid", $itemid[$i]);
		$item->appendChild($id);
	
		$itemtype = $dom->createElement("type", $type[$i]);
		$item->appendChild($itemtype);

		$itemdatatype = $dom->createElement("datatype", $datatype[$i]);
		$item->appendChild($itemdatatype);

		$itemintegerpart = $dom->createElement("integerpart", $integerpart[$i]);
		$item->appendChild($itemintegerpart);

		$iteminlsb = $dom->createElement("inlsb", $inlsb[$i]);
		$item->appendChild($iteminlsb);

		$iteminmsb = $dom->createElement("inmsb", $inmsb[$i]);
		$item->appendChild($iteminmsb);

		$itemoutlsb = $dom->createElement("outlsb", $outlsb[$i]);
		$item->appendChild($itemoutlsb);

		$itemoutmsb = $dom->createElement("outmsb", $outmsb[$i]);
		$item->appendChild($itemoutmsb);

		$value = $dom->createElement("itemvalue", $itemvalue[$i]);
		$item->appendChild($value);

		
		$packet->appendChild($item);
	}
		//close packet tag
		$dom->appendChild($packet);


#echo $dom->saveXML();
$dom->save("values.xml");
echo "A file has been generated and saved as values.xml";

	}
//Redirect back to the form if no values are entered
else
	{
		header("Location:input_parameters.php");		
		echo "Please enter value";
		exit;
	}
}
?>
<br><br>
<?php
include("end_page.html")
?>
