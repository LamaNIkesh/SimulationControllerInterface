<?php
include("head_admin.html")
?>

<?php 
//function to update the database
function CreateModelTabel($modelId,$modelName,$noOfPara, $url){
  //create connection
  $server = 'localhost';
  $user = 'root';
  $pass = '';
  $db = 'WebInterface';

  try{
      $connection = mysqli_connect("$server",$user,$pass,$db);
      
      //$itemid = mysqli_real_escape_string($connection,$itemid);
      $modelId = mysqli_real_escape_string($connection,$modelId);
      $modelName = mysqli_real_escape_string($connection,$modelName);
      $noOfPara = mysqli_real_escape_string($connection,$noOfPara);
      $url = mysqli_real_escape_string($connection,$url);

      //All the new models are inserted into ModelLibrary table which has three columns:modelId, modelname and URL for .sof location for FPGA

      $insertData = "INSERT INTO ModelLibrary (ModelID, ModelName, NoOfPara, LocationURL) 
                    VALUES ('$modelId', '$modelName','$noOfPara','$url')";
      if($connection->query($insertData)){
        echo "New record created successfully";
      }
      else{
        echo "ERROR: ".$insertData."<br>".$connection->error;
      }
    }
    catch (Exception $e){
      echo "ERROR!!!!!!!";
    }
}
//end of function
//run the function when the submit button is clicked...


if(isset($_POST['modelName'])){
  echo "pressed....";
  //pass the user variable into the function
  CreateModelTabel($_POST['modelID'],$_POST['modelName'],$_POST['noOfPara'],$_POST['url']);
}

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
          You have successfully created new model. Now add the parameters. 
        </h3>
        <hr>

        <form method="POST" action="admin_add_models_parameters.php">

          <!-- Passing these variables to the next page for adding parameters into the table --> 

          <input type = "Hidden" name = "modelID" value = <?php echo $_POST['modelID']; ?> >

          <input type = "Hidden" name = "modelName" value = <?php echo $_POST['modelName']; ?> > 

          <input type = "Hidden" name = "noOfPara" value = <?php echo $_POST['noOfPara']; ?> >  

          <input type = "Hidden" name = "url" value = <?php echo $_POST['url']; ?> >

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