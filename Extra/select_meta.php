<?php
include("head.html")
?>

<form action="select_meta_action.php" enctype="multipart/form-data" method="POST">
	Select metadata file
	<br><br>
	<input type="file" name="uploadfile2">
	<br>
	<br>
	<input type="submit" value="Upload file" name="submit">


</form>
<br><br>
<?php
include("end_page.html")
?>