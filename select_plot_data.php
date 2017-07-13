<?php
include("head.html")
?>
<div class = "container">
<div class="col-md-12">
<?php
$flag=0;
if ($_SESSION['flag']==1){

	?>

<h3> Choose the file to plot </h3>
<p> Remember to choose the XML results containing the timestamp and the spikes </p>
<p> The file has to be in your profile folder. </p>
<hr>  
<input type="file" id="file-input" />
<hr>
<br>
<p> If the file chosen is correct, plot the results. </p>
	<form action="plot_results.php" method="post">
	<input type="hidden" name="plotfile" id = "plotfile" value=""/>
	<input type="submit" value="Plot the results">
	</form>	
	<br><br>
	
<script type="text/javascript">
function readSingleFile(e) {
  var file = e.target.files[0];
  if (!file) {
    return;
  }
  var reader = new FileReader();
  var name = file.name;
  reader.onload = function(e) {
    var contents = e.target.result;
    //displayContents(name);
	document.getElementById('plotfile').value = name;
  };
  reader.readAsText(file);
}

document.getElementById('file-input')
  .addEventListener('change', readSingleFile, false);

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