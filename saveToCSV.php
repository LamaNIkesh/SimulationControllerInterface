<?php
include("head.html")
?>


<div class = "container">
		<div class="col-sm-12">
			<?php
			//saves the topology information into a topology initialisation file

			if ($_SESSION['flag']==1){ 
				#lets get the simulation id of the result file with xml parsing.......

				#Get total number of neurons from Sim_init file already created at the early stage of the network creation
				#$resultFile gives the location of the current result file
				
				$resultFile = $_POST['resultsFile'];
				$resultFileLoc = $_POST['resultsFileLocation'];
				$filename = $_POST['filename'];
				echo "resultsFile:".$resultFile;

				##########################################################################

		 		if(file_exists($resultFile)){ #Load XML file
				$resultXML = simplexml_load_file ($resultFile);
				echo "File found..";
				}
				#Gives the total neurons 
				#reading total neuron numbers
				$simId = $resultXML->packet->simulation;
				echo "simulation ID ".$simId;


		 		//here we will access the user database with the simualtion id for number of neurons and simulation duration from the database
		 		//these information are then used to create a txt version of the results
		 		$server = 'localhost';
				$user = 'root';
				$pass = '';
				$db = 'WebInterface';


				try{
					
					$connection = mysqli_connect("$server",$user,$pass,$db);
					$result = mysqli_query($connection, "select * from UserSimulation where SimulationId = '$simId'")
						or die("No matching simulation id found!!".mysql_error());
					
					#if ($result['simId']){
					$row = mysqli_fetch_array($result);
					echo "Found somethign";
					#echo $row['NoOfNeurons'];
					#echo $row['SimTime_ms'];
					$totalNoOfNeurons = $row['NoOfNeurons'];

					#$NoOfNeurons = $row['NoOfNeurons'];
					#$SimulationTime = $row['SimTime_ms'];
				}
				catch(Exception $e){
					echo "error";
				}
			
				#echo "No of neurons: ".$NoOfNeurons;

				//absolute filepath for python execution
				$filePath = '/home/nikesh/Documents/WebServer/Newbranch/SimulationControllerInterface/'.$resultFileLoc;
				#$file = $filename

				//executing python script that converts xml results into a txt file

				try {
					$output = shell_exec('sudo -u daemon python /home/nikesh/Documents/WebServer/Newbranch/SimulationControllerInterface/xmlParsing/xmlResultsToCSV.py 2>&1 '.$filePath.' '.$filename.' '.$totalNoOfNeurons);
					echo "<pre>$output</pre>";
					$filenameWithoutextension = substr($filename,0,-4); # this returns the filename without extension, 
																		#this will be used to locate csv file since only extension is changed with few added bits
					$csvFileName = $resultFileLoc.$filenameWithoutextension."resultscsv.txt";
					#Downloading with the correct filename
					?>
					<a id="cont" href=<?php echo $csvFileName ;?> download= <?php echo $csvFileName;?> >Save results as text file to your computer</a>
				<?php 	
		 		}
		 		catch (Exception $e){
		 			echo "error running python script";
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