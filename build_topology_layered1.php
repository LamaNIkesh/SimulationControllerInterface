<?php
include("head.html")
?>
<?php

if ($_SESSION['flag']==1){
	//reading neuron 



	?>

	<div class = "container">
		<div class="col-sm-12">
			<h6><font color = "#52a25e">System Builder->Simulation Parameters-><b>Layer Configuration</b></h6></font>
			<h3>
				You have selected to build a layered network
			</h3>
			<?php  
			//checks if the same model is used or different 
			//and show correct message on the screen
			if($_POST['samemodel'] == 'yes'){
				?>
				<h5>You have selected to create a network with <?php echo $_POST['noOflayers']?> layers of same neurons</h5><br>
				<?php } 

				else {
					?>
					<h5>You have selected to create a network with <?php echo $_POST['noOflayers']?> layers with different neurons</h5><br>
					<?php
				}

				?>
				<?php 
				//testing
				//echo $_POST['watchdog'];
				?>


				<form method="POST" action="build_topology_layered2.php">
					
					<?php 
					//$_POST['noOflayers'] is passed from the previous file
					for ($i=0; $i < $_POST['noOflayers']; $i++) { 
							#going through each layer and asking user to input number of neurons for each layers
						?>
						
						<?php 
								if($i == 0){//specifying first layer as input layer
									?>
									<!--<div class= "col-sm-5" style="background-color:lavender;" >-->
									<div class= "col-sm-5">
										Number of Neurons in layer <?php echo $i+1; ?> (<b>Input layer</b>): </div><input type="number" name=<?php echo "totalNeuronsLayer".($i+1) ?> min="1" max="500" value="1" align ="right" required>

										<?php
										if($_POST['samemodel'] == 'no'){
								//shows option to choose type of neuron model
											?>
											&nbsp&nbsp&nbsp&nbsp
											Neuron model: <select name=<?php echo "model".($i+1) ?> required>
											<option value="1">Integrate and fire</option>
											<option value="2">Leaky integrate and fire</option>
											<option value="3">Izhikevich</option>
										</select>


										<?php

									}

									?><br><br>
									<?php
								}
								//for last layer; output layer
								else if($i == $_POST['noOflayers'] - 1){//specifying last layer as output layer
									?>
									<br>
									<div class= "col-sm-5">
										Number of Neurons in layer <?php echo $i+1; ?> (<b>Output layer</b>):</div> <input type="number" name=<?php echo "totalNeuronsLayer".($i+1) ?> min="1" max="500" value="1" required>
										
										<?php

										if($_POST['samemodel'] == 'no'){
								//shows option to choose type of neuron model
											?>
											&nbsp&nbsp&nbsp&nbsp
											Neuron model: <select name=<?php echo "model".($i +1) ?> required>
											<option value="1">Integrate and fire</option>
											<option value="2">Leaky integrate and fire</option>
											<option value="3">Izhikevich</option>
										</select>


										<?php
									}
									?><br><br>
									<?php

								}
								//the middle layer
								else{
									?>
									<br>
									<div class= "col-sm-5">
										Number of Neurons in layer <?php echo $i+1; ?> :</div> <input type="number" name=<?php echo "totalNeuronsLayer".($i+1) ?> min="1" max="500" value="1" required>

										<?php
										if($_POST['samemodel'] == 'no'){
								//shows option to choose type of neuron model
											?>
											&nbsp&nbsp&nbsp&nbsp
											Neuron model: <select name=<?php echo "model".($i+1); ?> required>
											<option value="1">Integrate and fire</option>
											<option value="2">Leaky integrate and fire</option>
											<option value="3">Izhikevich</option>
										</select>


										<?php

									}
									?><br><br>
									<?php

								}//end of if for middle layer


							}//end of for loop
							
							//displaying option for users to choose neuron model for same model network
							if($_POST['samemodel'] == 'yes'){
								?>
								<br>
								&nbsp&nbsp&nbsp
								Neuron model: &nbsp&nbsp&nbsp<select name="model" required>
								<option value="1">Integrate and fire</option>
								<option value="2">Leaky integrate and fire</option>
								<option value="3">Izhikevich</option>
							</select>

							<hr>
							<?php
						}

						?>
						<br><br>
						<form action="build_topology_layered2.php" method="POST">
							
							<input type ="hidden" name= "watchdog" value=<?php echo $_POST['watchdog']; ?> required>
							<input type ="hidden" name= "simtime" value=<?php echo $_POST['simtime']; ?> required>
							<input type ="hidden" name= "noOflayers" value=<?php echo $_POST['noOflayers']; ?> required>
							<input type ="hidden" name= "samemodel" value=<?php echo $_POST['samemodel']; ?> required>
							<input type = "hidden" name = "simunits" value = <?php echo $_POST['simunits'] ?> required>
							<input type="submit" value="Next" name="submit">&nbsp; &nbsp; &nbsp; &nbsp;<--Lets assign neuron parameters

							
						</form>
						<br><br><br><br><br>

					</div></div>
					<?php

						//end of main if statement	
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