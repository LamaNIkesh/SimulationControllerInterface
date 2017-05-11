<?php
include("head.html")
?>
<?php
//Create new document
$dom = new DOMDocument("1.0");
$dom->formatOutput = true;

if(isset($_POST['submit']))
	{
	if(!empty($_POST["neuron"]) and !empty($_POST["simtime"]) and !empty($_POST["watchdog"]))
	{
?>
		<p>Fields submitted successfully</p>		
<?php
		//$timestamp = $_POST['timestamp'];
		$timestamp = 0;
		$neuron = $_POST['neuron'];
		$simtime = $_POST['simtime'];
		$watchdog = $_POST['watchdog'];
		//Create the xml tag input where every other tags goes into
		$input = $dom->createElement("input");
	//Create input tag and place value gotten from form into it
	$timestamp = $dom->createElement("timestamp", $timestamp);
	$input->appendChild($timestamp); 

	//Create neuron_number tag and place value gotten from form into it
	$neuron = $dom->createElement("neuron", $neuron);
	$input->appendChild($neuron);

	//Create simtime tag and place value gotten from form into it
	$simtime = $dom->createElement("simtime", $simtime);
	$input->appendChild($simtime); 

	//Create watchdog tag and place value gotten from form into it
	$watchdog = $dom->createElement("watchdog", $watchdog);
	$input->appendChild($watchdog);

	//close input tag
	$dom->appendChild($input);
	
	
	//Save generated xml file as build_input.xml
	$dom->save("build_input.xml");

	echo "A file has been generated and saved as build_input.xml";
	?>
		<form action="select_neuron.php" method="POST">
		<br>
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
	}
?>
<br><br>
<?php
include("end_page.html")
?>