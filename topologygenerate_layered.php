<?php
include("head.html")
?>

<script type="text/javascript" src="dist/vis.js"></script>
<link href="dist/vis.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
var nodes, edges, network;
    // convenience method to stringify a JSON object
    function toJSON(obj) {
    	return JSON.stringify(obj, null, 4);
    }
    function addNode() {
    	try {
    		var sen='#66FF00';
    		var inter='#FFFF00';
    		var motor='#0066FF';
    		nodes.add({
    			id: document.getElementById('node-id').value,
    			label: document.getElementById('node-label').value,
    			color:sen
    		});
    	}
    	catch (err) {
    		alert(err);
    	}
    }
    function addEdge() {
    	try {
    		edges.add({
    			id: document.getElementById('edge-id').value,
    			from: document.getElementById('edge-from').value,
    			to: document.getElementById('edge-to').value,
    			arrows:'to'
    		});
    	}
    	catch (err) {
    		alert(err);
    	}
    }
    function writeToFile()
    {
    	var txtFile = "data.txt";
    	var file = new File(txtFile);
    	var str = "My string";
    	file.open("w");
    	file.writeln("first line");
    	file.write(str);
    	file.close();
    }
    function draw(topology) {
        // create an array with nodes
        //writeToFile();
        nodes = new vis.DataSet();
        var neuron = document.getElementById("neuron").value;
        //document.write(neuron);
        //var muscle = document.getElementById("muscle").value;
        nodes.on('*', function () {
        	document.getElementById('nodes').innerHTML = JSON.stringify(nodes.get(), null, 4);
        });
        var counter2=1;
        //creates nodes
        for (i=1; i<=neuron; i++){	
        	var n = i.toString();
            //document.write(n);
        	var lab1="neuron";
        	var lab2=lab1.concat(n);
            console.log(lab2);
        	var lab = document.getElementById(lab2).value;
            
            nodes.add([
            	{id:counter2, label: lab, color:'#99CC33 '}]);
            counter2=counter2+1;
        }
        // create an array with edges
        edges = new vis.DataSet();
        edges.on('*', function () {
        	document.getElementById('edges').innerHTML = JSON.stringify(edges.get(), null, 4);
        });

        //Fully interconnected network
        if(topology == 1)
        {//for fully connected topology
        	var counter=1;
        	var str1 = "neuron";
        	var str2 = "synapse";
        	var from = "";
        	var to = "";
        	var check = "";
        	var n = "";
        	try {
        		for (i=1; i<=neuron; i++){
        			for (j=1; j<=neuron; j++){
        				from = i.toString();
        				to = j.toString();
        				check = str1.concat(from,str2,to);
        				if(document.getElementById(check)!=null){
                   //var x = document.getElementById(check);
                   //document.write(x);
                   
                   n = counter.toString();
                   edges.add({id: n, from: to, to: from, arrows:'to'});
                   counter=counter+1;
                //j = j+2;
            }          
        }
    }
}
catch (err) {
	alert(err);
}
}
else if(topology == 2){
//generates a random connection
var max = neuron;
var min = 1;
var counter=1;
//the values for str1 and str2 are changed so that right 
//fields can be accessed via id 
var str1 = "neuron";
var str2 = "synapse";
var from = "";
var to = "";
var connection = "";
var n = "";
/*var fso  = new ActiveXObject("Scripting.FileSystemObject");
var fh = fso.CreateTextFile("test.txt", true);
fh.WriteLine("Some text goes here...");
fh.Close();*/
try {
	for (i=1; i<=neuron; i++){
    //document.getElementById('i').innerHTML = i;
    for (j=1; j<=neuron; j++){
       
                    from = i.toString();
                    to = j.toString();
                    connection = str1.concat(from,str2,to);
                    if(document.getElementById(connection)!=null){
                        //var y = document.getElementById(connection);
                        n = counter.toString();
                        edges.add({id: n, from: to, to: from, arrows:'to'});
                        counter=counter+1;
                    }
                }
            }
        }
        catch (err) {
        	alert(err);
        }
    }

        // create a network
        var container = document.getElementById('network');
        var data = {
        	nodes: nodes,
        	edges: edges
        };
        var options = {
            
        layout: {
            randomSeed: undefined,
            improvedLayout:false,
            hierarchical: {
              enabled:false,
              levelSeparation: 200,
              nodeSpacing: 200,
              treeSpacing: 200,
              blockShifting: true,
              edgeMinimization: false,
              parentCentralization: false,
              direction: 'DU',        // UD, DU, LR, RL
              sortMethod: 'hubsize'   // hubsize, directed
            }
          }
        
    };
    network = new vis.Network(container, data, options);
    network.setOptions(options);
}
</script>


<!-- end of script-->
<div class = "container">
	<div class="col-sm-12">
		<h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->NeuronModelParameter->Creating Initialisation File->Create Topology-><b>Topology Viewer</b></h6></font>
		<!--<p><h3>Create Topology</h3></p>-->

		<?php

		if ($_SESSION['flag']==1){

            $simNum = $_POST['simNum'];

			$neuronlistPath = "SimulationXML/".$userLogged . "/Layered/neuronlist.txt";
			//$RandomTopology = fopen("SimulationXML/".$userLogged . "/Topology.txt", "w"); 
            //$RandomTopology = fopen("SimulationXML/".$userLogged . "/Layered/FullTopology.txt", "w");
            $FullTopology = fopen("SimulationXML/".$userLogged . "/Layered/FullTopology.txt", "w");
            $RandomTopology = fopen("SimulationXML/".$userLogged . "/Layered/RandomTopology.txt", "w");
			$neuronslist=file($neuronlistPath);
                //echo $list[9];
			$totalNeurons=$_POST['totalNeurons'];
            $totallayers = $_POST['noOflayers'];
            //echo $_POST['totalNeuronsEachLayer2'];
			?><input type="hidden" name="neuron" id = "neuron" value=<?php echo $_POST['totalNeurons']; ?>>
			<?php 

                //echo $_POST['totalNeurons'];
			$num = 1;
                //echo "neuron 1 is ".$_POST['neuron'.$num];
                //Fully interconnected
			
            $connection = '';
            //each element contains neuron numbers as 1 2
            //                                        3 4 5 
            // each row is extracted and separated with spaces and put into a multi dimensional
            //array where rows are layers and columns give neurons

            //echo "Neurons: ". $neuronslist[0];
            //echo "Neuron list size: ".count($neuronslist);
            $no_of_layers = count($neuronslist);
            //$layers = array();  
            $layers_neurons = array();
            $NeuronNumberEachLayer = array();
            $lastlayer = 0;

            //for loop to create a fully connected topology
            //traverse thrugh each element                                
            for ($i=0; $i <$no_of_layers; $i++) { 
                # dynamic array is created for each layer 
                ${"layer$i"} = array();
                //the contents of each layer is separated and put elementwise 
                //into different layer arrays
                $NeuronNumberEachLayer = explode(" ", $neuronslist[$i]);
                for ($j=0; $j < count($NeuronNumberEachLayer); $j++) { 
                    # this breaks each row from previous loop and place neurons 
                   ${"layer$i"}[$j] = $NeuronNumberEachLayer[$j];
                }   
                    //echo "$layer".$layers[$i][$i]; 
            }


            ?>
			
			<?php  
            #using switch case to deal with different topology
            #the nuerons are listed in a file.
            #open the file and extract neuron details to form a topology
            $noOfconnections = 0;
            $connection = array();
			switch ($_POST['topologyType']) {
				case 'fullyConnected':

                //--------------------------------------------------------------------------------------------------------------------------------
                //Most of the task here is to reorganise the different layers
                //into different arrays, creating connections and passing into javascript with id for visualisation
                //
				?>
				<p><h3>Fully Connected Topology</h3></p>

				<?php 
				
                //check only upto second last array
                //first iteration takes in 1st and 2nd array or layer so no need to 
                //all the way up the last layer
                
                ?>
                
                <input type="hidden" name=<?php echo "neuron". $totalNeurons; ?> id = <?php echo "neuron". $totalNeurons; ?> value=<?php echo "neuron". $totalNeurons; ?>>

                <?php
                for ($layer=0; $layer < $no_of_layers ; $layer++) { 

                    //traversing each array/layer
                    //while exploding previously, it creates one extra element so that the 
                    //size of 3 elements becomes 4. To eliminate that -1 is presented on the count
                    //no of neurons in a layer
                    for ($layernum=0; $layernum <count(${"layer$layer"})-1 ; $layernum++) { 

                        if($layer == 0){
                            //this is just to list the first layer neurons on the topology file.
                            //since first layer doesn't receive synapses from anywhere else, its just
                            //is 1
                            //   2
                            //   3
                        fwrite($FullTopology,${"layer$layer"}[$layernum]." "."\n"); //puts an empty \n character at the end
                        ?>
                        <input type="hidden" id = '<?php echo "neuron".${"layer$layer"}[$layernum];?>' name = <?php echo "neuron".${"layer$layer"}[$layernum];?> value = <?php echo "neuron".${"layer$layer"}[$layernum]; ?>>
                        <?php 
                        }
                        else{

                        $previouslayer = $layer -1;
                        fwrite($FullTopology,${"layer$layer"}[$layernum]);
                        //echo "next layer size:".count(${"layer$nextlayer"});
                        for ($j=0; $j <count(${"layer$previouslayer"}) ; $j++) { 
                            # Here for each layer element, all the second layer elements are connected                                            
                            $connection[$noOfconnections] = "neuron".${"layer$layer"}[$layernum]. "synapse".${"layer$previouslayer"}[$j];
                            echo $connection[$noOfconnections];
                            echo "<br>";
                            
                            fwrite($FullTopology, " ".${"layer$previouslayer"}[$j]);
                      
                            ?> 
                            <!-- Passing neuron number to the javascript for visualisation -->

                            <input type="hidden" id = '<?php echo "neuron".${"layer$layer"}[$layernum];?>' name = <?php echo "neuron".${"layer$layer"}[$layernum];?> value = <?php echo "neuron".${"layer$layer"}[$layernum]; ?>>

                            <input type="hidden" id = '<?php echo $connection[$noOfconnections];?>' name = <?php echo $connection[$noOfconnections];?> value = <?php echo $connection[$noOfconnections];?>>

                            <!-- // <?php if($layer == $no_of_layers-2) {?>
                            // <?php  
                            // $lastlayer = $layer + 1; ?>
                            // <input type="hidden" id = '<?php echo "neuron".${"layer$lastlayer"}[$layernum];?>' name = <?php echo "neuron".${"layer$lastlayer"}[$layernum];?> value = <?php echo "neuron".${"layer$lastlayer"}[$layernum]; ?>>
                            // <?php } ?> -->
                            <?php $noOfconnections++;
                            }       
                        }

                    }
                }
                //--------------------------------------------------------------------------------------------------------------------------------

				?><input type= "submit" method = "post" value = "Visualise network" onclick = "draw(2)">
				<br><br>
				<form action="save_layered_topology.php" method="post">
                <input type= "hidden" name = "topology_type" id = "topology_type" value = "FullTopology">
                <input type ="hidden" name = "totalNeurons" id ="totalNeurons" value = <?php echo $totalNeurons; ?>>
                <input type = "hidden" name = "neurons_layer1" id = "neurons_layer1" value = <?php echo $_POST['totalNeuronsEachLayer1']; ?>>
                 <input type="hidden" value=<?php echo $simNum; ?> name="simNum">
				<input type="submit" value="Next" action = "save_layered_topology.php"></form>
				<?php 
				break;
                //------------------------Random Network--------------
				case 'randomlyConnected':
			    #-----------------------------------------randomly connected network
                #--Pretty much same as fully connected network, but the connections are randomly chosen 
                #--Random number is generated within the range of neurons in each layer and use that to 
                #--calculate the neuron to form synapse with.
				?>
				<p><h3>Random Topology</h3></p>

				<?php 
                
                //check only upto second last array
                //first iteration takes in 1st and 2nd array or layer so no need to 
                //all the way up the last layer
                // $noOfconnections = 0;
                // $connection = array();
                ?>

                <input type="hidden" name=<?php echo "neuron". $totalNeurons; ?> id = <?php echo "neuron". $totalNeurons; ?> value=<?php echo "neuron". $totalNeurons; ?>>

                <?php
                for ($layer=0; $layer < $no_of_layers ; $layer++) { 
                    //traversing each array/layer
                    //while exploding previously, it creates one extra element so that the 
                    //size of 3 elements becomes 4. To eliminate that -1 is presented on the count
                    //no of neurons in a layer
                    for ($layernum=0; $layernum <count(${"layer$layer"})-1 ; $layernum++) 
                    { 
                        if($layer == 0){
                            //this is just to list the first layer neurons on the topology file.
                            //since first layer doesn't receive synapses from anywhere else, its just
                            //is 1
                            //   2
                            //   3
                            fwrite($RandomTopology,${"layer$layer"}[$layernum]."\n");
                            ?>
                            <input type="hidden" id = '<?php echo "neuron".${"layer$layer"}[$layernum];?>' name = <?php echo "neuron".${"layer$layer"}[$layernum];?> value = <?php echo "neuron".${"layer$layer"}[$layernum]; ?>>
                            <?php
                        }
                        else
                        {
                            $previouslayer = $layer -1;
                            //gives rest of the neuron numbers in ascending order...so that would be in second third etc...
                            //echo "neuron num:".${"layer$layer"}[$layernum];
                            fwrite($RandomTopology,${"layer$layer"}[$layernum]); 
                            //echo "Connection to: ".${"layer$layer"}[$layernum];

                            //echo "next layer size:".count(${"layer$nextlayer"});
                            for ($j=0; $j <count(${"layer$previouslayer"}) ; $j++) 
                            { 
                                //generate random number to use that to choose neuron to connect and also to choose 
                                //how many neurons to connect to
                                //previous layer is used since, we are dealing with synapses received.
                                //echo "j variable is: ".$j;
                                //---------------------------------------------------------------------------------
                                //if there are 6 neurons, we generate random number from 0-4 and not 5 because,
                                //there is weird problem which messes up the format in randomtopology.txt file.
                                //I think that is because if $j randomly goes to upto 5 for 6 previous neurons[prev layer]
                                //then the for loop thinks that next number is 6 but it stays in the loop sinice next num can be 
                                //any number so it stays in this loop but next neurons is already introduced which messes up the whole
                                //format which is important to create topo_Ini file. 

                                //For now, genreating random number 1 less than total number of neurons seems to eradicate the issue

                                $randomConnection = mt_rand(0,(count(${"layer$previouslayer"})-2));
                                $randomDivider = mt_rand(1,10);
                                $j = intval($j + (0.3*$randomConnection*count(${"layer$previouslayer"}))/$randomDivider);
                                # Here for each layer element will be connected to the previous layer neurons which are randomly chosen
                                # the random number will decide the index to choose from, and the also the number of times this particular
                                # loop goes on. 
                                # If the loop goes on for 4 times, 4 connections are formed which could be same or different                                             
                                //$connection[$noOfconnections] = "neuron".${"layer$layer"}[$layernum]. "synapse".${"layer$previouslayer"}[$j];
                                $connection[$noOfconnections] = "neuron".${"layer$layer"}[$layernum]. "synapse".${"layer$previouslayer"}[$randomConnection];
                                //echo $connection[$noOfconnections];
                                //echo "<br>";
                                //echo "randomConnection:".$randomConnection;
                                //echo "<br>";
                                //echo "randomDivider:".$randomDivider;
                                //echo "<br>";
                                //echo "Neuron num:".$
                                //echo "ranodm connectio number: ".$randomConnection."<br>";
                                //echo "connection from : ".${"layer$previouslayer"}[$randomConnection]."<br>";
                                //echo "no of neurons in prev layer".count(${"layer$previouslayer"})."<br>";
                                //echo "j value: ".$j."<br>";
                                //echo "connection from neuron: ".${"layer$previouslayer"}[$randomConnection]."<br>";
                                if(${"layer$previouslayer"}[$randomConnection] != NULL){
                                fwrite($RandomTopology," ".${"layer$previouslayer"}[$randomConnection]);
                                }
                                
                                ?> 
                                <!-- Passing neuron number to the javascript for visualisation -->
                                <input type="hidden" id = '<?php echo "neuron".${"layer$layer"}[$layernum];?>' name = <?php echo "neuron".${"layer$layer"}[$layernum];?> value = <?php echo "neuron".${"layer$layer"}[$layernum]; ?>>
                                <input type ="hidden" name = "totalNeurons" id ="totalNeurons" value = <?php echo $totalNeurons; ?>>
                                <input type="hidden" id = '<?php echo $connection[$noOfconnections];?>' name = <?php echo $connection[$noOfconnections];?> value = <?php echo $connection[$noOfconnections];?>>

                                <?php $noOfconnections++;
                                //This recalculates the $j value to decide how many times the loop should run
                            } 
                            fwrite($RandomTopology," "."\n");     
                        }//end of else          
                    }//end of second for loop statement
                }//end of for looop
                
                //-------------------------------------------------------------------------------------------------------------------------------
                echo "checking ".$_POST['totalNeuronsEachLayer'.'1'];
				?>
				<input type= "submit" method = "post" value = "Visualise Network" onclick = "draw(2)">  
				<br><br>
				<form action="save_layered_topology.php" method="post">
                    <input type= "hidden" name = "topology_type" id = "topology_type" value = "RandomTopology">
                    <!-- neurons_layer1 is passed to the next page and further to know whcih neurons are in the first input layer
                        For layered network, the inputs are only in the first layer -->
                    <input type ="hidden" name = "totalNeurons" id ="totalNeurons" value = <?php echo $totalNeurons; ?>>
                    <input type = "hidden" name = "neurons_layer1" id = "neurons_layer1" value = <?php echo $_POST['totalNeuronsEachLayer1']; ?>>
                     <input type="hidden" value=<?php echo $simNum; ?> name="simNum">
				<input type="submit" value="Next" action = "save_layered_topology.php"></form>
				
				<?php
				break; 			
				default:
				# code...
				break;
			}


			?>

		<h2>Network</h2>
		
		<br><br>
		<div id="network"></div>

		<table style="display:none;">
			<colgroup>
			<col width="1000px">
			<col width="1000px">
		</colgroup>
		<tr>
			<td>
				<h2>Nodes</h2>
				<pre id="nodes"></pre>
			</td>

			<td>
				<h2>Edges</h2>
				<pre id="edges"></pre>
			</td>
		</tr>
	</table>
	<br><br>
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

</div>
</div>
<?php
include("end_page.html")
?>