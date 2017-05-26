<?php
include("head.html")
?>
<?php

if ($_SESSION['flag']==1){
	?>

	<div class = "container">
		<div class="col-sm-12">
			<h2>
				Welcome to System Builder
			</h2>
			<p>This page lets you build your own network topology using an existing Neuron Models with customisable parameters.</p>
			
			<p>You can build a multi layered network topology with arbitary numbers of neurons on each layer with arbitary synpases or create a highly recurrent network without any visible layers. </P>
			<p>The neuron parameters are all changeable along with the synaptic connection topology among the neurons</p>
			
			<p>Would you like to build a layered network?</p>(Press Yes to create a layered network, Press no to create a network without any layers)</p>
			<form method="POST" action="#">
			<input type = "submit" value="Create layered network" name = "submit" formaction = "#"> 
			<input type="submit" value="Create non layered network" name="submit" formaction = "build_topology_nolayer.php">
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