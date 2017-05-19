<?php
include("head.html")
?>

<div class = "container">
	<div class="col-sm-12">
		<form action="save_neuron.php" method="post">

		<?php

		if ($_SESSION['flag'] == 1){
			$list=file("Libraries/neuron_id.txt");
	// echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[0]");
			$no_of_neurons=$_POST['neuron_num'];
			if ($_POST['samemodel']=='yes' and $_POST['no_of_diff_neurons'] == 0){
				?>
				<p><?php echo $no_of_neurons; ?> neuron(s) to be processed with the same model</p>
				<form action="save_neuron.php" method="post">
					<input type="hidden" name="no_of_neurons" value=<?php echo $no_of_neurons; ?>>
					<input type="hidden" value=<?php echo $_POST['samemodel']; ?> name="samemodel">
					<input type="hidden" value=<?php echo $_POST['no_of_diff_neurons']; ?> name="no_of_diff_neurons">
			<!--
			<input type="hidden" value=<?php echo $_POST['muscle']; ?> name="muscle">
			
			<input type="hidden" value=<?php echo $_POST['musclesamemodel']; ?> name="musclesamemodel">-->
			
			<?php
			for ($number = 1; $number < $no_of_neurons + 1; ++$number){
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
else {
	$number = 1;
	$subtractedSameModel = 0;
	//checks if there are combination of same and different models
	//this section is executed when same models aer present
	?> <form action="save_neuron.php" method="post"> <?php
 	if($no_of_neurons > $_POST['no_of_diff_neurons']){
		$subtractedSameModel = $no_of_neurons - $_POST['no_of_diff_neurons'];
		$no_of_neurons = $_POST['no_of_diff_neurons'];
		$no_of_diff_neurons = $_POST['no_of_diff_neurons'];
		?><p><?php echo $subtractedSameModel;?> neurons to be processed with same models</p>

		
			<input type="hidden" name="no_of_diff_neurons" value=<?php echo $no_of_diff_neurons; ?>>
			<input type="hidden" name="no_of_same_neurons" value=<?php echo $subtractedSameModel; ?>>
			<input type="hidden" name="no_of_neurons" value=<?php echo $no_of_neurons; ?>>
			<input type="hidden" value=<?php echo $_POST['samemodel']; ?> name="samemodel">


			<?php
			for ($number = 1; $number < $subtractedSameModel + 1; ++$number){
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


//This section deals with different models
?><p><?php echo $no_of_neurons; ?> neurons to be processed with different models</p>
	<input type="hidden" name="no_of_neurons" value=<?php echo $no_of_neurons + $subtractedSameModel; ?>>
	<input type="hidden" value=<?php echo $_POST['no_of_diff_neurons']; ?> name="no_of_diff_neurons">

		<!---
		<input type="hidden" value=<?php echo $_POST['muscle']; ?> name="muscle">
		<
		<input type="hidden" value=<?php echo $_POST['musclesamemodel']; ?> name="musclesamemodel">-->
		
		<?php
		for ($number = 1; $number < $no_of_neurons+1; ++$number){
			?>
			<p>Neuron <?php echo $number; ?> </p>
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
