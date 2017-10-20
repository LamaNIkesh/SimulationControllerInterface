
import socket 
import time
import sys
import numpy as np
import readXML_1 as rx


host = socket.gethostname()
port = 60000
packet = ""

#xmlFile = sys.argv[1]
xmlFile = '/home/nikesh/Documents/WebServer/SimulationControllerInterface/SimulationXML/nikeshLama/Initialisation_file_nikeshLama1.xml'
#xmlFile = 'Initialisation_file_nikeshLama1.xml'

try:
	#returns a list with all the packets 
	MessageArray = rx.xmlParseBeforePublishing(xmlFile)
	print("successfully read")
except:
	print("cannot read")


def TCPclient(host, port, packet):
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    print("Sending packet to ", host, port)
    try:
        #TODO needs revision
        sock.connect((host, port))
        sock.sendall(packet.encode('utf-8'))
    finally:
        sock.close()

#loops through packets and publish to the topic

for i in range(len(MessageArray)):
	packet= packet + MessageArray[i]
	print(packet)
	print('\n')
	TCPclient(host,port,packet)
	if(MessageArray[i] == 0):
		print("Finished sending packets successfully")
		break
	

	
	


