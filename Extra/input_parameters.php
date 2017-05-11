<?php
include("head.html")
?>
<?php 

#Check if XML file exists and load file
if(file_exists('Libraries/ModelLibrary_metadata.xml'))
{#Load XML file
	$xml = simplexml_load_file ("Libraries/ModelLibrary_metadata.xml");
}
else {
 	exit ('Could not load the file...');
}
?>
<h2> Input details </h2>

<form action="get_parameters.php" method="POST">

<!--Get destination device value which is the neuron id from the metadata file-->
<?php 
foreach ($xml->neuron as $neuron)
{
	"Destination device: ";?> <input type="hidden" name="neuronid" value="<?php echo $neuron->neuronid?>">
	<?php $neuron->neuronid . "<br>";
}
?>

<!--source device value-->
<input type="hidden" name="sourcedevice" value="65532">

<!--command value which is fixed (24)-->
<input type="hidden" name="command" value="24">

<!--timestamp value which is fixed (0)-->
<input type="hidden" name="timestamp" value="0">

<!--Get model id value from the metadata file-->
<?php 
foreach ($xml->neuron as $neuron)
{
	"Model ID: ";?> <input type="hidden" name="modelid" value="<?php echo $neuron->modelid?>">
	<?php $neuron->modelid . "<br>";
}
?>

<br>

<!--Get item property values from the metadata file-->
<?php
foreach($xml->neuron->item as $item)
{
	"ID: ";?> <input type="hidden" name="itemid[]" value="<?php echo $item->itemid?>">
	<?php $item->itemid . "<br>";

	 "Type: ";?> <input type="hidden" name="type[]" value="<?php echo $item->type?>">
	<?php $item->type . "<br>";

	"Datatype: ";?> <input type="hidden" name="datatype[]" value="<?php echo $item->datatype?>">
	<?php $item->datatype . "<br>";

	"Integer part: ";?> <input type="hidden" name="integerpart[]" value="<?php echo $item->integerpart?>">
	<?php $item->integerpart . "<br>";

	"Inlsb: ";?> <input type="hidden" name="inlsb[]" value="<?php echo $item->inlsb?>">
	<?php $item->inlsb . "<br>";

	"Inmsb: ";?> <input type="hidden" name="inmsb[]" value="<?php echo $item->inmsb?>">
	<?php $item->inmsb . "<br>";

	"Outlsb: ";?> <input type="hidden" name="outlsb[]" value="<?php echo $item->outlsb?>">
	<?php $item->outlsb . "<br>";

	"Outmsb: ";?> <input type="hidden" name="outmsb[]" value="<?php echo $item->outmsb?>">
	<?php $item->outmsb . "<br>"; ?>

<!--User input-->
	<?php echo $item->name . ": ";?> <input name="itemvalue[]"/ required>
<br>
<?php
echo "<br>";
}
?>
<br>
<input type="submit" name="submit" value="Submit" />
</form>
<br><br>
<?php
include("end_page.html")
?>