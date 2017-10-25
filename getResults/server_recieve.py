
#!/usr/bin/python

import MySQLdb as mdb					#PythonMySql API
import pymysql
import socket                   # Import socket module
import sys

'''
port = 6000                    # Reserve a port for your service.
s = socket.socket()             # Create a socket object
host = '127.0.0.1'    # Get local machine name
s.bind((host, port))            # Bind to the port
s.listen(5)                     # Now wait for client connection.
'''

#Once the packet has been sent to the IM server, the database is updated to show configured simulations for each users	
con = mdb.connect(host ="localhost",port =3306, user = "nikesh", passwd = "1234", db = "WebInterface")
cur = con.cursor()	
rows = cur.execute("SELECT * from `UserSimulation`;")

countrow = rows
print("number of rows:", countrow)
data = cur.fetchone()
print(data)

'''
while True:
	print ('Server listening....')
	conn, addr = s.accept()     # Establish connection with client.
	print ('Got connection from', addr)
	data = conn.recv(50000)
	print('Server received', repr(data))

	#conn.send('Thank you for connecting'.encode(encoding = 'utf-8'))
	conn.close()

'''