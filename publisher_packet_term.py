import paho.mqtt.client as mqtt
import time
import sys
import numpy as np
import readXML_1 as rx
import subscriber as sub

global flag
#xmlFile = sys.argv[1]
xmlFile = '/home/nikesh/Documents/WebServer/SimulationControllerInterface/SimulationXML/nikeshLama/Initialisation_file_nikeshLama1.xml'
#xmlFile = 'Initialisation_file_nikeshLama1.xml'
print(xmlFile)
#Interface Manager
MQTT_HOST= "100.100.1.254"
MQTT_PORT = 3000
MQTT_KEEPALIVE_INTERVAL = 5
MQTT_TOPIC = "webapp/post"
#listening for acknowledgement
MQTTP_TOPIC_ACK = "webapp/post/ack"

MQTT_MSG = ""
try:
	#returns a list with all the packets 
	MessageArray = rx.xmlParseBeforePublishing(xmlFile)
	print("successfully read")
except:
	print("cannot read")
#Define on_connect event handler
def on_connect(mosq, obj, rc):
	print("Connected to the MQTT broker")

#Define publish events
def on_publish(client, user,mid):
	print ("Message published")

#Initiate MQTT client
mqttc = mqtt.Client()

#Register event handlers
mqttc.on_publish = on_publish
mqttc.on_connect = on_connect

#connect with MQTT broker
try:
	mqttc.connect(MQTT_HOST, MQTT_PORT, MQTT_KEEPALIVE_INTERVAL)
except:
	print("Cannot connect")
	sys.exit(1)

sub.flag=True
#for i in range(len(MessageArray)):
	#MQTT_MS
#print ("messagearray size", len(MessageArray))

#loops through packets and publish to the topic
for i in range(len(MessageArray)):
	MQTT_MSG = ""
	MQTT_MSG = MQTT_MSG + MessageArray[i]
	print(MQTT_MSG)
		#
	#publishing the packets as string
	mqttc.publish(MQTT_TOPIC, MQTT_MSG)
	#setting flag to false

	#it will be set to true when acknowledgement as 200 is received
	#---Uncomment this section for acknowledgement 
	sub.flag=True
	#Initiate MQTT client
	mqttc1 = mqtt.Client("listener")
	mqttc1.connect(sub.MQTT_HOST, sub.MQTT_PORT, MQTT_KEEPALIVE_INTERVAL)
	#Register event handlers
	mqttc1.on_message = sub.on_message
	mqttc1.on_connect = sub.on_connect
	mqttc1.on_subscribe = sub.on_subscribe
	mqttc1.loop_forever()
	print("Flag",sub.flag)
	if(MessageArray[i] == 0):
		print("Finished sending packets successfully")
		break
	time.sleep(0.1)

#disconnect from the mqtt broker
mqttc.disconnect()


