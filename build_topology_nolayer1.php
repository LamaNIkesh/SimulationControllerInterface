
<?php
include("head.html")
?>

<div class = "container">
	<div class="col-sm-12">
		<h6><font color = "#52a25e">System Builder->Simulation Parameters-><b>Saving Simulation Parameters</b></h6></font>
		<?php
		if ($_SESSION['flag']==1){

			$userLogged = $_SESSION['username'];
			/*
			---------------------------------------------------------------------------------------------------------------------------
			This section to assign simulation number to this particular simulation
			Reading database for simulation id, assigning unused simid to the user and update the database
			*/

			//database connection params
			$server = 'localhost';
			$user = 'root';
			$pass = '';
			$db = 'WebInterface';

			try{
				$connection = mysqli_connect("$server",$user,$pass,$db);
				$result = mysqli_query($connection, "select * from SImulation");

				while($simulation = mysqli_fetch_assoc($result)){
					#echo $simulation['id'];
					#echo $simulation['SimulationId'];
					#echo $simulation['Engage'];
					#Checking if a particular simualtion number is free to use
					#after being assigned the sim number engage field is set to 1, so that it cannot be reassigned to another simulation
					#until it is free again
					if($simulation['Engage'] == 0){
						$simNum = $simulation['SimulationId'];
						#$simulation['Engage'] = 1;
						break;
					}

				}
			}
			catch (Exception $e) {
					echo "error: ".$e->getMessage();
			}
			//------------------------------------------------------------------------------------------------------	

			#$simNum = 1;
			echo "\nsimulation ID assigned:".$simNum;

			//Create new document
			$dom = new DOMDocument('1.0', 'UTF-8');
			//$dom->formatOutput = true;

			if(isset($_POST['submit']))
			{	
				try {
					if(!empty($_POST["totalNeurons"]) and !empty($_POST["simtime"]) and !empty($_POST["watchdog"]))
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
						$userID = $userLogged .'_'.$simNum;
						$watchdog = $_POST['watchdog']; 
						//$watchdog = 2;
					
						if ($timestepsize=='ms'){
							$cycles=1;
							$cyclesNum=$simtime*$cycles;
						}
						if ($timestepsize=='s'){
							$cycles=1000;
							$cyclesNum=$simtime*$cycles;
						}
		//Create the xml tag input where every other tags goes into
		//$input = $dom->createElement("input");
	//Create input tag and place value gotten from form into it

						

						$input1 = $dom->createElement("Sim_Meta");
						$input = $dom->createElement("packet");

						$destDevice=$dom->createElement("destdevice", $destdevice);
						$input->appendChild($destDevice);
						$sourceDevice=$dom->createElement("sourcedevice", $sourcedevice);
						$input->appendChild($sourceDevice);

						$simID=$dom->createElement("simID", $simNum);
						$input->appendChild($simID);
						
						$command=$dom->createElement("command", $command);
						$input->appendChild($command);
						$tmsp=$dom->createElement("timestamp", $timestamp);
						$input->appendChild($tmsp);
						$tmspSize=$dom->createElement("timestepsize", $cycles);
						$input->appendChild($tmspSize);
						$cycle=$dom->createElement("cyclesNum", $cyclesNum);
						$input->appendChild($cycle);
						$watchdog=$dom->createElement("timeout", $watchdog);
						$input->appendChild($watchdog);

						$i=$dom->createElement("neuronsnum", $_POST['totalNeurons']);
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
					<input type="hidden" value=<?php echo $_POST['totalNeurons']; ?> name="totalNeurons">
					<input type = "hidden" value=<?php echo $_POST['totalDiffModelNeurons'];?> name = "totalDiffModelNeurons">
					<input type="hidden" value=<?php echo $simNum; ?> name="simNum">
					<input type="hidden" value=<?php echo $_POST['samemodel']; ?> name="samemodel">
					<!--
					<input type="hidden" value=<?php echo $_POST['totalDiffModelNeurons']; ?> name="totalDiffModelNeurons">-->
						
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
