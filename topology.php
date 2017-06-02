<?php
include("head.html")
?>

<script type="text/javascript" src="dist/vis.js"></script>
<link href="dist/vis.css" rel="stylesheet" type="text/css" />


  <div class = "container">
   <div class="col-sm-12">
    <h6><font color = "#52a25e">System Builder->Simulation Parameters->NeuronModels->NeuronModelParameter->Creating Initialisation File-><b>Create Topology</b></h6></font>
    <p><h3>Create Topology</h3></p>

    <p id = "i"></p>

    <?php
    if ($_SESSION['flag']==1){
        $neuronlistPath = "SimulationXML/".$userLogged . "/NeuronList.txt";
        $list=file($neuronlistPath);

                    //echo $list[9];
        $totalNeurons=$_POST['totalNeurons'];
                    //echo $_POST['totalNeurons'];
        $num = 1;
                    //echo "neuron 1 is ".$_POST['neuron'.$num];
                    //Fully interconnected
        ?>
        <br>
        <form method="post" action ="topologygenerate.php"> 
            <input type= "submit" method = "post" value = "Create fully interconnected network">
            <input type="hidden" name="totalNeurons" value=<?php echo $_POST['totalNeurons']; ?>>
            <input type="hidden" name="topologyType" value="fullyConnected">
        </form>
        <br><br>
        <form action = "topologygenerate.php" method = "post">
            <input type= "submit" method = "post" value = "Create Random network">    
            <input type="hidden" name="topologyType" value="randomlyConnected">
            <input type="hidden" name="totalNeurons" value=<?php echo $_POST['totalNeurons']; ?>>
        </form>
        <?php
                    //probabilistic connections

        ?>
    
<br><br>
<?php
}
else{
  ?>
  <p>You need to log in to see this page:</p>
  <form action="login.php" method="post">
     <input type="submit" value="Log in">
 </form>
 <br><br>
 <?php
}
?>

</div>
</div>
<?php
include("end_page.html")
?>
