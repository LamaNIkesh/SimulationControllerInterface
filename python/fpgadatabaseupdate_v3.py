
import pymysql 
import pymysql.cursors

conn= pymysql.connect(host='127.0.0.1',user='root',password='cncr2018',db='WebInterface',charset='utf8mb4',cursorclass=pymysql.cursors.DictCursor)
a=conn.cursor()

