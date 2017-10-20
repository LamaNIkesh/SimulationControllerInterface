import socket                   # Import socket module

port = 60000                    # Reserve a port for your service.
s = socket.socket()             # Create a socket object
host = socket.gethostname()     # Get local machine name
s.bind((host, port))            # Bind to the port
s.listen(5)                     # Now wait for client connection.



while True:
	print ('Server listening....')
	conn, addr = s.accept()     # Establish connection with client.
	print ('Got connection from', addr)
	data = conn.recv(50000)
	print('Server received', repr(data))

	#conn.send('Thank you for connecting'.encode(encoding = 'utf-8'))
	conn.close()

