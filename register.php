<?php
include("head.html")
?>

<div class ="container">
<h1>Register</h1>
<form action="validation.php" method="post" name="test1">
<fieldset>
<legend>Create account</legend>
<input type='hidden' name='submitted' id='submitted' value='1'/>
<br>User name:<br><input type="text" name="user" required><br><br>
Password:<br><input type="password" name="pass" required><br><br>
Confirm password:<br><input type="password" name="passconf" required><br><br>
Email address:<br><input type="text" name="email" required><br><br>
<input type="submit">
</fieldset>
</form>
<br><br>
</div>
<?php
include("end_page.html")
?>