#import necessary libraries
import socket 
import time
import sys
import numpy as np
import readXML_1 as rx
from termcolor import colored,cprint


host = socket.gethostname()
host = "100.100.1.252" # host name is already configured in /etc/hosts ; IMserver has 100.100.1.252 IP address
port = 3000
packet = ""

##----sys.argv[1] will be used when calling this python script from php webpage directly.
# argv will the file path which may be different for different users
#xmlFile = sys.argv[1]
xmlFile = '/home/nikesh/Documents/WebServer/SimulationControllerInterface/SimulationXML/nikeshLama/Initialisation_file_nikeshLama1.xml'
#xmlFile = 'Initialisation_file_nikeshLama1.xml'
#print_red_on_cyan = lambda x:cprint(x,'red','on_cyan')
#function for tcp connection and packet transmission
def TCPclient(host, port, packet):
	sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)    
	print colored(("+++++++++++Connection established with ", host,"port: ",port, "+++++++++++"),'cyan')	
	print ("Sending packet to ", host, port)
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
		
	print colored("successfully read",'green')
#error handling
except:
	print("cannot read")

#traverse through array elements and establish connection and send packet 


#uncomment this section for sending all the packets 
'''
for i in range(len(MessageArray)):
	'''
	loops through each packet and send them to the tcp server i.e. im server
	'''
	packet= MessageArray[i]
	print colored(packet,'green') # just printing on the console
	TCPclient(host,port,packet)
'''

#sending only the first packet for testing
packet = MessageArray[0]
print colored(packet,'green')
TCPclient(host,port,packet)
	

	
	


