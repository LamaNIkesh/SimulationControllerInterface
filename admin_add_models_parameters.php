<?php
include("head_admin.html")
?>



<?php 
//sanity check
//echo $_POST['modelName'];

//lets create a counter variable that counts the number of parameters that have been created...


//function to update the database
function databaseUpdate($modelId,$name,$typicalval){
  //create connection
  $server = 'localhost';
  $user = 'root';
  $pass = 'cncr2018';
  $db = 'WebInterface';

  try{
      $connection = mysqli_connect("$server",$user,$pass,$db);
      
      //$itemid = mysqli_real_escape_string($connection,$itemid);
      $modelId = mysqli_real_escape_string($connection,$modelId);
      $name = mysqli_real_escape_string($connection,$name);
      $typicalval = mysqli_real_escape_string($connection,$typicalval);

      //echo $_POST['modelName'];
      //checkign the table exists or not, if the table with model name already exists, we simply update the parameters
      $sql = "SHOW TABLES LIKE '".$_POST['modelName']."'";
      if(mysqli_num_rows(mysqli_query($connection, $sql)) == 1){
        //echo "Table exists..";
      }
      else{
        $createTable = "CREATE TABLE ".$_POST['modelName']." (
        ItemID INT(10) AUTO_INCREMENT PRIMARY KEY,
        ModelID varchar(100) NOT NULL,
        Name VARCHAR(30) NOT NULL,
        TypicalVal FLOAT(10) NOT NULL,
        FOREIGN KEY (ModelID) REFERENCES ModelLibrary(ModelID) ON DELETE CASCADE
        )";
        if($connection->query($createTable) == TRUE){
          echo "Table created successfully......";
        }
        else{
          echo "Error creating table:".$connection->error;
        }
       }                                                     //since item id is autoincremented no need to explicitly insert into database


      $insertData = "INSERT INTO ".$_POST['modelName']." (ModelID,Name, TypicalVal) 
                    VALUES ('$modelId','$name','$typicalval')";
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



if(isset($_POST['name'])){
    databaseUpdate($_POST['modelID'],$_POST['name'], $_POST['typicalval']);
}



if ($_SESSION['flag']==1){
    ?>
    <div class = "container">
      <div class="col-sm-12">
        <h6><font color = "#52a25e">Admin Model Configuration-><b>Specify Model</b></h6></font>

        <?php 
          if ($_POST['noOfPara']>0){
              //checks if there are any parameter that needs to be inserted
              //if all the parameteres are created, show a message and go back to home
         ?>

        <h4>
          Please specify each paramter for your <?php echo $_POST['modelName']; ?> Neuron Model .
        </h4><br> 
        <h5> 
          There are <?php echo $_POST['noOfPara']; ?> parameters
        </h5>
        <hr>      

        <form method="POST" action="#">
          <!-- 
          <div class= "col-sm-3">
            Item ID: </div><input type="text" name="itemid" placeholder = "itemID" required>
            <br>
            <br>
          -->
          <div class= "col-sm-3">
            Name: </div><input type="text" name="name" placeholder = "eg.threshold" required> 
            <br>
            <br> 
            <!--
          <div class= "col-sm-3">
            Type: </div><input type="text" name="type" placeholder = "eg.1" required>
            <br>
            <br>
          <div class= "col-sm-3">
            Datatype: </div><input type="text" name="datatype" placeholder = "eg.16" required>
            <br>
            <br>
          <div class= "col-sm-3">
            Integer Part: </div><input type="text" name="integerpart" placeholder = "eg.8" required> 
            <br>
            <br> 
          -->

          <div class= "col-sm-3">
            Typical Value: </div><input type="text" name="typicalval" placeholder = "eg.6.0" required>
            <br>
            <br>
          <!--
          <div class= "col-sm-3">
            In LSB: </div><input type="text" name="inlsb" placeholder = "eg.0" required>
            <br>
            <br>
          <div class= "col-sm-3">
            In MSB: </div><input type="text" name="inmsb" placeholder = "eg.31" required> 
            <br>
            <br> 
          <div class= "col-sm-3">
            Out LSB: </div><input type="text" name="outlsb" placeholder = "eg.0" required>
            <br>
            <br>
          <div class= "col-sm-3">
            Out MSB: </div><input type="text" name="outmsb" placeholder = "eg.0" required>
            <br>
            <br>
          -->       
          <div class= "col-sm-3">
          <input type="hidden" name="modelName" value=<?php echo $_POST['modelName']; ?>>
          <input type="hidden" name="modelID" value=<?php echo $_POST['modelID']; ?>>
          <input type="hidden" name="noOfPara" value=<?php echo $_POST['noOfPara'] - 1; ?>>
            <input type="submit" value="Next" required>
            <br><br>
          </div>
        </form>
      </div>
    </div>
<?php
    }//end of if ($_POST['noOfPara'])
else{
    echo "You have successfully created your model with all the paramters. Go back";
  }
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

</div>

<?php
include("end_page.html")
?>