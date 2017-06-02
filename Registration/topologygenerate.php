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
        	try {s
        		var sen='#66FF00';
        		var inter='#FFsFF00';
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
            dcoument.write(neuron);
            //document.write(neuron);
            //var muscle = document.getElementById("muscle").value;
            nodes.on('*', function () {
            	document.getElementById('nodes').innerHTML = JSON.stringify(nodes.get(), null, 4);
            });
            var counter2=1;
            //creates nodes
            for (i=1; i<=neuron; i++){	
            	var n = i.toString();
            	var lab1="neuron";
            	var lab2=lab1.concat(n);
            	var lab = document.getElementById(lab2).value;
                //document.write(lab);
                nodes.add([
                	{id:counter2, label: lab, color:'#99CC33 '}]);
                counter2=counter2+1;
            }


            // create an array with edges
            edges = new vis.DataSet();

            edges.on('*', function () {
            	document.getElementById('edges').innerHTML = JSON.stringify(edges.get(), null, 4);
            });
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

            				str1 = "neuron";
            				str2 = "synapse";
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
    var str1 = "neu";
    var str2 = "syn";
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
                         //var random = Math.floor(Math.random() * neuron);

                         /*var from = i.toString();
                         var random = Math.floor(Math.random() * (max - min + 1)) + min;
                         var to = random.toString(); //using random neuron to connect to 
                         var connection = str1.concat(from,str2,to);
                         var randomDivider = Math.floor(Math.random() * (max - min + 1)) + min; // this random will be used to divide the 
                                                                                                //previously generated number to have random
                                                                                                //number of connections, otherwise there will always
                                                                                                //be equal number of connections
                        var y = document.getElementById(connection);
                         //fh.WriteLine(from + ' ' + to);
                         
                         //document.write(y);
                         if(y.checked == true){
                            //document.getElementById('connection').innerHTML = y;
                            var n = counter.toString();
                            edges.add({id: n, from: to, to: from, arrows:'to'});
                            counter=counter+1;
                        }
                        j = Math.round(j + neuron/5+ (random/randomDivider));
                        */                          
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
            var options = {interaction:{
            	dragNodes:true,
            	dragView: true,
            	hideEdgesOnDrag: true,
            	hideNodesOnDrag: false,
            	hover: true,
            	hoverConnectedEdges: true,
            	keyboard: {
            		enabled: true,
            		speed: {x: 10, y: 10, zoom: 0.02},
            		bindToWindow: true
            	},
            	multiselect: false,
            	navigationButtons: false,
            	selectable: true,
            	selectConnectedEdges: true,
            	tooltipDelay: 300,
            	zoomView: true
            }
        };

        network = new vis.Network(container, data, options);
        network.setOptions(options);
    }



</script> <!-- custom js to visualise topology-->

<!-- end of script-->

<div class = "container">
	<div class="col-sm-12">

		<p><h3>Create Topology</h3></p>

		<p id = "i"></p>

		<?php
		if ($_SESSION['flag']==1){
			$neuronlistPath = "SimulationXML/".$userLogged . "/NeuronList.txt";
			$list=file($neuronlistPath);

                    //echo $list[9];
			$totalNeurons=$_POST['totalNeurons'];
                    //echo $_POST['totalNeurons'];
			$num = 1;
                    //echo "neuron 1 is ".$_POST['neuron'.$num];
                    //Fully interconnected
			?>
			<br>

			<?php 

			if($_POST['topologyType'] == "fullyConnected"){
				?>
				<input type= "submit" method = "post" value = "View Fully Connected Network" onclick = "draw(1)">  
				<input type="hidden" name="neuron" id = "neuron" value=<?php echo $_POST['totalNeurons']; ?>>
				<?php 
				$myfile = fopen("SimulationXML/".$userLogged . "/Topology.txt", "w"); 
				for ($number = 1; $number < $totalNeurons+1; $number++){
					$name1='neuron'.$number;
					$arrayIndex = $number - 1;
					?>
					
					<input type="hidden" name=<?php echo "nameid". $number; ?> id = <?php echo "nameid". $number; ?> value=<?php echo $name1; ?>>
					<input type="hidden" name=<?php echo "neuron". $number; ?> id = <?php echo "neuron". $number; ?> value=<?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$arrayIndex]"); ?>>
					<?php

					for ($connect = 1; $connect < $totalNeurons+1; $connect++){
						$name2='neuron'.$connect;
						$arrayIndex2 = $connect - 1;
						$fullyconnect[$connect] = "neuron" . $number . "synapse" . $connect;
						$connection = $number." ".$connect;
						fwrite($myfile, $connection."\n");
                        //the format or id will be something like neuron1synapse4 and it will change with each iteration
                                //echo $fullyconnect[$connect];
						?> <input type="hidden" id = '<?php echo $fullyconnect[$connect];?>' name = <?php echo $fullyconnect[$connect];?>>

						<?php
					}
				}
				fclose($myfile);	

			}
		//elseif($_POST['topologyType'] == "randomlyConnected"){
			?>	
			<input type= "submit" method = "post" value = "View Network" onclick = "draw(2)">   
            <input type="hidden" name="neuron" id = "neuron" value=<?php echo $_POST['totalNeurons']; ?>> 
			<?php 
                    //randomly connected network
			$randomTopo = fopen("SimulationXML/".$userLogged . "/RandomTopology.txt", "w"); 
			for ($number = 1; $number < $totalNeurons+1; $number++){
				$name1='neuron'.$number;
				$arrayIndex = $number - 1;
                        //echo "First loop: ".$number;
				?>
				<!--
				<input type="hidden" name=<?php echo "nameid". $number; ?> id = <?php echo "nameid". $number; ?> value=<?php echo $name1; ?>>
                -->
				<input type="hidden" name=<?php echo "neuron". $number; ?> id = <?php echo "neuron". $number; ?> value=<?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$arrayIndex]"); ?>>
				
                <?php
				for ($connect = 1; $connect < $totalNeurons+1; $connect++){

					$name2='neuron'.$connect;
					$arrayIndex2 = $connect - 1;
					$randomConnection = mt_rand(1,$totalNeurons);
					$randomDivider = mt_rand(1,$totalNeurons);

                                //echo $randomConnection;
                                //$fullyconnect[$connect] = "neuron" . $number . "synapse" . $connect;
					$randomlyconnected[$connect] = "neu" . $number . "syn" . $randomConnection;
                            //echo $randomlyconnected[$connect];
					$connection = $number." ".$randomConnection;
					fwrite($randomTopo, $connection."\n");

					?> <input type="hidden" id = '<?php echo $randomlyconnected[$connect];?>' name = <?php echo $randomlyconnected[$connect];?> >


					<?php
					$connect = intval($connect + 2*$connect/((2/5)*$connect) + ($randomConnection/$randomDivider));
				} 
			}
			fclose($randomTopo);
		//}
			?>

			<h2>Network</h2>
			<button onclick="draw(1)">See network</button>
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
