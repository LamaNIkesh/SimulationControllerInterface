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
                                        edges.add({id: n, from: to, to: from, arrows:'from'});
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
                              enabled:true,
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
                			?>
                			
                			<?php  
                            #using switch case to deal with different topology
                            #the nuerons are listed in a file.
                            #open the file and extract neuron details to form a topology
                			switch ($_POST['topologyType']) {
                				case 'fullyConnected':
             				# code...
                				?>
                				<p><h3>Fully Connected Topology</h3></p>

                				<?php 
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

                                //echo "layer1 ".$layer0[0];
                                //echo "no of layers: ".$no_of_layers;
                                //traversing only upto the second last layer.
                                //next layer is traversed at present layer
                                //echo "layer 1 size: ".count($layer1);
                                //print_r($layer1);

                                //check only upto second last array
                                //first iteration takes in 1st and 2nd array or layer so no need to 
                                //all the way up the last layer
                                $noOfconnections = 0;
                                $connection = array();
                                ?>
                                <input type="hidden" name=<?php echo "neuron". $totalNeurons; ?> id = <?php echo "neuron". $totalNeurons; ?> value=<?php echo "neuron". $totalNeurons; ?>>

                                <?php
                                for ($layer=0; $layer < $no_of_layers - 1; $layer++) { 
                                    # code...
                                    //echo "layer: ".$layer;
                                    //echo "layer size: ". count(${"layer$layer"});
                                    //traversing each array/layer
                                    //while exploding previously, it creates one extra element so that the 
                                    //size of 3 elements becomes 4. To eliminate that -1 is presented on the count
                                    //no of neurons in a layer
                                    for ($layernum=0; $layernum <count(${"layer$layer"})-1 ; $layernum++) { 

                                        $nextlayer = $layer + 1;
                                        fwrite($FullTopology,${"layer$layer"}[$layernum]);
                                        //echo "next layer size:".count(${"layer$nextlayer"});
                                        for ($j=0; $j <count(${"layer$nextlayer"})-1 ; $j++) { 
                                            # Here for each layer element, all the second layer elements are connected                                            
                                            $connection[$noOfconnections] = "neuron".${"layer$layer"}[$layernum]. "synapse".${"layer$nextlayer"}[$j];
                                            echo $connection[$noOfconnections];
                                            echo "<br>";
                                            
                                            fwrite($FullTopology, " ".${"layer$nextlayer"}[$j]);
                                      
                                            ?> 
                                            <!-- Passing neuron number to the javascript for visualisation -->

                                            <input type="hidden" id = '<?php echo "neuron".${"layer$layer"}[$layernum];?>' name = <?php echo "neuron".${"layer$layer"}[$layernum];?> value = <?php echo "neuron".${"layer$layer"}[$layernum]; ?>>

                                            <input type="hidden" id = '<?php echo $connection[$noOfconnections];?>' name = <?php echo $connection[$noOfconnections];?> value = <?php echo $connection[$noOfconnections];?>>

                                            <?php if($layer == $no_of_layers-2) {?>
                                            <?php  
                                            $lastlayer = $layer + 1; ?>
                                            <input type="hidden" id = '<?php echo "neuron".${"layer$lastlayer"}[$layernum];?>' name = <?php echo "neuron".${"layer$lastlayer"}[$layernum];?> value = <?php echo "neuron".${"layer$lastlayer"}[$layernum]; ?>>

                                            <?php } ?>

                                            <?php $noOfconnections++;
                                    }
                                    fwrite($FullTopology,"\n");
                                }
                                
                                }

                				
                                // print_r($layer0);
                                // print_r($layer1);
                                // print_r($layer2);
                                // print_r($layer3);
                                //echo "[2][2]".$layers_neurons[2][2];


                				//fclose($RandomTopology);
                				?><input type= "submit" method = "post" value = "Visualise network" onclick = "draw(2)">
                				<br><br>
                				<form action="save_layered_topology.php" method="post">
                				<input type="submit" value="Next" action = "save_layered_topology.php"></form>
                				<?php 
                				break;
                                //------------------------Random Network--------------
                				case 'randomlyConnected':
            				    #randomly connected network
                				?>
                				<p><h3>Random Topology</h3></p>

                				<?php 
                				//$randomTopo = fopen("SimulationXML/".$userLogged . "/Topology.txt", "w");    				
                                //randomly connected network
                				for ($number = 1; $number < $totalNeurons+1; $number++){
                					$name1='neuron'.$number;
                					$arrayIndex = $number - 1;
                                    //echo "First loop: ".$number;
                					?>
                					
                					<input type="hidden" name=<?php echo "nameid". $number; ?> id = <?php echo "nameid". $number; ?> value=<?php echo $name1; ?>>
                					<input type="hidden" name=<?php echo "neuron". $number; ?> id = <?php echo "neuron". $number; ?> value=<?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$arrayIndex]"); ?>>
                					<?php
                					for ($connect = 1; $connect < $totalNeurons+1; $connect++){
                						$name2='neuron'.$connect;
                						$arrayIndex2 = $connect - 1;
                						$randomConnection = mt_rand(1,$totalNeurons);
                						$randomDivider = mt_rand(1,$totalNeurons);
                                            //echo $randomConnection;
                                            //$fullyconnect[$connect] = "neuron" . $number . "synapse" . $connect;
                						$randomlyconnected[$connect] = "neuron" . $number . "synapse" . $randomConnection;
                						$connection = $number." ".$randomConnection;
                						if($connect == 1){
                						fwrite($RandomTopology, $connection);
                						}
                						else{
                							fwrite($RandomTopology," ".$randomConnection);
                						}
                                        //echo $randomlyconnected[$connect];
                						?> <input type="hidden" id = '<?php echo $randomlyconnected[$connect];?>' name = <?php echo $randomlyconnected[$connect];?> >


                						<?php
                						$connect = intval($connect + 2*$connect/((2/5)*$connect) + ($randomConnection/$randomDivider));
                					}
                					fwrite($RandomTopology,"\n");
                				}
                				fclose($RandomTopology);
                				?>
                				<input type= "submit" method = "post" value = "Visualise Network" onclick = "draw(2)">  
                				<br><br>
                				<form action="save_topology.php" method="post">
                				<input type="submit" value="Next" action = "save_topology.php"></form>
                				
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