<?php
include("head_admin.html")
?>

<?php 
if ($_SESSION['flag']==1){
    ?>

    <div class = "container">
      <div class="col-sm-12">
        <h6><font color = "#52a25e">Admin Model Configuration-><b>Specify Model</b></h6></font>
        <h4>
          Please specify each paramter for your <?php echo $_POST['modelName']; ?> Neuron Model .
        </h4><br> 
        <h5> 
          There are <?php echo $_POST['noOfPara']; ?> parameters
        </h5>
        <hr>      

        <form method="POST" action="#">

          <div class= "col-sm-3">
            Item ID: </div><input type="text" name="modelName" placeholder = "eg.LIF" required>
            <br>
            <br>
          <div class= "col-sm-3">
            Name: </div><input type="text" name="modelName" placeholder = "eg.LIF" required> 
            <br>
            <br> 
          <div class= "col-sm-3">
            Type: </div><input type="text" name="modelName" placeholder = "eg.LIF" required>
            <br>
            <br>
          <div class= "col-sm-3">
            Datatype: </div><input type="text" name="modelName" placeholder = "eg.LIF" required>
            <br>
            <br>
          <div class= "col-sm-3">
            Integer Part: </div><input type="text" name="modelName" placeholder = "eg.LIF" required> 
            <br>
            <br> 
          <div class= "col-sm-3">
            Typical Value: </div><input type="text" name="modelName" placeholder = "eg.LIF" required>
            <br>
            <br>
          <div class= "col-sm-3">
            In LSB: </div><input type="text" name="modelName" placeholder = "eg.LIF" required>
            <br>
            <br>
          <div class= "col-sm-3">
            In MSB: </div><input type="text" name="modelName" placeholder = "eg.LIF" required> 
            <br>
            <br> 
          <div class= "col-sm-3">
            Out LSB: </div><input type="text" name="modelName" placeholder = "eg.LIF" required>
            <br>
            <br>
          <div class= "col-sm-3">
            Out MSB: </div><input type="text" name="modelName" placeholder = "eg.LIF" required>
            <br>
            <br>
          

            
                

                  
          <div class= "col-sm-3">
            <input type="submit" value="Next" name="submit" required>
            <br><br>
          </div>

      </div>
    </div>
<?php
}
else{
  ?>
  <div class = "container">
    <div class="col-sm-12">
      <p>You need to log in to see this page:</p>
      <form action="login.php" method="post">
        <input type="submit" value="Log in">
      </form>
      <br><br>
      <?php } ?>
    </div>
  </div>



 
<?php
include("end_page.html")
?>