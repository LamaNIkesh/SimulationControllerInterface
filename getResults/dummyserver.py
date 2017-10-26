
#!/usr/bin/python

import MySQLdb as mdb					#PythonMySql API
import pymysql
import socket                   # Import socket module
import sys
import Queue
import xml.etree.ElementTree as ET
import os

#port = 3000                    # Reserve a port for your service.
s = socket.socket()             # Create a socket object
host = "100.100.1.173"  	# IM server IP
port = 3000  			# port for IM server tcp     # Get local machine name
s.bind((host, port))            # Bind to the port
s.listen(5)                     # Now wait for client connection.


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
def insertIntoQueue():
	pass



def xmlResultCheckForSimulationIDandCommand():
	pass

counter = 0
while True:
	print ('Server listening....')
	conn, addr = s.accept()     # Establish connection with client.
	print ('Got connection from', addr)
	data = conn.recv(50000)
	print('Server received', repr(data))
	counter = counter + 1
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
		print('start/end packet')

	#end of main else						
	else:
		#the first tag will be simulation id which is the simulation results
		print('results packet')


	conn.close()
	print('Number of packets received: ', counter)


#database_connect()
