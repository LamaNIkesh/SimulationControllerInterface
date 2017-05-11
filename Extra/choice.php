<?php

#Direct to build.php page if the option chosen is build
if(isset($_POST['option']) && ($_POST['option']) == "build")
	{
		header("Location:build.php");
	}

#Direct to visualise.php page if the option chosen is visualise
elseif(isset($_POST['option']) && ($_POST['option']) == "visualise")
	{
		header("Location:visualise.php");
	}

#Direct to firstview.php page if neither of the 2 options are chosen and the user attempts to submit the form.
else
	{
		header("Location:home.php");
	}

?>