<?php
include("head.html")
?>

<div class = "container">
	<div class="col-sm-12">

		<?php
		if ($_SESSION['flag']==1){
			$simNum = 1;
			$userID = $userLogged . $simNum;
			$data = new DOMDocument;
			$data->formatOutput = true;
			$dom=$data->createElement("Topology_Initialisation");
// $xml = simplexml_load_file($userLogged . "/" . $userID . ".xml");

			for ($number = 1; $number < $_POST['neuron']+1; ++$number){
				$packet=$data->createElement("packet");
				$destdev=$data->createElement("destdevice",$_POST['nameid'.$number]+1);
				$packet->appendChild($destdev);
				$sourcedev=$data->createElement("sourcedevice",65532);
				$packet->appendChild($sourcedev);
				$command=$data->createElement("command",11);
				$packet->appendChild($command);
				$timestamp=$data->createElement("timestamp",0);
				$packet->appendChild($timestamp);
				
				for ($connect = 1; $connect < $_POST['neuron']+1; ++$connect){
					if (isset($_POST["neuron" . $number . "synapse" . $connect]) && $_POST["neuron" . $number . "synapse" . $connect]=="on"){
						$itemid=$data->createElement("preneuronid",$_POST['nameid'.$connect]+1);
						$packet->appendChild($itemid);
					}
				}
				$dom->appendChild($packet);
			}
			for ($number = 1; $number < $_POST['muscle']+1; ++$number){
				$packet=$data->createElement("packet");
				$destdev=$data->createElement("destdevice",$_POST['musclenameid'.$number]+303);
				$packet->appendChild($destdev);
				$sourcedev=$data->createElement("sourcedevice",65532);
				$packet->appendChild($sourcedev);
				$command=$data->createElement("command",11);
				$packet->appendChild($command);
				$timestamp=$data->createElement("timestamp",0);
				$packet->appendChild($timestamp);
				for ($connect = 1; $connect < $_POST['neuron']+1; ++$connect){
					if (isset($_POST["muscle" . $number . "synapse" . $connect]) && $_POST["muscle" . $number . "synapse" . $connect]=="on"){
						$itemid=$data->createElement("preneuronid",$_POST['nameid'.$connect]+1);
						$packet->appendChild($itemid);
					}
				}
				$dom->appendChild($packet);
			}
			
			$data->appendChild($dom);
			$filename="SimulationXML/".$userLogged . "/Topo_Ini_file_" . $userID . ".xml";
			$data->save($filename);

			echo "Topology initialisation data has been saved as ", "Topo_Ini_file_" . $userID . ".xml";
			?>
			<p>Other initialisation files could be added before sending the data, such as muscle and stimulation. These features would be eventually added.</p>
			<p> In the case of adding other initialisation files, these buttons will send the user to the adequate page. This procedure might change. </p>
			<form action="select_stimulus.php" method="post">
				<br><input type="submit" value="Add stimulus initialisation data">
				<input type="hidden" name="neuron" id = "neuron" value=<?php echo $_POST['neuron']; ?>>
				<?php
				for ($number = 1; $number < $_POST['neuron']+1; ++$number){
					?>
					<input type="hidden" name=<?php echo "name".$number?> value=<?php echo $_POST['name'.$number]; ?>>
					<input type="hidden" name=<?php echo "nameid".$number?> value=<?php echo $_POST['nameid'.$number]; ?>>
					<?php
				}
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
	</div></div>

	<?php
	include("end_page.html")
	?>