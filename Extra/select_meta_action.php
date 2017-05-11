<?php
include("head.html")
?>
<?php
if(isset($_POST['submit']))
{
		if ($_FILES['uploadfile2']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

//Check that a file was uploaded
if($_FILES['uploadfile2']["size"]>0)
	{
		$ext = pathinfo($_FILES['uploadfile2']['name'], PATHINFO_EXTENSION);
		if ($ext == 'xml')
		{
		//Get the name of the uploaded file
		$file = pathinfo($_FILES['uploadfile2']['name'], PATHINFO_FILENAME);

		//Change the name of the uploaded file to 'ModelLibrary_metadata.xml'
		$temp = explode (".", $_FILES["uploadfile2"]["name"]);
		$newfilename = "ModelLibrary_metadata". '.' . end($temp);		

		//Attempt to move the uploaded file to it's new place. If it's successfully, data in the file gets plotted
		if(move_uploaded_file($_FILES['uploadfile2']['tmp_name'], $newfilename))
		{
			echo "The file " .  $newfilename . " has been successfully uploaded";
?>
		<form action="input_parameters.php" method="POST">
		<br>
		<input type="submit" value="Proceed to input details" name="submit">
		</form>
		<br><br>

<?php
		}
			
		//if no file was uploaded or an error occurred in the process and the file s not successfullty saved, redirects to allow user upload file again
		else
		{
			echo "There was an error uploading the file, please try again!";
?>
		<form action="select_meta.php" method="POST">
		<br>
		<input type="submit" value="Try again" name="submit">
		</form>
		<br>
		<form action="firstview.php" method="POST">
		<input type="submit" value="Cancel" name="submit">
		</form>
		<br>
<?php
		}

		}
		else{
			?>
		<p>The file is the wrong format.</p>
		<form action="select_meta.php" method="POST">
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
	else{
		?>
		<p>There was an error. Please try again.</p>
		<form action="select_meta.php" method="POST">
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


include("end_page.html")
?>