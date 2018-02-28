<?php
//head included
include("head.html")
?>

<?php 
	
	//function to read database and return the list of neuron models present
	function queryDatabase($arrayForModelPara){
	$server = 'localhost';
  	$user = 'root';
  	$pass = '';
  	$db = 'WebInterface';

  	try{
  		//create connection
  	$connection = mysqli_connect("$server",$user,$pass,$db);
  	//$_POST['model'] is the selected model from the previous page
  	//since the table is named with the same model we can select table with the model name
  	$result = mysqli_query($connection, "select * from ".$_POST['model']."") 
			or die("No model found!!!!".mysql_error());
	$loopCounter = 0;
	$noOfFields
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_assoc($result)){

			echo "Model ID: ".$row['ModelID']."---Model Name: ".$row['ModelName']." "."<br>";
			$arrayForModelName[][] = $row['ModelName'];
			echo count($arrayForModelName);
		}
	}
	return $arrayForModelName;
	mysqli_close($connection);
  	}

  	catch(Exception $e){
  		echo "Cannot establish connection !!";
  	}

}
$arrayForModelPara = array(array());
$arrayForModelPara = queryDatabase($arrayForModelPara);

 ?>


<div class = "container">
	<div class="col-sm-12">
		<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels-><b>NeuronModelParameter</b></h6></font>
		<?php
		if ($_SESSION['flag']==1){
			$simNum = $_POST['simNum'];
if(file_exists('Libraries/ModelLibrary_metadata.xml')){ #Load XML file
	$ModelLibrary = simplexml_load_file ("Libraries/ModelLibrary_metadata.xml");
	//echo "test";
}
else {
	exit ('Could not load the file...');
}
//echo $_POST['samemodel'];
if ($_POST['samemodel']=='yes' and $_POST['totalDiffModelNeurons'] == 0){
	/*if ($_POST['model']==1){$modelname="Integrate and fire";}
	if ($_POST['model']==2){$modelname="Leaky integrate and fire";}
	if ($_POST['model']==3){$modelname="Izhikevich";}*/
	?><p>There are <?php echo $_POST['totalNeurons']; ?> neurons to be processed with the same model.
	<br><br> The typical values for the <?php echo $_POST['model']; ?> model are: </p>
	<form action="save_neuron_data.php" method="post">
		
		<?php
		for ($number = 1; $number < $_POST['totalNeurons']+1; ++$number){
			?>
			<input type="hidden" name=<?php echo "neuron".$number?> value=<?php echo $_POST['neuron'.$number]; ?>>
			<?php

		}
		?>

		<input type="hidden" name="model" value=<?php echo $_POST['model']; ?>>
		<!--keeping the to neuron for the next file save_neuron_data-->
		<input type="hidden" name="totalNeurons" value=<?php echo $_POST['totalNeurons']; ?>>
		<input type="hidden" name="totalDiffModelNeurons" value=<?php echo $_POST['totalDiffModelNeurons']; ?>>
		<input type="hidden" value=<?php echo $_POST['samemodel']; ?> name="samemodel">
		<input type="hidden" value=<?php echo $simNum; ?> name="simNum">

		<?php
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
else{
	//deals with different models with combination of same models too

	if($_POST['totalNeurons']>$_POST['totalDiffModelNeurons']){
		//echo "nxt stage";
		$subtractedSameModel= $_POST['totalNeurons'] - $_POST['totalDiffModelNeurons'];
		if ($_POST['model']==1){$modelname="Integrate and fire";}
		if ($_POST['model']==2){$modelname="Leaky integrate and fire";}
		if ($_POST['model']==3){$modelname="Izhikevich";}
		?><p>There are <?php echo $subtractedSameModel; ?> neurons to be processed with the same model.
		<br><br> <legend>The typical values for the <?php echo $modelname; ?> model are: </legend></p>
		<form action="save_neuron_data.php" method="post">

			<?php
			for ($loopCounter = 1; $loopCounter < $subtractedSameModel+1; $loopCounter++){
				?>
				<input type="hidden" name=<?php echo "neuron".$loopCounter?> value=<?php echo $_POST['neuron'.$loopCounter]; ?>>
				<?php
				//echo "name ", $_POST['name'.$number];

			}
			?>

			<input type="hidden" name="model" value=<?php echo $_POST['model']; ?>>
			
			<!--keeping the to neuron for the next file save_neuron_data-->
			<input type="hidden" name="totalNeurons" value=<?php echo $_POST['totalNeurons']; ?>>

			<input type="hidden" value=<?php echo $_POST['samemodel']; ?> name="samemodel">
			<input type="hidden" value=<?php echo $simNum; ?> name="simNum">

			<?php
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

<?php
}


//$list=file("Libraries/neuron_id.txt");
?><p>There are <?php echo $_POST['totalDiffModelNeurons']; ?> neuron(s) to be processed with different models.</p>
<form action="save_neuron_data.php" method="post">
	<!--<input type="hidden" name="neuron" value=<?php echo $_POST['totalDiffModelNeurons']; ?>>-->
	<input type="hidden" value=<?php echo $_POST['totalDiffModelNeurons']; ?> name="totalDiffModelNeurons">

	<?php
	for ($loopCounter = 1; $loopCounter < $_POST['totalDiffModelNeurons']+1; $loopCounter++){
		//echo $loopCounter;
		$modelNumber = $loopCounter + $subtractedSameModel;
		//echo 'passed model : '.$_POST['model'.$modelNumber];
		if ($_POST['model'.$modelNumber]==1){$modelname="Integrate and fire";}
		if ($_POST['model'.$modelNumber]==2){$modelname="Leaky integrate and fire";}
		if ($_POST['model'.$modelNumber]==3){$modelname="Izhikevich";}

		foreach ($ModelLibrary->neuron as $model){
			if ($model->neuronid==$_POST['model'.$modelNumber]){

				$id=$_POST['name'.($loopCounter + $subtractedSameModel)];
				echo 'id '.$id;
				?><br><fieldset>
				<legend>The typical values for the <?php echo $modelname; ?> model are: </legend>
				<input type="hidden" name=<?php echo 'model'.$modelNumber; ?> value=<?php echo $_POST['model'.$modelNumber]; ?>>
				<input type="hidden" name=<?php echo 'name'.$modelNumber; ?> value=<?php echo $id; ?>><?php
				foreach ($model->item as $item){
					$DataItem= str_replace("_", " ", $item->name);
					?>
					<div class="col-sm-4">
						<?php echo $DataItem; ?>:</div><div class = "col-sm-8"> <input type="number" name=<?php echo "neuron" . $modelNumber . "item" . $item->itemid; ?> value=<?php echo $item->typicalvalue; ?> required></div><br><br>
						<?php
					}
				}?></fieldset><?php
			}
		}?>
		
		<br><input type="submit" value="Next">
	</form><br><br>
	<?php
}

?>
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
