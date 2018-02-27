<?php
include("head.html")
?>
<script src = "js/stimDrop.js" type = "text/javascript"></script>
<link rel="stylesheet" href="css/stimDrop.css">

<div class = "container">
		<div class="col-sm-12">
			<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->
										NeuronModelParameter->Creating Initialisation File->
										Create Topology->Topology Viewer->Save Topology-><b>Add Stimulus</b></h6></font>
			<h3>Please select stimulation parameters and timing of the stimulation for the selected neurons</h3>
			<h4>All the stimulation are constant electrical current</h4>
			<br>
			<?php
			//saves the topology information into a topology initialisation file

			if ($_SESSION['flag']==1){
					
					if($_GET['StimNeurons']){
					$simNum = $_GET['simNum'];
					$name = $_GET['StimNeurons'];
					//echo "stim neurons: ".$name;
					$i =1;
					echo "the number of neurons: ",$_GET['totalNeurons'];
	
				?>
				<form action="save_stimulus.php" method="post">

				<input type="hidden" name='topology' id = 'topology' value=<?php echo $_GET['topology']; ?>>
				<?php
				foreach ($name as $StimNeurons) {
					# code...
					
					echo "<b>Stimulation for Neuron ".$StimNeurons."</b><br><br>";
					
					?>
					<!---nameid.$i is passed to the save_stimulus.php where the information is written into an xml file-->
					<input type = "hidden" name = <?php echo "nameid".$i; ?> id = <?php echo "nameid".$i; ?> value = <?php echo $StimNeurons; ?> >
					Value of the stimulus(mA): <input type="number" name=<?php echo 'value'.$i; ?> value=0.00>
					&nbsp; &nbsp;Beginning of the stimulus(ms): <input type="number" name=<?php echo 'start'.$i; ?> value=0>
					&nbsp; &nbsp;End of the stimulus(ms): <input type="number" name=<?php echo 'end'.$i; ?> value=0><br><hr>

					<?php 
					
					$i++;
				}
			}
			else{
				echo "No neurons selected for stimulation!!!";
			}

				?>
				<!-- The number of stimulus receiving neurons are passed to the next page to form stimulation xml file -->
				<input type = "hidden" name = "stimNeurons" id = "stimNeurons" value = <?php echo $i; ?> >
				<input type = "hidden" name = "totalNeurons" id ="totalNeurons" value = <?php echo $_GET['totalNeurons'];?>>
				<input type="hidden" value=<?php echo $simNum; ?> name="simNum">

				<?php 
				//$name = $_GET['']

				/*for ($i=1; $i <=$_POST['noOfNeurons'] ; $i++) { 
					# code...
					echo "stimulation receiving neurons: ".$_POST[$i];
				}*/
			?>
	
	<input type="submit" value="Next">
	<br><br>
	</form><br><br>
	<?php	
	
}
else
{
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
