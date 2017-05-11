<?php
include("head.html")
?>
<?php
$flag=0;
if (file_exists ("Libraries/database.txt")){
$data= file("Libraries/database.txt");
for ($line = 0; $line < count($data); ++$line){
	$userData=explode(" ",$data[$line]);
	if ($userData[3]=="1"){
		$flag=1;
		$userLogged=$userData[0];
	}
}
}
if ($flag==1){
	?>
<h1> Spike Train Results</h1>
<p> 
	The spike trains from the file <?php echo $_POST["plotfile"]; ?> are plotted. If no results are seen, go back and check that the file uploaded was right.
</p>
<p id="demo1"></p>
<div id="spikes"></div>
<br><br>

<script>
var filename ='<?php echo  $userLogged . "/" . $_POST["plotfile"] ; ?>';
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
	for (j = 0; j < zlen; j++) {
    x = xmlDoc.getElementsByTagName("item")[j];
    xlen = x.childNodes.length;
    y = x.firstChild;
    txt = "";
    for (i = 0; i < xlen; i++) {
        if (y.nodeType == 1) {
			if (y.nodeName == "timestamp") {
				var z = x.getElementsByTagName(y.nodeName)[0].childNodes[0].nodeValue;
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

var test4 = [];
for (var i = 0; i < test.length; i++) {
	var name = xmlDoc2.getElementsByTagName("Column1")[parseInt(test[i])-1].childNodes[0].nodeValue;
	test4.push([name, test2[i]]);
	}

var test5 = test4.sort();	

var y1 = [];
var x1 = [];

for (var i = 0; i < test5.length; i++) {
	y1.push(test5[i][0]);
	x1.push(test5[i][1]);
	}
	
var data = [{
	  name: "Spikes",
      y: y1,
      x: x1,
      mode: 'markers',
	  marker: { 
		symbol: 142,
		color: 'rgba(255,0,255,1)',
		//size: 500
		},
	  uid: "40abaa"
	  }];
    var layout = {
	  showlegend: false,
	  title: "Spike trains",
      yaxis: { 
		showgrid: false
	   },      // set the y axis title
      xaxis: {
		title: "Timestamp",
        showgrid: false,                  // remove the x-axis grid lines
		zeroline: false
       },
	  hovermode: 'closest',
	  paper_bgcolor: "rgba(255,0,255,0.01)",
	  plot_bgcolor: "rgba(255,0,255,0.01)"
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
<br><br>
<?php
}
?>
<?php
include("end_page.html")
?>