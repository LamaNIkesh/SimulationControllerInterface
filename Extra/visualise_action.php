<?php
include("head.html")
?>

<?php
if(isset($_POST['submit']))
{

	if ($_FILES['uploadfile']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }
//Check that a file was uploaded
	if($_FILES['uploadfile']["size"]>0)
	{	//Get the name of the uploaded file
		$name = $_FILES['uploadfile']['name'];
		//Change the name of the uploaded file to 'plot_data.xml'
		$temp = explode (".", $_FILES["uploadfile"]["name"]);
		$newfilename = "plot_data". '.' . end($temp);

		//Attempt to move the uploaded file to it's new place. If it's successfully, data in the file gets plotted
		if(move_uploaded_file($_FILES["uploadfile"]["tmp_name"],$newfilename))
		{
			echo "The file has been successfully uploaded";
?>
		<form action="spike_plot.php" method="POST">
		<br>
		<input type="submit" value="Proceed to visualise result" name="submit">
		</form>
<?php
		}
	}
		//if no file was uploaded or an error occurred in the process and the file s not successfullty saved, redirects to allow user upload file again
	else
		{
		echo "There was an error uploading the file, please try again!";
?>
		<form action="visualise.php" method="POST">
		<br>
		<input type="submit" value="Try again" name="submit">
		</form>
		<br>
		<form action="logged2.php" method="POST">
		<input type="submit" value="Cancel" name="submit">
		</form>
<?php
		}
}

?>
<br><br>
<?php
include("end_page.html")
?>