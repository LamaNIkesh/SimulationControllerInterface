<?php
/**
 * Execute the given command by displaying console output live to the user.
 *  @param  string  cmd          :  command to be executed
 *  @return array   exit_status  :  exit status of the executed command
 *                  output       :  console output of the executed command
 */
    $filePath = '/home/nikesh/Documents/WebServer/NewbranchV4/SimulationControllerInterface/SimulationXML/nikeshlama2018/Initialisation_file_nikeshlama2018_6.xml';
    $synfilePath = '/home/nikesh/Documents/WebServer/NewbranchV4/SimulationControllerInterface/SimulationXML/nikeshlama2018/Initialisation_file_Synapse_nikeshlama2018_6.xml';			
    while (@ ob_end_flush()); // end all output buffers if any


    $proc = popen("sudo -u daemon python /home/nikesh/Documents/WebServer/NewbranchV4/SimulationControllerInterface/tcpSend/send_packet_tcp.py 2>&1 $filePath $synfilePath; echo Exit status : $?", 'r');

    $live_output     = "";
    $complete_output = "";

    while (!feof($proc))
    {
        $live_output     = fread($proc, 50000);
        $complete_output = $complete_output . $live_output;
        echo "$live_output";
        @ flush();
    }
    pclose($proc);

    // get exit status
    preg_match('/[0-9]+$/', $complete_output, $matches);

?>
