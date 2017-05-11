<?php
#Include phplot 
require_once 'phplot.php';

#Check if XML file exists and load file
if(file_exists('plot_data.xml'))
{
	$xml = simplexml_load_file ("plot_data.xml");
}
else {
 	exit ('Could not load the file...');
}

#Read xml file into array
$data = array();
$max=0;
foreach ($xml->item as $item)
{
	
	foreach ($item->spikeNeuronID as $spikeNeuronID)
	{

		$data[]=array ('', (int)$item->timestamp, (int)$spikeNeuronID);
		if ((int)$spikeNeuronID>$max)
		{
			$max=(int)$spikeNeuronID;
		}		
		
	}
}

#Set graph properties and plot data

$plot = new PHPlot(800, 600);
$plot->SetImageBorderType('plain');
$plot->SetDataColors('blue');
$plot->SetPrintImage(False);
$plot->SetPlotType('points');
$plot->SetDataType('data-data');
$plot->SetDataValues($data);

$plot->SetTitle('Neuron-Timestamp plot');

$plot->SetPlotAreaWorld(-1, 0, (int)$item->timestamp+0.9, $max+0.9);
$plot->SetXTickIncrement(1);
$plot->SetYTickIncrement(1);

# Turn on 4 sided borders, now that axes are inside:

$plot->SetPlotBorderType('full');
$plot->SetDataColors('black');
$plot->SetPointShapes('plus');
$plot->SetPointSize(20);

# Draw both grids:
$plot->SetDrawDashedGrid(False);
$plot->SetLightGridColor('black');
$plot->SetDrawXGrid(False);
$plot->SetDrawYGrid(True);  # Is default

$plot->SetXTitle("Timestamp");
$plot->SetYTitle("Neurons");

$plot->DrawGraph();
?>

<?php
include("head.html")
?>

<h1>
Temporary spike plot</h1>
<img src="<?php echo $plot->EncodeImage();?>" alt="Plot Image">
<br><br>

<?php
include("end_page.html")
?>