#!/usr/bin/python

#import necessary libraries
import MySQLdb as mdb					#PythonMySql API
import socket
import time
import sys
import numpy as np

from termcolor import colored,cprint
import xml.etree.ElementTree as ET
import time

#host = '127.0.0.1'
host = "100.100.1.252" # IM server IP
port = 3000  # port for IM server tcp 
#port = 6000

packet = ""

##----sys.argv[1] will be used when calling this python script from php webpage directly.
# argv will the file path which may be different for different users
xmlFile = sys.argv[1]
#xmlFile = '/home/nikesh/Documents/WebServer/SimulationControllerInterface/SimulationXML/nikeshLama/Initialisation_file_nikeshLama1.xml'
#xmlFile = 'Initialisation_file_nikeshLama1.xml'
#print_red_on_cyan = lambda x:cprint(x,'red','on_cyan')


def xmlParseBeforePublishing(xmlFile):
	'''
		This function takes the path of xml file generated from the web interface and breaks the packets into 
		different chunks and each packet is stored along row of a multidimensional array

		output: returns a list with all the information about the packets
				each append is appending a full packet 
	'''
	try:
		tree = ET.parse(xmlFile)
		#gets root which is <newSimulation>
		root = tree.getroot()
		print (root)
		packetContent = []
		
		counter = 0
		RequiredFPGAs = 0
		for child in root:
			print(ET.tostring(child))
			packetContent.append( ET.tostring(child))
			if (child[3].text == '30'):
				RequiredFPGAs = RequiredFPGAs + 1
		print(RequiredFPGAs)
			
		TargetFPGAArray = [[0]*2]*RequiredFPGAs
		TargetFPGAArray[0][0] = 5
		TargetFPGAArray[0][1] = 6
		print(TargetFPGAArray[0][0])
		print(TargetFPGAArray[0][1])
		print(np.shape(TargetFPGAArray))
		#creating empty 2d array to store FPGA and model
		print(TargetFPGAArray[0][0])
		for child in root:
			if (child[3].text == '30'):
				#Target FPGA packet
				print("Target FPGA: ",child[5].text)
				print("Target Model: ", child[6].text)
				#Lets write into an array
				print("counter ->",counter)
				TargetFPGAArray[counter][0] = child[5].text
				TargetFPGAArray[counter][1] = child[6].text
				print("Input complete")
				#TargetFPGAArray[0][1]=6
			#packetContent.append( ET.tostring(child))
			#packetCounter = packetCounter + 1
			#counter = counter + 1
		else:
			print("NothingS")
		

	except:
		print("cannot open xml...")

	print("Returning")
	return packetContent,TargetFPGAArray


#function for tcp connection and packet transmission
def TCPclient(host, port, packet):
	sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	print (colored(("+++++++++++Connection established with ", host,"port: ",port, "+++++++++++"),'cyan'))
	print ("Sending packet to ", host, port)
	try:
		#TODO needs revision
		sock.connect((host, port))
		sock.sendall(packet.encode('utf-8'))
	finally:
		sock.close()



def CheckDatabaseForProgrammedFPGAs(db_table, TargetFPGAArray):
	#Checking databse for programmed FPGAs before sending simulation Packets
	#Once the packet has been sent to the IM server, the database is updated to show configured simulations for each users	
	con = mdb.connect(host ="localhost",port =3306, user = "nikesh", passwd = "1234", db = "WebInterface")
	cur = con.cursor()	
	database_table = db_table
	#query = "SELECT * from %s"%(database_table)
	query = "SELECT FPGANumber, OnlineStatus, neuronModel, ModelConf FROM %s"%(database_table)

	rows = cur.execute(query)
	data = cur.fetchall()
	
	#Returns a 2d Array  
	#[][0]=FPGANumber
	#[][1] = OnlineStatus
	#[][2] = neuronModel
	#[][3] = ModelConf
	#print("size: ",np.shape(data))
	#print(data[0][3])

	#Checking if all the fpgas are configured or not by double checking with the TargetFPGAArray
	#All the target FPGAs need to have neuronModel ModelConf as 1
	AllConfiguredFlag = 0
	#print("length of array", len(TargetFPGAArray))
	for i in range(len(TargetFPGAArray)):
		#TargetFPGAArray[][1] = FPGAnum, [2] = ModelName
	 	TargetFPGANum = TargetFPGAArray[i][0]
	 	#print("Target FPGA num",TargetFPGANum)
	 	#print("Target FPGA num model: ",data[int(TargetFPGANum)][3])
	 	#print("Model COnf: ",data[int(TargetFPGANum)-1][3])
	 	if(data[int(TargetFPGANum)-1][3] == 1):
	 		AllConfiguredFlag = 1 
	 		print("Flag: ", AllConfiguredFlag)
	 	else:
	 		AllConfiguredFlag = 0
	 		print("Flag: ", AllConfiguredFlag)

	print("Final configre Flag: ",AllConfiguredFlag)
	return AllConfiguredFlag




#try converting xml file into array elements as separate packets

#returns a list with all the packets 
print(xmlFile)

try:
	MessageArray,TargetFPGAArray = xmlParseBeforePublishing(xmlFile)
	print(TargetFPGAArray)
#error handling
except:
	print("cannot read")

#print(len(TargetFPGAArray))
#xmlParseBeforePublishing(xmlFile)

#print (colored("successfully read",'green'))
#uncomment this section for sending all the packets 

userdata = CheckDatabaseForProgrammedFPGAs('FPGAPool', TargetFPGAArray)
NumberOfTargetFPGAs = len(TargetFPGAArray)
print("Number of FPGAs needed!!", NumberOfTargetFPGAs)

for i in range(len(MessageArray)):
#for i in range(1):
	#loops through each packet and send them to the tcp server i.e. im server
	packet= MessageArray[i]
	print (colored(packet,'green')) # just printing on the console
	try:
		if(i<NumberOfTargetFPGAs):
			TCPclient(host,port,packet)
			print("FPGA programming Packets Sent!!!")
		#print("Trying....")
		if(i>=NumberOfTargetFPGAs): #Sending the first FPGA programming packets without any acknowledgements
			#if(CheckDatabaseForProgrammedFPGAs('FPGAPool', TargetFPGAArray) == 1):
				#print("FPGAs Programmed successfully!!")
				#print("Sending rest of the paackets!!!")
			while(True):
				if(CheckDatabaseForProgrammedFPGAs('FPGAPool', TargetFPGAArray) == 1):
					print("All FPGAs configured")
					TCPclient(host,port,packet)
					time.sleep(0.5)
					break
				else:
					print("Not programmed")
					time.sleep(5) # delay for 5 seconds
		#lets wait for acknowledgement from the receiver. 
		# s.bind((host, port))            # Bind to the port
		# s.listen(5)                     # Now wait for client connection.	
		# print ('Server listening....')
		# conn, addr = s.accept()     # Establish connection with client.
		# print ('Got connection from', addr)
		# #actual packet data
		# data = conn.recv(50000)
		# While(data!='0'):
		# 	print('Packet {} sent out of {} packets:'.format(i,len(MessageArray))
		# 	#print('Server received', repr(data)
	except:
		print (colored('Error: Could not establish connection!!', 'red'))
	#break




#traverse through array elements and establish connection and send packet 
#print(MessageArray[0])


#sending only the first packet for testing
'''
packet = MessageArray[0]
print (colored(packet,'green'))
try:
	TCPclient(host,port,packet)
except:
	print (colored('Error: Could not establish connection!!', 'red'))
'''


