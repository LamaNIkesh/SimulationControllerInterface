<?php
include("head_admin.html")
?>

<?php 

if ($_SESSION['flag']==1){
    ?>

    <!-- Add/Remove/Edit models. Each model can have number of paramters and each parameter can be described
          with 10 sub parameters....The metadata for each parameter is given as
          <item>
            <itemid>1</itemid>
            <name>absolute_refractory_period</name>
            <type>1</type><datatype>16</datatype>
            <integerpart>8</integerpart>
            <typicalvalue>5.0</typicalvalue>
            <inlsb>0</inlsb>
            <inmsb>31</inmsb>
            <outlsb>0</outlsb>
            <outmsb>0</outmsb>
          </item>
          
          This is for one parameterer, these are then stored in the database.
    -->


    <div class = "container">
      <div class="col-sm-12">
        <h6><font color = "#52a25e">Admin Model Configuration-><b>Specify Model</b></h6></font>
        <h3>
          Welcome! You can add/delete/edit neuron models in the model library
        </h3>
        <hr>
        
        <b><p>Please specify neuron model name<p></b>


        <form method="POST" action="admin_add_models_dbUpdate.php">

          <div class= "col-sm-3">
            Model Name: </div><input type="text" name="modelName" placeholder = "eg.LIF" required><br>
            <br>
          <div class= "col-sm-3">
            Model ID: </div><input type="text" name="modelID" placeholder = "eg.1" required><br>
            <br>
          <div class= "col-sm-3">
            No of Parameters: </div><input type="number" name="noOfPara" min="1" placeholder = "1" required>
            <br><br>
          <div class= "col-sm-3">
            .sof url: </div><input type="text" name = "url" placeholder="://url" min="1" max="1000" required>
            <br><br>
            <div class= "col-sm-3">
            Filename: </div><input type="text" name = "filename" placeholder="eg:phototaxis" min="1" max="1000" required>
            <br><br>
                  
          <div class= "col-sm-3">
            <input type="submit" value="Next" name="submit" required>


            <br><br>
          </div>
         </form> 
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