
#!/usr/bin/python

import MySQLdb as mdb					#PythonMySql API
import pymysql
import socket                   # Import socket module
import sys
import Queue
import xml.etree.ElementTree as ET
import os
from pathlib import Path

port = 3001                   # Reserve a port for your service.
s = socket.socket()             # Create a socket object
#host = '127.0.0.1'    # Get local machine name
host = '100.100.1.173'
#host = '127.0.0.1'
#port = 3000

s.bind((host, port))            # Bind to the port
s.listen(5)                     # Now wait for client connection.


########################################################################################

def database_connect(db_table):
	#Once the packet has been sent to the IM server, the database is updated to show configured simulations for each users	
	con = mdb.connect(host ="localhost",port =3306, user = "nikesh", passwd = "1234", db = "WebInterface")
	cur = con.cursor()	
	database_table = db_table
	#query = "SELECT * from %s"%(database_table)
	query = "SELECT SimulationId, UserId FROM %s"%(database_table)

	rows = cur.execute(query)
	data = cur.fetchall()
	#returns multidimensional array with simulation id and user id
	# data[0][0]-->sim id
	# data[0][1]-->user id
	return data
	#countrow = rows
	#print("number of rows:", countrow)
	#data = cur.fetchone()
	#print(data)

def userDatabaseUpdate(simulationid, status,engage):
	'''
	updates the database after the result has been obtained
	The simulation Table is also updated to free up engage value
	Args: simulation id
			status 
			engage
	'''
	#setting up query and arguents for the query
	query = "UPDATE UserSimulation SET Status = %s, Engage = %s WHERE SimulationId = %s"
	arguements = (status, engage, simulationid)
	try:
		con = mdb.connect(host ="localhost",port =3306, user = "nikesh", passwd = "1234", db = "WebInterface")
		cur = con.cursor()	
		cur.execute(query, arguements)
		#accep changes
		con.commit()
	except Error as error:
			print(error)
	finally:
			cur.close()
			con.close()
	print("++++++++User Database Updated++++++++++")
		

def simulationDatabaseUpdate(simulationid,engage):
	'''
	updates the database after the result has been obtained
	The simulation Table is also updated to free up engage value
	Args: simulation id
			status 
			engage
	'''
	#setting up query and arguents for the query
	query = "UPDATE SImulation SET Engage = %s WHERE SimulationId = %s"
	arguements = (engage, simulationid)
	try:
		con = mdb.connect(host ="localhost",port =3306, user = "nikesh", passwd = "1234", db = "WebInterface")
		cur = con.cursor()	
		cur.execute(query, arguements)
		#accep changes
		con.commit()
	except Error as error:
			print(error)
	finally:
			cur.close()
			con.close()
	print("++++++++Simulation Database Updated++++++++++")




def insertIntoQueue():
	pass



def xmlResultCheckForSimulationIDandCommand():
	pass

#############################################################################

while True:
	print ('Server listening....')
	conn, addr = s.accept()     # Establish connection with client.
	print ('Got connection from', addr)
	#actual packet data
	data = conn.recv(50000)
	print('Server received', repr(data))
	#conn.send('Thank you for connecting'.encode(encoding = 'utf-8'))
	#xml parsing
	root = ET.fromstring(data)
	#root = tree.getroot()
	# print (root.tag)
	print(root[0].tag)
	# print(root[1].text)
	# print(root[2].text)
	# print(root[3].text)
	# print(root[4].text)

	#checking the contents of the xml for command and simulation number
	#based on these numbers the results will be collected to the correct user folder
	#if command is 25 with type 3 and status 1, it represents start of results packet
	#if command is 25 with type 3 and status 4, it represents end of results packets at 
	#which point the results file will be closd and the database is updated to be complete

	userdata = database_connect(db_table = 'UserSimulation')

	if(root[0].tag == 'destdevice'):
		#this packet will either be start results packet or end simulation packet
		
		########Format of the start or end packet
		# <destdevice>65532</destdevice>		root[0]
		#<sourcedevice>65535</sourcedevice> 	root[1]
		# <simulation>2</simulation>			root[2]
		# <command>25</command>					root[3]
		# <timestamp>0</timestamp>				root[4]
		# <type>3</type>						root[5]
		# <status>1 or 4</status>				root[6]
		
		simId = root[2].text
		cmd = root[3].text
		print(root[6].text)
		status = root[6].text #1 for start and 4 for end

		if(status == '1' or status == '4'):
			#checking the database for what simulation is assigned for which user
			userdata = database_connect(db_table = 'UserSimulation')
			#print(userdata)
			#userSimId = userdata[0][0] #first element eg. (1,nikeshlama)
			#userId = userdata[0][1] #second element
			#print('usersimid',userSimId)
			#print('username',userId)
			#print(len(userdata))
			#print('usersimID',int(userSimId))
			#print('simid',simId)
			userdatasize = len(userdata)
			for i in range(userdatasize):
				#print(i)
				#check if the packet simulation id matches the one in the databse
				#matching sim id will give userid which will be used to save the results 
				#in appropriate user folder
				if(int(simId) == int(userdata[i][0])):
					print ('match found')
					print('username is',userdata[i][1])
					userid = userdata[i][1] # return the username for that simulation

					#save into a file
					if(status == '1'):
						print ("start packet detected")
						resultsFile = open('../SimulationXML/%s/simResults/Results_%s_%s.xml'%(userid,userid,simId),'w+')
						xmlheader = '<?xml version="1.0" encoding="UTF-8"?>\n<results>' 
						resultsFile.write(xmlheader)
						resultsFile.write('\n')
						#resultsFile.write("<results>")
						
						#resultsFile.close()
					else:
						print ("End packet detected")
						resultsFilePath = Path('../SimulationXML/%s/simResults/Results_%s_%s.xml'%(userid,userid,simId))
						if resultsFilePath.is_file():
							resultsFile = open('../SimulationXML/%s/simResults/Results_%s_%s.xml'%(userid,userid,simId),'a')
							#closing the file after finishing the tag
							resultsFile.write('</results>')
							resultsFile.close()
							#now that the file has been successfully stored
							#Database needs to be updated as well
							userDatabaseUpdate(simulationid = simId, status = 'Finished',engage = 0)
							#update simulatino database
							#simulationDatabaseUpdate(simulationid = simId,engage = 0)

						else:
							print("Error: End packet received before start packet!!!!")
							break
							
						

	#if the packet contains actual simulation results in timestamps						
	elif(root[0].tag == 'simulation'):

		########Format of the start or end packet
		# <simulation>2</simulation>			root[0]
		# <timestamp>1</timestamp>				root[1]
		# <neuronid>0</neuronid>				root[2]

		#the first tag will be simulation id which is the simulation results
		simId = root[0].text
		userdata = database_connect(db_table = 'UserSimulation')
		userdatasize = len(userdata)
		for i in range(userdatasize):
			#print(i)
			#check if the packet simulation id matches the one in the databse
			#matching sim id will give userid which will be used to save the results 
			#in appropriate user folder
			if(int(simId) == int(userdata[i][0])):
				#print ('match found')
				print('username is',userdata[i][1])
				userid = userdata[i][1] # return the username for that simulation
				print ("Results packet detected")
				resultsFilePath = Path('../SimulationXML/%s/simResults/Results_%s_%s.xml'%(userid,userid,simId))
				if resultsFilePath.is_file():
					resultsFile = open('../SimulationXML/%s/simResults/Results_%s_%s.xml'%(userid,userid,simId),'a')
					#write the packet data obtained at the top of this code
					resultsFile.write(data)
				else:
					print("The file doesn't exist. The start must be missing. Please check!!!!")
				
	else:
		print('Packet Error!!!!')

	conn.close()
	#print('txi\nnew')

#database_connect()