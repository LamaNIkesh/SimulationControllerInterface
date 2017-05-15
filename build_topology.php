<?php
include("head.html")
?>
<?php

if ($_SESSION['flag']==1){
	?>

	<div class = "container">
		<div class="col-sm-12">
			<h1>
				Build
			</h1>
			<p>This pages would let you build your own topology using an existing subcircuit of the <em>C. elegans</em> connectome. 
				<form method="POST" action="build_topology1.php">
					Timestamp: <input type="number" name="timestamp" value="0" disabled> (Fixed value)<br>
					<br>
					Number of neurons in the subcircuit: <input type="number" name="neuron" min="1" max="302" value="1" required>
					<br><br>
					Number of muscles in the subcircuit: <input type="number" name="muscle" min="0" max="135" value="0" required>
					<br><br>
					
					Are all neurons using the same model: <select name="samemodel" required>
					<option value="yes">Yes</option>
					<option value="no">No</option>
				</select>
				<br><br>
				Are all muscles using the same model: <select name="musclesamemodel" required>
				<option value="yes">Yes</option>
				<! -- <option value="no">No</option> -->
			</select>
			<br><br>
			Simulation units: <select name="simunits" required>
			<option value="s">Seconds</option>
			<option value="ms">Miliseconds</option>
			<option value="us">Microseconds</option>
		</select>
		<br><br>
		Simulation time: <input type="number" name="simtime" min="1" value="1" required>
		<br><br>
		Watchdog (ms): <input type="number" name="watchdog" min="1" max="1000" value="1" required>
		<br><br>
		<input type="submit" value="Next" name="submit" required>
		<br><br>
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