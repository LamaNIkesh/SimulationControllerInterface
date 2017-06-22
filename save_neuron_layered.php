<?php
include("head.html")
?>

<div class = "container">
	<div class="col-sm-12">
		<h6><font color = "#52a25e">System Builder->Simulation Parameters->Saving Simulation Parameters-><b>Neuron Model Parameters</b></h6></font>
		<?php
		if ($_SESSION['flag']==1){
if(file_exists('Libraries/ModelLibrary_metadata.xml')){ #Load XML file
	$ModelLibrary = simplexml_load_file ("Libraries/ModelLibrary_metadata.xml");
	//echo "test";
}
else {
	exit ('Could not load the file...');
}




//echo $_POST['samemodel'];
if ($_POST['samemodel']=='yes'){

	$LayerDetails = fopen("SimulationXML/".$userLogged."/LayerDetails.txt","w");

	//same model for each layer
	if ($_POST['model']==1){$modelname="Integrate and fire";}
	if ($_POST['model']==2){$modelname="Leaky integrate and fire";}
	if ($_POST['model']==3){$modelname="Izhikevich";}
	?><p>Please select model parameters for your network
	<br><br> The typical values for the <?php echo $modelname; ?> model are: </p>
	<form action="save_neuron_data_layered.php" method="post">
		
		<?php
		?>
		<input type ="hidden" name= "noOflayers" value=<?php echo $_POST['noOflayers']; ?> required>
		<input type ="hidden" name= "samemodel" value=<?php echo $_POST['samemodel']; ?> required>
		<input type ="hidden" name= "model" value=<?php echo $_POST['model']; ?> required>
		<input type = "hidden" name = "totalNeurons" value = <?php  echo $_POST['totalNeurons']; ?> require>
		<?php  
		for ($i=1; $i <= $_POST['noOflayers']; $i++) { 
			//passing no fo neurons each layer to the next file to be read
			fwrite($LayerDetails,$i);
			fwrite($LayerDetails," ".$_POST['totalNeuronsEachLayer'.$i]);
			fwrite($LayerDetails, " ".$_POST['model']);
			fwrite($LayerDetails,"\n");
			?>
			<input type ="hidden" name=<?php echo "totalNeuronsEachLayer".$i ?> value= $_POST['totalNeuronsEachLayer'.$i] required>
			<?php  
		}
		

		$index = 1;
		foreach ($ModelLibrary->neuron as $model)
		{
			if ($model->neuronid==$_POST['model']){
				foreach ($model->item as $item){
					$DataItem= str_replace("_", " ", $item->name);
					?>
					<div class="col-sm-4">
						<?php echo $index,")"; ?> <?php echo $DataItem; ?>:</div><div class="col-sm-8"><input type="number" name=<?php echo "item" . $item->itemid; ?> value=<?php echo $item->typicalvalue; ?> required></div><br><br>
<?php
$index++;
}
}
}
?>
<input type="submit" value="Next">
</form><br><br>
<?php

}
else
{
	//for differnet models for each layer

	$layermodel = fopen("SimulationXML/".$userLogged."/Layered/layerModel.txt","r");

	$LayerDetails = fopen("SimulationXML/".$userLogged."/Layered/LayerDetails.txt","w");

	$numberOfLayers = 0;
	$modelarray = array();
	$i = 0;
	//reads the layerModel.txt which has the model number for each layer
	while(! feof($layermodel))
	{	
		
		$gettingLine= fgets($layermodel);
	  	//to avoid any null values at the end
		if($gettingLine == NULL){break;}
		echo "gettingline ".$gettingLine;
		$numberOfLayers++;
		$modelarray[$i] = $gettingLine;
		$i++; 
	}
	/*echo "--";
	echo $numberOfLayers;
	echo "[0]:".$modelarray[0];
	echo "[1]:".$modelarray[1];
	echo "[2]:".$modelarray[2];*/
	
	
	for ($layernum=0; $layernum <$numberOfLayers ; $layernum++) { 
		# code...
		//check for each layer and displays user with parameter customisation fields
		//echo "model".$modelarray[$layernum];
		if ($modelarray[$layernum] == 1){$modelname="Integrate and fire";}
		if ($modelarray[$layernum] == 2){$modelname="Leaky integrate and fire";}
		if ($modelarray[$layernum] == 3){$modelname="Izhikevich";}
		?><p>Please select model parameters for your network. Each layer has a different model/parameters
		<!--<br><br> The typical values for the <?php echo $modelname; ?> model are: </p>-->
		<?php
		//writing layer number, no of neurons in each layer and model id for each layer  
		fwrite($LayerDetails,($layernum + 1));
		fwrite($LayerDetails," ".$_POST['totalNeuronsEachLayer'.($layernum+1)]);
		fwrite($LayerDetails, " ".$modelarray[$layernum]);
		//fwrite($LayerDetails, "\n");
		?>
		<form action="save_neuron_data_layered.php" method="post">

			<?php
			?>
			<input type ="hidden" name= "noOflayers" value=<?php echo $numberOfLayers; ?> required>
			<input type ="hidden" name= "samemodel" value=<?php echo $_POST['samemodel']; ?> required>
			<input type = "hidden" name = "totalNeurons" value = <?php  echo $_POST['totalNeurons']; ?> require>
			<!-- Passing model id for each layer-->
			<input type = "hidden" name = <?php echo "model".($layernum + 1); ?> value = <?php echo $modelarray[$layernum]; ?> required>
			<?php  
			//this for loop passes no fo neuron in each layer to the next page
			for ($i=1; $i <= $_POST['noOflayers']; $i++) { 
			//passing no fo neurons each layer to the next file to be read
				//writing the numbe of layer, number of neuron in each layer
				//and model id for each layer to a text file for easy access later	

			?>
			<input type ="hidden" name=<?php echo "totalNeuronsEachLayer".$i ?> value= <?php  echo $_POST['totalNeuronsEachLayer'.$i]; ?> required>
			
			<?php  
			}//end of for loop
			
			
			$index = 1;
			//reading model library to generate correct nueron model fields
			foreach ($ModelLibrary->neuron as $model){
				//echo gettype ($model->neuronid );
				//echo gettype ($modelarray[$layernum] );
				//echo "model:".$model->neuronid;
				//echo "modelarray:".$modelarray[$layernum];
				//changing string number to int number
				//for some reason it takes it as a string num
				if ($model->neuronid == intval($modelarray[$layernum])){ //echo "inside model";?>
				<br><fieldset>
				<legend>The typical values for the <?php echo $modelname; ?> model are: </legend>
				<?php
				foreach ($model->item as $item){
					$DataItem= str_replace("_", " ", $item->name);
					?>
					<div class="col-sm-4">
						<?php echo $DataItem; ?>:</div><div class = "col-sm-8"> <input type="number" name=<?php echo  "item" . $item->itemid; ?> value=<?php echo $item->typicalvalue; ?> required></div><br><br>
						<?php
					}
				}?></fieldset><?php
			}//end of for each

		}//end of looping through each layer

		?>

		<input type="submit" value="Next">
	</form><br><br><br>
	<?php
	fclose($LayerDetails);
}

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
