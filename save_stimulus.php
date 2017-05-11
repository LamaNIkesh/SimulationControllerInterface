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
		$userID = $userLogged . $simNum;
	}
}
}
if ($flag==1){
	$userID = $userLogged . $simNum;
	$data = new DOMDocument;
	$data->formatOutput = true;
	$dom=$data->createElement("Preconfigured_stimulus");
	// $xml = simplexml_load_file($userLogged . "/" . $userID . ".xml");
	for ($number = 1; $number < $_POST['neuron']+1; ++$number){
		for ($stim = 1; $stim < 17+1; ++$stim){
			if(isset($_POST['stim'.$stim.$number])){
				$packet=$data->createElement("packet");
				$destdev=$data->createElement("destdevice",$_POST['nameid'.$number]+1); // Needs to specify the destination; is the neuron??
				$packet->appendChild($destdev);
				$sourcedev=$data->createElement("sourcedevice",65532); // Needs to specify the source; is the NC??
				$packet->appendChild($sourcedev);
				$command=$data->createElement("command",19);
				$packet->appendChild($command);
				$timestamp=$data->createElement("timestamp",$_POST['start'.$stim.$number]);
				$packet->appendChild($timestamp);
				$endtimestamp=$data->createElement("endtimestamp",$_POST['end'.$stim.$number]);
				$packet->appendChild($endtimestamp);
				$itemID=$data->createElement("itemID",$_POST['stim'.$stim.$number]); // How to specify the stim? 1-17? or sth else?
				$packet->appendChild($itemID);
				$itemValue=$data->createElement("itemValue",$_POST['value'.$stim.$number]);
				$packet->appendChild($itemValue);
				
				$dom->appendChild($packet);
			}
		}
	}
	$data->appendChild($dom);
	$filename=$userLogged . "/Stim_Ini_file_" . $userID . ".xml";
	$data->save($filename);	
	echo "Preconfigured stimulus data has been saved as ", "Stim_Ini_file_" . $userID . ".xml";
	?>
	</form><br>
	<form action="initialisation_file.php" method="post">
	<br><input type="submit" value="Create initialisation file">
	</form><br>
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
