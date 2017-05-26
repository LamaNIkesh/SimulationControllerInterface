<?php
include("head.html")
?>

<?php $number = 0; ?>

<div class = "container">
	<div class="col-sm-12">
		<form action="save_neuron.php" method="post">

		<?php

		if ($_SESSION['flag'] == 1){
			$list=file("Libraries/neuron_id.txt");
	// echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[0]");
			$totalNeurons=$_POST['totalNeurons'];
			if ($_POST['samemodel']=='yes' and $_POST['totalDiffModelNeurons'] == 0){
				?>
				<p><?php echo $totalNeurons; ?> neuron(s) to be processed with the same model</p>
				<form action="save_neuron.php" method="post">
					<input type="hidden" name="totalNeurons" value=<?php echo $totalNeurons; ?>>
					<input type="hidden" value=<?php echo $_POST['samemodel']; ?> name="samemodel">
					<input type="hidden" value=<?php echo $_POST['totalDiffModelNeurons']; ?> name="totalDiffModelNeurons">
			<!--
			<input type="hidden" value=<?php echo $_POST['muscle']; ?> name="muscle">
			
			<input type="hidden" value=<?php echo $_POST['musclesamemodel']; ?> name="musclesamemodel">-->
			
			<?php
			for ($number = 1; $number < $totalNeurons + 1; ++$number){
				?> 
				<!--Neuron-->  <!--name:--> <input type ="hidden" name=<?php echo 'name'.$number; ?> value=<?php echo 'name'.$number; ?> required>
			<?php
		}
		?>
		
	Neuron model: <select name="model" required>
	<option value="1">Integrate and fire</option>
	<option value="2">Leaky integrate and fire</option>
	<option value="3">Izhikevich</option>
</select>
<br><br>
<input type="submit" value="Next">
</form><br><br>

<?php
}
// this option is when the user has both same and different combinations of models.

else {
	$number = 1;
	$subtractedSameModel = 0;
	//checks if there are combination of same and different models
	//this section is executed when same models aer present
	?> <form action="save_neuron.php" method="post"> <?php
 	if($totalNeurons > $_POST['totalDiffModelNeurons']){
		$subtractedSameModel = $totalNeurons - $_POST['totalDiffModelNeurons'];
		//$totalNeurons = $_POST['totalDiffModelNeurons'];
		$totalDiffModelNeurons = $_POST['totalDiffModelNeurons'];
		?><p><?php echo $subtractedSameModel;?> neurons to be processed with same models</p>

		
			<input type="hidden" name="totalDiffModelNeurons" value=<?php echo $totalDiffModelNeurons; ?>>
			<input type="hidden" name="sameModelNeurons" value=<?php echo $subtractedSameModel; ?>>
			<input type="hidden" name="totalNeurons" value=<?php echo $totalNeurons; ?>>
			<input type="hidden" value=<?php echo $_POST['samemodel']; ?> name="samemodel">


			<?php
			for ($number = 1; $number < $subtractedSameModel + 1; $number++){
				echo "number ".$number;
				?> 
				<!--Neuron-->  <!--name:--> <input type ="hidden" name=<?php echo 'name'.$number; ?> value=<?php echo 'name'.$number; ?> required>
			<?php
		}
		?>	


			Neuron model: <select name="model" required>
			<option value="1">Integrate and fire</option>
			<option value="2">Leaky integrate and fire</option>
			<option value="3">Izhikevich</option>
		</select>
	<br><br>


	<?php
}
$noOfSameModels = $number;
echo $noOfSameModels;
//This section deals with different models
?><p><?php echo $totalNeurons; ?> neurons to be processed with different models</p>
	<input type="hidden" name="totalNeurons" value=<?php echo $totalNeurons + $subtractedSameModel; ?>>
	<input type="hidden" value=<?php echo $_POST['totalDiffModelNeurons']; ?> name="totalDiffModelNeurons">

		<!---
		<input type="hidden" value=<?php echo $_POST['muscle']; ?> name="muscle">
		<
		<input type="hidden" value=<?php echo $_POST['musclesamemodel']; ?> name="musclesamemodel">-->
		
		<?php
		for ($neuronNum = 0; $neuronNum < $totalNeurons+1; $neuronNum++){
			echo $neuronNum;
			$number = $neuronNum + $noOfSameModels;
			echo "diff neurons ".$number;
			?>
			<p>Neuron <?php echo $number; ?> </p>
			<input type ="hidden" name=<?php echo 'name'.$number; ?> value=<?php echo 'name'.$number ; ?> required>
			<!---
			Neuron name: <select name=<?php echo 'name'.$number; ?> required>
			<?php
			for ($index = 0; $index < 302; ++$index){ ?>
			<!--<option value=<?php echo $index;?>> <?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$index]"); ?> </option>-->
		
			<?php
		}
		?>

	
	Neuron model: <select name=<?php echo 'model'.$number; ?> required>
	<option value="1">Integrate and fire</option>name
	<option value="2">Leaky integrate and fire</option>
	<option value="3">Izhikevich</option>
</select>
<br><br>
<?php
}?>
<br><input type="submit" value="Next">
</form><br><br>

<?php
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
