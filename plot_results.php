<?php
include("head.html")
?>

<div class = "container">
<div class="col-md-12">
<?php
$flag=0;
if ($_SESSION['flag']==1){
	
	$userlogged = $_SESSION['username'];
	?>
<h1> Spike Train Results</h1>
<p> 
	The spike trains from the file <?php echo $_POST["plotfile"]; ?> are plotted. If no results are seen, go back and check that the file uploaded was right.
</p>
<p id="demo1"></p>
<div id="spikes"></div>
<br><br>

<script>
var filename ='<?php echo  "SimulationXML/".$userlogged ."/Result/". $_POST["plotfile"] ; ?>';
//var filename = "ACM/Spike_train_ACM1.xml";
var xhttp = new XMLHttpRequest();
var xhttp2 = new XMLHttpRequest();
xhttp2.open("GET", "Libraries/neuron_id.xml", true);
xhttp2.send();
xhttp.open("GET", filename, true);
xhttp.send();
xhttp.onreadystatechange = function() {
	if (xhttp.readyState == 4 && xhttp.status == 200 && xhttp2.readyState == 4 && xhttp2.status == 200) {
		myFunction(xhttp, xhttp2);
	}
};
function myFunction(xml1, xml2) {

    var x, y, i, xlen, xmlDoc, txt;
    xmlDoc = xml1.responseXML;
	xmlDoc2 = xml2.responseXML;
	var test = [];
	var test2 = [];
	var zlen = xmlDoc.getElementsByTagName("item").length;
	//console.log(zlen);
	for (j = 0; j < zlen; j++) {
	    x = xmlDoc.getElementsByTagName("item")[j];
	    //console.log(x);
	    xlen = x.childNodes.length;
	    y = x.firstChild;
	    //console.log(xlen);
	    //console.log("length of x ",xlen);
	    txt = "";

	    for (i = 0; i < xlen; i++) {
	        if (y.nodeType == 1) {
	        	//console.log("node type: ", y.nodeType);
				if (y.nodeName == "timestamp") {
					var z = x.getElementsByTagName(y.nodeName)[0].childNodes[0].nodeValue;
					//console.log(z);
					}
				else{
					var z = x.getElementsByTagName(y.nodeName)[(i-1)/2-1].childNodes[0].nodeValue;
					test.push(z);
					test2.push(x.getElementsByTagName("timestamp")[0].childNodes[0].nodeValue);
					}
	            txt += i + " " + y.nodeName + " " + z + "<br>";
	        }
	        y = y.nextSibling;
	    }
	}
//console.log(txt);z
console.log("test",test);

function getMaxOfArray(numArray) {
  return Math.max.apply(null, numArray);
}

console.log("max: ", getMaxOfArray(test));
var test4 = [];
console.log("test.length", test.length)
for (var i = 0; i < test.length; i++) {
	console.log(test2[i]);
	//the neuron name would be the same as the name
	var name = test[i];
	console.log("name:",name);

	test4.push([name, test2[i]]);
	}
	console.log("sorted: ",test)
console.log("Test4:",test4);
var test5 = test4.sort();	
console.log("length: ",test5.length);
var y1 = [];
var x1 = [];

for (var i = 0; i < test5.length; i++) {
	y1.push(test5[i][0]);
	x1.push(test5[i][1]);
	}
	console.log(y1)
console.log(x1)
var data = [{
	  name: "Spikes",
      y: y1,
      x: x1,
      mode: 'markers',
	  marker: { 
		symbol: 142,
		color: 'rgba(0,0,100,1)',
		//size: 500
		},
	  uid: "40abaa"
	  }];
    var layout = {
	  showlegend: false,
	  title: "Spike trains",
      yaxis: { 
      	title: "Neuron Numbers",
      	showline:true,
		showgrid: true
	   },      // set the y axis title
      xaxis: {
		title: "Timestamp",
        showgrid: false,                  // remove the x-axis grid lines
		zeroline: false
       },
	  hovermode: 'closest',
	  margin: {                           // update the left, bottom, right, top margin
        l: 40, b: 40, r: 10, t: 60
      },
	  paper_bgcolor: "rgba(255,0,255,0.01)",
	  plot_bgcolor: "rgba(0,0,255,0.01)"
    };
    Plotly.plot(document.getElementById('spikes'), data, layout, {displaylogo: false});
}
</script>

<?php
}
else{
	?>
	<p>You need to log in to see this page:</p>
<form action="login.php" method="post">

<input type="submit" value="Log in">
</form>
<br><br><br><br><br><br><br>
<?php
}
?>
<?php
include("end_page.html")
?>