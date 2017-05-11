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
	}
}
}
if ($flag==1){
if(file_exists('Libraries/ModelLibrary_metadata.xml')){ #Load XML file
	$ModelLibrary = simplexml_load_file ("Libraries/ModelLibrary_metadata_muscles.xml");
}
else {
 	exit ('Could not load the file...');
}
	if ($_POST['musclesamemodel']=='yes'){
		if ($_POST['musclemodel']==1){$modelname="Linear";}
		if ($_POST['musclemodel']==2){$modelname="Wiener";}
		if ($_POST['musclemodel']==3){$modelname="Adapted";}
		?><p>There are <?php echo $_POST['muscle']; ?> muscles to be processed with the same model. </p>
		<p> The equation for the <?php echo $modelname; ?> model is: </p>
		<?php

		if ($_POST['musclemodel']==1){
			?>
			$$ \theta_3 \ddot{F}(t) + \theta_2 \dot{F}(t) + \theta_1 F(t) = \theta_0 u(t) $$
			<?php
			}
		if ($_POST['musclemodel']==2){
			?>
			$$ \theta_3 \ddot{q}(t) + \theta_2 \dot{q}(t) + \theta_1 q(t) = \theta_0 u(t) $$
			$$ f(t) = \frac{q(t)^m}{q(t)^m + k^m}$$
			<?php
			}
		if ($_POST['musclemodel']==3){
			?>
			$$ \dot{C}_N(t) + \frac{C_N (t)}{\tau_c} = u(t) $$
			$$ \dot{F} + \frac{F(t)}{\tau_1} = A C_N (t)$$
			<?php
			}
		?>
		<p> The typical values for the <?php echo $modelname; ?> model are: </p>
		<form action="save_muscle_data.php" method="post">
		<input type="hidden" name="neuron" value=<?php echo $_POST['neuron']; ?>>
		<input type="hidden" name="muscle" value=<?php echo $_POST['muscle']; ?>>
		<?php
		for ($number = 1; $number < $_POST['neuron']+1; ++$number){
		?>
			<input type="hidden" name=<?php echo "name".$number?> value=<?php echo $_POST['name'.$number]; ?>>
		<?php
		}
		for ($number = 1; $number < $_POST['muscle']+1; ++$number){
		?>
			<input type="hidden" name=<?php echo "musclename".$number?> value=<?php echo $_POST['musclename'.$number]; ?>>
		<?php
		}
		?>
		<?php
		for ($number = 1; $number < $_POST['muscle']+1; ++$number){
		?>
		<input type="hidden" name=<?php echo "musclename".$number?> value=<?php echo $_POST['musclename'.$number]; ?>>
		<?php
		}
		?>
		<input type="hidden" name="model" value=<?php echo $_POST['musclemodel']; ?>>
		<input type="hidden" value=<?php echo $_POST['musclesamemodel']; ?> name="musclesamemodel">
		<?php
		foreach ($ModelLibrary->muscle as $model)
		{
			if ($model->muscleid==$_POST['musclemodel']){
				foreach ($model->item as $item){
					$DataItem= str_replace("_", " ", $item->name);
					?>
					&nbsp; &nbsp; <?php echo $DataItem; ?>: <input type="number" name=<?php echo "item" . $item->itemid; ?> value=<?php echo $item->typicalvalue; ?> required><br><br>
					<?php
				}
			}
		}
		?>
		<input type="submit" value="Next">
		</form><br><br>
		<?php
	}
	else{
		/* $list=file("muscle_id.txt");
		?><p>There are <?php echo $_POST['muscle']; ?> neurons to be processed with different models.</p>
		<form action="save_muscle_data.php" method="post">
		<input type="hidden" name="neuron" value=<?php echo $_POST['neuron']; ?>>
		<?php
		for ($number = 1; $number < $_POST['neuron']+1; ++$number){
		?>
			<input type="hidden" name=<?php echo "name".$number?> value=<?php echo $_POST['name'.$number]; ?>>
		<?php
		}
		?>
		<input type="hidden" value=<?php echo $_POST['muscle']; ?> name="muscle">
		<input type="hidden" value=<?php echo $_POST['musclesamemodel']; ?> name="musclesamemodel">
		<?php
		for ($number = 1; $number < $_POST['muscle']+1; ++$number){
		if ($_POST['musclemodel'.$number]==1){$modelname="Linear";}
		if ($_POST['musclemodel'.$number]==2){$modelname="Wiener";}
		if ($_POST['musclemodel'.$number]==3){$modelname="Adapted";}
		?>
		<input type="hidden" name=<?php echo 'musclemodel'.$number; ?> value=<?php echo $_POST['musclemodel'.$number]; ?>>
		<input type="hidden" name=<?php echo 'musclename'.$number; ?> value=<?php echo $_POST['musclename'.$number]; ?>>
		<?php		
		foreach ($ModelLibrary->muscle as $model){

			if ($model->neuronid==$_POST['model' . $number]){
			$id=$_POST['name'.$number];
			?><br><fieldset>
			<legend>Neuron <?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$id]"); ?>. The typical values for the <?php echo $modelname; ?> model are: </legend>
			<?php
			foreach ($model->item as $item){
				$DataItem= str_replace("_", " ", $item->name);
				?>
				&nbsp; &nbsp; <?php echo $DataItem; ?>: <input type="number" name=<?php echo "muscle" . $number . "item" . $item->itemid; ?> value=<?php echo $item->typicalvalue; ?> required><br><br>
				<?php
			}
		}?></fieldset><?php
		}
		}?>
		
		<br><input type="submit" value="Next">
		</form><br><br>
		<?php */
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
<?php
include("end_page.html")
?>