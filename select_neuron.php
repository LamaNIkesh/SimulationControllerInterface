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
	$list=file("Libraries/neuron_id.txt");
	// echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[0]");
	$neurons=$_POST['neuron'];
	if ($_POST['samemodel']=='yes'){
		?><p><?php echo $neurons; ?> neurons to be processed with the same model</p>
		<form action="save_neuron.php" method="post">
		<input type="hidden" name="neuron" value=<?php echo $neurons; ?>>
		<input type="hidden" value=<?php echo $_POST['muscle']; ?> name="muscle">
		<input type="hidden" value=<?php echo $_POST['samemodel']; ?> name="samemodel">
		<input type="hidden" value=<?php echo $_POST['musclesamemodel']; ?> name="musclesamemodel">
		<?php
		for ($number = 1; $number < $neurons+1; ++$number){
		?> 
		Neuron <?php echo $number; ?> name: <select name=<?php echo 'name'.$number; ?> required>
		<?php
		for ($index = 0; $index < 302; ++$index){
		?>
		<option value=<?php echo $index;?>> <?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$index]"); ?> </option>
		<?php
		}
		?>
		</select><br><br>
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
	?><p><?php echo $neurons; ?> neurons to be processed with different models</p>
	<form action="save_neuron.php" method="post">
	<input type="hidden" name="neuron" value=<?php echo $neurons; ?>>
	<input type="hidden" value=<?php echo $_POST['muscle']; ?> name="muscle">
	<input type="hidden" value=<?php echo $_POST['samemodel']; ?> name="samemodel">
	<input type="hidden" value=<?php echo $_POST['musclesamemodel']; ?> name="musclesamemodel">
<?php
for ($number = 1; $number < $neurons+1; ++$number){
	?>
	<p>Neuron <?php echo $number; ?> </p>
		Neuron name: <select name=<?php echo 'name'.$number; ?> required>
		<?php
		for ($index = 0; $index < 302; ++$index){ ?>
		<option value=<?php echo $index;?>> <?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$index]"); ?> </option>
		<?php
		}
		?>
		</select><br><br>
		Neuron model: <select name=<?php echo 'model'.$number; ?> required>
		<option value="1">Integrate and fire</option>
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
<?php
include("end_page.html")
?>
