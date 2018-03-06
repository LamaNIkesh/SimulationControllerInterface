import MySQLdb

if __name__ == '__main__':

	'''
	Script to initialise FPGAPool database with all the FPGA nums and default vals
	'''
	connect = MySQLdb.connect(host="localhost",user="root", passwd="", db='WebInterface')

	x = connect.cursor()
	try:
		for i in range(301):
			if i > 0:
				x.execute("""INSERT INTO FPGAPool (FPGANumber, Maintenance, OnlineStatus,Simulationid) VALUES (%s,%s,%s,%s)""", (i,0,0,0))
				connect.commit()
	except:
		connect.rollback()

	connect.close()
    

