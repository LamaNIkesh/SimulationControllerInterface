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
</script>

