<?php
include("head.html")
?>
<script src = "js/stimDrop.js" type = "text/javascript"></script>
<link rel="stylesheet" href="css/stimDrop.css">

<?php 
?>

<div class = "container">
		<div class="col-sm-12">
			<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->
										NeuronModelParameter->Creating Initialisation File->
										Create Topology->Topology Viewer->Save Topology-><b>Add Stimulus</b></h6></font>
			<?php
			//saves the topology information into a topology initialisation file
			echo "total neurons: ".$_POST['totalNeurons'];
			if ($_SESSION['flag']==1){

				$simNum = $_POST['simNum'];

				echo "topology ".$_POST['topology'];

				if($_POST['topology'] == 'nonlayered'){	//for non layered network.
														//any neurons can get input stimulus
					$neuronNum = $_POST['noOfNeurons'];
				}
				else{
					$neuronNum = $_POST['inputlayer']; //This gives out number of neurons in the input layer 
														//for layered network
					
					echo "There are ".$neuronNum." neurons in the input layer";

				}

				?><p>There are <?php echo $neuronNum; ?> neurons that could receive stimulus.</p>
				<p>Please select the neurons that you would like to provide stimulus into</p>
				<?php 
				// $filename="SimulationXML/".$userLogged . "/Neuron_Ini_file_" . $userID . ".xml";
				// $xmlDoc1 = new DOMDocument();
				// $xmlDoc1->load($filename);
				?>
				<form action="select_stimulus.php" method="get">
				  <input type = "hidden" name = "noOfNeurons" id = "noOfNeurons" value = <?php echo $neuronNum; ?> >
				  <input type="hidden" name='topology' id = 'topology' value=<?php echo $_POST['topology']; ?>>
				  <input type = "hidden" name = "totalNeurons" id ="totalNeurons" value = <?php echo $_POST['totalNeurons']; ?>>
				  <input type="hidden" value=<?php echo $simNum; ?> name="simNum">
				  <div class="multiselect">
				    <div class="selectBox" onclick="showCheckboxes()">
				      <select>
				        <option>Select Neurons</option>
				      </select>
				      <div class="overSelect"></div>
				    </div>
				    <div id="checkboxes">
				    	<?php 
				    	try{
				    		for($i = 1; $i<=$neuronNum; $i++){
				    			?>
				    			<label for=<?php echo $i; ?>>
				    			<?php 
				    			
				        			echo '<input type="checkbox" name = "StimNeurons[]" id="StimNuerons" value = '.$i.' size = 10 required> Neuron'.$i.'</label>' ;
				        		}
				        	}	
				        catch(Exception $e){
				        	echo 'No Stimulation selected!!!!';
				        	}
				        
				       	?>
				  </div>
				  </div>		
				<br>		
				<hr>	
				<h5>Please proceed to next page to add stimulation values for the selected neurons</h5>	
				<input type="submit" value="Next">
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
