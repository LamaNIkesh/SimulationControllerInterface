<?php
include('head.html')
?>


<?php echo $_SESSION['loginfail'] ?>
<div class = "container">
  <div class="wrapper">
    <form class="form-signin" action = "loginProcess.php" method = "post">
    <input type="hidden" value="UserLogin" name="LoginType">       
      <h2 class="form-signin-heading">Please login</h2>
      <?php 
      if($_SESSION['loginfail'] == 1)
        {?>
	      <p>Login Failed!!Please check your username and password</p>
      <?php } 
      $_SESSION['loginfail'] = 0;?>
      <input type="text" class="form-control" name="username" placeholder="Username" required="" autofocus=""/>
      <input type="password" class="form-control" name="password" placeholder="Password" required="" />      
      <label class="checkbox">
        <input type="checkbox" value="remember-me" id="rememberMe" name="rememberMe"> Remember me
      </label>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
      <br>
      <a href="admin_login.php">Login as admin  </a> <img src="img/admin.png" width = "25" height = "25" margin-top = "100px">
      
    </form>

  </div>
</div>
<?php
include("end_page.html")
?>