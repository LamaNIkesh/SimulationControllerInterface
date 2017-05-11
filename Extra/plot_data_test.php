<?php
include("head.html")
?>

<head>
  <!-- Plotly.js -->
  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
</head>

<p>
    Now I just need to load the actual data and create the vectors...
</p>
<div id="spikes"></div>
<br><br>


<script>
var data = [{
	  name: "ADAR",
      y: ["ADAR","ADAR","ADAR","ADAR"],
      x: [1, 2, 3, 4],
      line: { width: 0 },
	  marker: { 
		symbol: 142,
		color: 'rgb(16, 32, 77)'
		},
	  uid: "40abaa"},
		
	  {
	  name: "ADAL",
	  y: ["ADAL","ADAL"],
      x: [2, 4],
      line: { width: 0 },
	  marker: { 
		symbol: 142,
		color: 'rgb(16, 32, 77)'
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
        // tickformat: "%B, %Y"              // customize the date format to "month, day"
      },
      // margin: {                           // update the left, bottom, right, top margin
        // l: 40, b: 10, r: 10, t: 20
      // }
    };

    Plotly.plot(document.getElementById('spikes'), data, layout, {displaylogo: false});
    
</script>



<?php
include("end_page.html")
?>