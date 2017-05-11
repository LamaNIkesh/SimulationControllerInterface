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
	$list=file("Libraries/neuron_id.txt");
	$neurons=$_POST['neuron'];
	$filename=$userLogged . "/Neuron_Ini_file_" . $userID . ".xml";
	$xmlDoc1 = new DOMDocument();
	$xmlDoc1->load($filename);
	?><p>There are <?php echo $_POST['neuron']; ?> neurons that could receive stimulus.</p>
	<form action="save_stimulus.php" method="post">
	<input type="hidden" name="neuron" value=<?php echo $_POST['neuron']; ?>>
	<?php
	for ($number = 1; $number < $_POST['neuron']+1; ++$number){
		$id=$_POST['name'.$number];
		?>
		Neuron <?php echo $_POST['name'.$number]; ?> <br><br>
		<input type="hidden" name=<?php echo "name".$number?> value=<?php echo $_POST['name'.$number]; ?>>
		<input type="hidden" name=<?php echo "nameid".$number?> value=<?php echo $_POST['nameid'.$number]; ?>>
		<!-- <input type="checkbox" name= ?php echo 'stim0'.$number; ?> value="none" checked> None <br> -->
		<input type="checkbox" name=<?php echo 'stim1'.$number; ?> value=65535 > Chemotaxis: Lisine <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value1'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start1'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end1'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim2'.$number; ?> value=65534 > Chemotaxis: cAMP <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value2'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start2'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end2'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim3'.$number; ?> value=65533 > Chemotaxis: Biotin <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value3'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start3'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end3'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim4'.$number; ?> value=65532 > Chemotaxis: Na++ <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value4'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start4'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end4'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim5'.$number; ?> value=65531 > Chemotaxis: Cl- <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value5'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start5'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end5'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim6'.$number; ?> value=65530 > Chemotaxis: Heavy metals <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value6'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start6'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end6'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim7'.$number; ?> value=65529 > Chemotaxis: Copper <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value7'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start7'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end7'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim8'.$number; ?> value=65528 > Chemotaxis: Cadmium <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value8'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start8'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end8'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim9'.$number; ?> value=65527 > Chemotaxis: SDS - Sodium dodecyl sulfate <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value9'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start9'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end9'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim10'.$number; ?> value=65526 > Chemotaxis: Quinine <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value10'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start10'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end10'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim11'.$number; ?> value=65525 > Thermoception: Temperature <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value11'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start11'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end11'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim12'.$number; ?> value=65524 > Mechanoception: Force <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value12'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start12'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end12'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim13'.$number; ?> value=65523 > Electrosensation: Current <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value13'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start13'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end13'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim14'.$number; ?> value=65522 > Proprioception: Stretch <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value14'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start14'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end14'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim15'.$number; ?> value=65521 > Proprioception: Flexion <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value15'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start15'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end15'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim16'.$number; ?> value=65520 > Phototaxis: Wavelength <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value16'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start16'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end16'.$number; ?> value=0><br>
		<input type="checkbox" name=<?php echo 'stim17'.$number; ?> value=65519 > Phototaxis: Light Intensity <br>
		&nbsp; &nbsp; &nbsp; &nbsp; Value of the stimulus: <input type="number" name=<?php echo 'value17'.$number; ?> value=0.00>
		Beginning of the stimulus: <input type="number" name=<?php echo 'start17'.$number; ?> value=0>
		End of the stimulus: <input type="number" name=<?php echo 'end17'.$number; ?> value=0>
		<br><br>
		<?php
	}
	?>
	
	<br><input type="submit" value="Next">
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
