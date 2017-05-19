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

        function draw() {
            // create an array with nodes
            nodes = new vis.DataSet();
            var neuron = document.getElementById("neuron").value;
            var muscle = document.getElementById("muscle").value;
            nodes.on('*', function () {
            	document.getElementById('nodes').innerHTML = JSON.stringify(nodes.get(), null, 4);
            });
            var counter2=1;
            for (i=1; i<=neuron; i++){	
            	var n = i.toString();
            	var lab1="name";
            	var lab2=lab1.concat(n);
            	var lab = document.getElementById(lab2).value;
            	nodes.add([
            		{id:counter2, label: lab, color:'#FFFF00'}]);
            	counter2=counter2+1;
            }
            for (i=1; i<=muscle; i++){	
            	var n = i.toString();
            	var lab1="musclename";
            	var lab2=lab1.concat(n);
            	var lab = document.getElementById(lab2).value;
            	n2 = n.concat("muscle");
            	nodes.add([
            		{id:n2, label: lab, color:'#00FFFF'}]);
            	counter2=counter2+1;
            }

            // create an array with edges
            edges = new vis.DataSet();
            edges.on('*', function () {
            	document.getElementById('edges').innerHTML = JSON.stringify(edges.get(), null, 4);
            });
            var counter=1;
            try {
            	for (i=1; i<=neuron; i++){
            		for (j=1; j<=neuron; j++){
            			var str1 = "neuron";
            			var str2 = "synapse";
            			var from = i.toString();
            			var to = j.toString();
            			var check = str1.concat(from,str2,to);
            			var x = document.getElementById(check);
            			if(x.checked==true){
            				var n = counter.toString();
            				edges.add({id: n, from: to, to: from, arrows:'to'});
            				counter=counter+1;
            			}
            		}
            	}
            }
            catch (err) {
            	alert(err);
            }
            try {
            	for (i=1; i<=neuron; i++){
            		for (j=1; j<=muscle; j++){
            			var str1 = "muscle";
            			var str2 = "synapse";
            			var from = i.toString();
            			var to1 = j.toString();
            			var to = to1.concat("muscle");
            			var check = str1.concat(to1,str2,from);
            			var x = document.getElementById(check);
            			if(x.checked==true){
            				var n = counter.toString();
            				edges.add({id: n, from: from, to: to, arrows:'to'});
            				counter=counter+1;
            			}
            		}
            	}
            }
            catch (err) {
            	alert(err);
            }

            // create a network
            var container = document.getElementById('network');
            var data = {
            	nodes: nodes,
            	edges: edges
            };
            var options = {};
            network = new vis.Network(container, data, options);
        }
        </script>

<!-- end of script-->


        <div class = "container">
        	<div class="col-sm-12">


        		<?php
        		if ($_SESSION['flag']==1){
        			$list=file("Libraries/neuron_id.txt");
        			$neurons=$_POST['neuron'];
        			
        			for ($number = 1; $number < $neurons+1; ++$number){
        				$name1=$_POST['name'.$number];
        				?>

        				<p>Neuron <?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$name1]"); ?> receives synapses from: </p>
        				<form action="save_topology.php" method="post">
        					<input type="hidden" name="neuron" id = "neuron" value=<?php echo $_POST['neuron']; ?>>
        					<input type="hidden" value=<?php echo $_POST['muscle']; ?> id="muscle" name="muscle">
        					<input type="hidden" name=<?php echo "nameid". $number; ?> id = <?php echo "nameid". $number; ?> value=<?php echo $name1; ?>>
        					<input type="hidden" name=<?php echo "name". $number; ?> id = <?php echo "name". $number; ?> value=<?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$name1]"); ?>>
        					<?php
        					for ($connect = 1; $connect < $neurons+1; ++$connect){
        						$name2=$_POST['name'.$connect];
        						?>
        						<input type="checkbox" id=<?php echo "neuron" . $number . "synapse" . $connect; ?> name=<?php echo "neuron" . $number . "synapse" . $connect; ?> />
        						<?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$name2]"); ?>  <br>
        						<?php
        					}

        					?>
        					<br>
        					<?php	
        				}
        				for ($number = 1; $number < $muscle+1; ++$number){
        					$name3=$_POST['musclename'.$number];
        					?>
        					<p>Muscle <?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list2[$name3]"); ?> receives synapses from: </p>
        					<input type="hidden" name=<?php echo "musclenameid". $number; ?> id = <?php echo "musclenameid". $number; ?> value=<?php echo $name3; ?>>
        					<input type="hidden" name=<?php echo "musclename". $number; ?> id = <?php echo "musclename". $number; ?> value=<?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list2[$name3]"); ?>>
        					<?php
        					for ($connect2 = 1; $connect2 < $neurons+1; ++$connect2){
        						$name4=$_POST['name'.$connect2];
        						?>
        						<input type="checkbox" id=<?php echo "muscle" . $number . "synapse" . $connect2; ?> name=<?php echo "muscle" . $number . "synapse" . $connect2; ?> />
        						<?php echo preg_replace("/[^a-zA-Z0-9]+/", "", "$list[$name4]"); ?>  <br>
        						<?php
        					}

        					?>
        					<br>
        					<?php	
        				}
        				?>
        				<br><input type="submit" value="Next">
        			</form><br><br>

        			<h2>Network</h2>
        			<button onclick="draw()">See network</button>
        			<br><br>
        			<div id="network"></div>


        			<table style="display:none;">
        				<colgroup>
        				<col width="600px">
        				<col width="600px">
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
