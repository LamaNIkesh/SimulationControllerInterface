
import MySQLdb

if __name__ == '__main__':
	
	connect = MySQLdb.connect(host='100.100.1.168',user="root", passwd="cncr2018", db='WebInterface')

	x = connect.cursor()
	try:
		for i in range(376):
			if i > 0:
				x.execute("""INSERT INTO FPGAPool (FPGANumber, Maintenance, OnlineStatus,Simulationid) VALUES (%s,%s,%s,%s)""", (i,0,0,0))
				#connect.commit()
				print ("#")
	except:
		connect.rollback()

	connect.close()
    
