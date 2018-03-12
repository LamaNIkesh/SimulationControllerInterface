<?php
include("head_admin.html")
?>

<?php 

function readModelLibrary(){
  $server = 'localhost';
  $user = 'root';
  $pass = 'cncr2018';
  $db = 'WebInterface';
  $flag = 0;

  try{
      $connection = mysqli_connect("$server",$user,$pass,$db);
      //echo $_POST['user'];
      $result = mysqli_query($connection, "select * from ModelLibrary") 
      or die("No user found!!!!".mysql_error());
      $counter = 0;
      while($row = mysqli_fetch_array($result)){
          echo "<tr>";
          echo "<td>".$row['ModelID']."</td>";
          echo "<td>".$row['ModelName']."</td>";
          echo "<td>"
          ?>
          <div class = "button-container"> 
            <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete the model?');">  
              <div>
                <input type="hidden" name = "DELETE" id = "DELETE" value = <?php echo $row['ModelName']; ?> >
                <input type="submit"  value="DELETE">
              </div>
            </form>
            <form action="" method="post">  
              <div> 
              <input type="hidden" name = "EDIT" id = "EDIT" value = "EDIT" >
              <input type="submit" value="EDIT">
              </div>
            </form>
          </div>
          <?php
          "</td>";
        }
    }
    catch (Exception $e){
      echo "Error! ".$e->getMessage();
    }
}



function editModelLibrary(){

}

function removeModel($model){
  //function for deletion of model
  $server = 'localhost';
  $user = 'root';
  $pass = 'cncr2018';
  $db = 'WebInterface';
  $flag = 0;

  try{
      $connection = mysqli_connect("$server",$user,$pass,$db);
      //echo $_POST['user'];
      $result = mysqli_query($connection, "select * from ModelLibrary") 
      or die("No user found!!!!".mysql_error());
      $counter = 0;
      while($row = mysqli_fetch_array($result)){
        //checks if the model matches the model in the database
        if ($model == $row['ModelName']){
          echo "row model :".$row['ModelName'];
          echo "    input model: ".$model;
          //removing a particular model
          $DeleteQuery = "DELETE FROM ModelLibrary WHERE ModelName = '$model'";
         
          
          if (mysqli_query($connection,$DeleteQuery)){
            echo "Record deleted successfully...";
            //lets drop the model table as well
            $dropTable = "DROP TABLE `$model`";
            if(mysqli_query($connection,$dropTable)){
              echo "Table removed successfully";
            }
            else{
              echo "error deleting table";
            }
          }
          else{
            echo "Error deleting record:".mysqli_error($connection);
          }
        }
      }
     //Error handling  
    }
    catch (Exception $e){
      echo "Error! ".$e->getMessage();
    }
}


function updateModelLibrary(){
  /*
  Depending on the changes, this is a wrapper function that calls correspondign changes
  */
  if(isset($_POST['DELETE'])){
    #deleting record
    echo "Deleting record";
    $model = $_POST['DELETE'];
    echo "model: ".$model;
    removeModel($model);
  }

  if(isset($_POST['EDIT'])){
    echo "Editing records";
  }

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
        <h6><font color = "#52a25e">Admin Model Editor-></b></h6></font>
        <h3>
          Welcome! You can add/delete/edit neuron models in the model library
        </h3>
        <hr>
        
        <b><p>List of Models present<p></b>
        <style>
          table {
              border-collapse: collapse;
              width: 100%;
          }

          th, td {
              text-align: center;
              padding: 8px;
          }

          tr:nth-child(even){background-color: #f2f2f2}

          th {
              background-color: #4CAF50;
              color: white;
          }
        </style>

        <table width  = "800" border = "1" cellpadding = "1" cellspacing = "1">
        <tr>
        <th>Model ID</th>
        <th>Model Name</th>
        
        <th colspan = "3" width = "50">Edit Options</th>
        <tr>

        <?php 
        //Let the function do the heavy lifting
        readModelLibrary();
        updateModelLibrary();
         ?>

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