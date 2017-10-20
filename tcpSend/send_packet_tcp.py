#import necessary libraries
import socket 
import time
import sys
import numpy as np
import readXML_1 as rx

host = socket.gethostname()
#host = 100.100.1.252 # host name is already configured in /etc/hosts ; IMserver has 100.100.1.252 IP address
port = 60000
packet = ""

##----sys.argv[1] will be used when calling this python script from php webpage directly.
# argv will the file path which may be different for different users
#xmlFile = sys.argv[1]
xmlFile = '/home/nikesh/Documents/WebServer/SimulationControllerInterface/SimulationXML/nikeshLama/Initialisation_file_nikeshLama1.xml'
#xmlFile = 'Initialisation_file_nikeshLama1.xml'

#function for tcp connection and packet transmission
def TCPclient(host, port, packet):
	sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)    
	print("+++++++++++Connection established with ", host, "port: ", port, "+++++++++++")	
	print("Sending packet to ", host, port)
	try:
		#TODO needs revision
		sock.connect((host, port))
		sock.sendall(packet.encode('utf-8'))
	finally:
		sock.close()

#try converting xml file into array elements as separate packets
try:
	#returns a list with all the packets 
	MessageArray = rx.xmlParseBeforePublishing(xmlFile)
	print("successfully read")
#error handling
except:
	print("cannot read")

#traverse through array elements and establish connection and send packet 
for i in range(len(MessageArray)):
	'''
	loops through each packet and send them to the tcp server i.e. im server
	'''
	packet= MessageArray[i]
	print(packet) # just printing on the console
	TCPclient(host,port,packet)

	

	
	


