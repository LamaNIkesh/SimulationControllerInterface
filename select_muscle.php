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
$muscle=$_POST['muscle'];
$musclesamemodel=$_POST['musclesamemodel'];
$list=file("Libraries/muscle_id.txt");
	if ($_POST['musclesamemodel']=='yes'){
		?>
		<p>There are <?php echo $muscle; ?> muscles to be processed with the same model</p>
		<form action="save_muscle.php" method="post">
		<input type="hidden" name="neuron" value=<?php echo $_POST['neuron']; ?>>
		<input type="hidden" value=<?php echo $_POST['muscle']; ?> name="muscle">
		<input type="hidden" value=<?php echo $_POST['musclesamemodel']; ?> name="musclesamemodel">
		<?php
		for ($number = 1; $number < $_POST['neuron']+1; ++$number){
			?>
			<input type="hidden" name=<?php echo "name".$number?> value=<?php echo $_POST['name'.$number]; ?>>
		
		<?php
		}
		for ($number = 1; $number < $_POST['neuron']+1; ++$number){
		?>
			<input type="hidden" name=<?php echo "name".$number?> value=<?php echo $_POST['name'.$number]; ?>>
		<?php
		
		}
		for ($number = 1; $number < $muscle+1; ++$number){
			?> 
			Muscle <?php echo $number; ?> name: <select name=<?php echo 'musclename'.$number; ?> required>
			<?php
			for ($index = 0; $index < 95; ++$index){
				?>
				<option value=<?php echo $index;?>> <?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$index]"); ?> </option>
				<?php
			}
			?>
			</select><br><br>
			<?php
		}
		?>
		Muscle model: <select name="musclemodel" required>
		<option value="1">Linear</option>
		<option value="2">Wiener</option>
		<option value="3">Adapted</option>
		</select>
		<br><br>
		<input type="submit" value="Next">
		</form><br><br>
	<?php
	}
	else{
		
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