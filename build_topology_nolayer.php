<?php
include("head.html")
?>
<?php

if ($_SESSION['flag']==1){
	?>

	<div class = "container">
		<div class="col-sm-12">
			<h6><font color = "#52a25e">System Builder-><b>Simulation Parameters</b></h6></font>
			<h3>
				You have selected to build a network without any layers
			</h3>
			<br>
			<p>This page lets you build your own network topology using an existing Neuron Models with customisable parameters.</p> 
			<p>Please choose your simulation parameters</p>
				<form method="POST" action="build_topology_nolayer1.php">
					<div class= "col-sm-3">
					Timestamp(in ms): </div><input type="number" name="timestamp" value="0.01" disabled> (Fixed value)<br>
					<br>
					<div class= "col-sm-3">
					Number of neurons :</div> <input type="number" name="totalNeurons" min="1" max="500" value="1" required>
					<br><br>
					<!---Number of muscles in the subcircuit: <input type="number" name="muscle" min="0" max="135" value="0" required>
					<br><br>-->
					<div class= "col-sm-3">
					Are all neurons using the same model: </div><select name="samemodel" required>
					<option value="yes">Yes</option>
					<option value="no" >No</option>
					</select>
					<br><br>
					<div class = "col-sm-3">
					
					How many with different models?:</div><input type="number" name="totalDiffModelNeurons" min="0" max="500" value="0" required>(Leave it to zero if all the neurons have same model)
				
				
				<br><br>
				<!---Are all muscles using the same model: <select name="musclesamemodel" required>
				<option value="yes">Yes</option>
				 <option value="no">No</option>
			</select>-
			<br><br>-->

			<div class= "col-sm-3">
			Simulation units: </div><select name="simunits" required>
			<option value="s">Seconds</option>
			<option value="ms">Miliseconds</option>
			<option value="us">Microseconds</option>
		</select>
		<br><br>
		<div class= "col-sm-3">
		Simulation time: </div><input type="number" name="simtime" min="1" value="1" required>
		<br><br>
		<div class= "col-sm-3">
		Watchdog (ms): </div><input type="number" name="watchdog" min="1" max="1000" value="1" required>
		<br><br>
		<div class= "col-sm-3">
		<input type="submit" value="Next" name="submit" required>
		<br><br></div>
	</div></div>
	<?php
}
else{
	?>
	<div class = "container">
		<div class="col-sm-12">
			<p>You need to log in to see this page:</p>
			<form action="login.php" method="post">
				<input type="submit" value="Log in">
			</form>
			<br><br>
			<?php } ?>
		</div>
	</div>

	<?php
	include("end_page.html")
	?>