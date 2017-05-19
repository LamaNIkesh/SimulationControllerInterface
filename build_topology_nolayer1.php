
<?php
include("head.html")
?>

<div class = "container">
	<div class="col-sm-12">
		<?php
		if ($_SESSION['flag']==1){

			$userLogged = $_SESSION['username'];
	//increases the simNum for the file name for multiple file 
			$_SESSION['simNum']++;
			$simNum =$_SESSION['simNum'];
//Create new document
			$dom = new DOMDocument('1.0', 'UTF-8');
			//$dom->formatOutput = true;

			if(isset($_POST['submit']))
			{	
				try {
					if(!empty($_POST["no_of_neurons"]) and !empty($_POST["simtime"]) and !empty($_POST["watchdog"]))
					{
						?>
						<p>Fields submitted successfully</p>		
						<?php
						$destdevice = 0;
						$sourcedevice=65532;
						$command=14;
						$timestamp = 0;
						$timestepsize = $_POST['simunits'];
						$simtime = $_POST['simtime'];
						$userID = $userLogged ."_".$simNum;
						$watchdog = $_POST['watchdog']; 
						//$watchdog = 2;
						if ($timestepsize=='us'){
							$cycles=1;
							$cyclesNum=$simtime*$cycles;
						}
						if ($timestepsize=='ms'){
							$cycles=1000;
							$cyclesNum=$simtime*$cycles;
						}
						if ($timestepsize=='s'){
							$cycles=1000000;
							$cyclesNum=$simtime*$cycles;
						}
		//Create the xml tag input where every other tags goes into
		//$input = $dom->createElement("input");
	//Create input tag and place value gotten from form into it
						$input1 = $dom->createElement("Sim_Meta");
						$input = $dom->createElement("packet");
						$a=$dom->createElement("destdevice", $destdevice);
						$input->appendChild($a);
						$b=$dom->createElement("timestamp", $sourcedevice);
						$input->appendChild($b);
						$c=$dom->createElement("command", $command);
						$input->appendChild($c);
						$d=$dom->createElement("timestamp", $timestamp);
						$input->appendChild($d);
						$e=$dom->createElement("timestepsize", $cycles);
						$input->appendChild($e);
						$f=$dom->createElement("cyclesNum", $cyclesNum);
						$input->appendChild($f);
						$g=$dom->createElement("simID", $simNum);
						$input->appendChild($g);
						$h=$dom->createElement("watchdogPeriod", $watchdog);
						$input->appendChild($h);
						$i=$dom->createElement("neuronsnum", $_POST['no_of_neurons']);
						$input->appendChild($i);
						//Not using muscle, only for c elegans
						//$j=$dom->createElement("musclesnum", $_POST['muscle']);
						//$input->appendChild($j);
						$input1->appendChild($input);
						$dom->appendChild($input1);

			//Save generated xml file as build_input.xml
						$filename="SimulationXML/".$userLogged . "/Sim_Ini_file_" . $userID . ".xml";
						//echo $filename;
						$dom->save($filename);	

						echo "A metadata initialisation file has been generated and saved as ", "Sim_Ini_file_" . $userID . ".xml in your folder SimulationXML/".$userLogged;
						

					} 
				}
				catch (Exception $e) {
					echo "error: ".$e->getMessage();

				}
				?>

				<form action="select_neuron.php" method="POST">
					<br>
					<input type="hidden" value=<?php echo $_POST['no_of_neurons']; ?> name="neuron_num">
					<input type = "hidden" value=<?php echo $_POST['no_of_diff_neurons'];?> name = "no_of_diff_neurons">
					<!--input type="hidden" value=<?php echo $_POST['muscle']; ?> name="muscle">-->
					<input type="hidden" value=<?php echo $_POST['samemodel']; ?> name="samemodel">
					<!--
					<input type="hidden" value=<?php echo $_POST['no_of_diff_neurons']; ?> name="no_of_diff_neurons">-->
					<!--
					<input type="hidden" value=<?php echo $_POST['musclesamemodel']; ?> name="musclesamemodel">-->
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
		
		?>
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

</div></div>
<?php
include("end_page.html")
?>