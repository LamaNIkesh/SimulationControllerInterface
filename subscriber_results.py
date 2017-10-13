import paho.mqtt.client as mqtt
import time
import os


#Interface Manager
#Your broker IP
MQTT_HOST= "100.100.1.254"
MQTT_PORT = 3000
MQTT_KEEPALIVE_INTERVAL = 5
MQTT_TOPIC = "webapp/get"

#if(Path('results.txt').file()):
resultsFile = open('SimulationXML/nikeshLama/Result/results.xml','w')

#Define on_connect event handler
def on_connect(mosq, obj,flags, rc):
	#subscribe to the topic
	mqttc.subscribe(MQTT_TOPIC, 0)

#Define on_subscribe event handler
def on_subscribe(mosq, obj, mid, granted_qos):
	print ("Subscribed to '%s' Topic"%MQTT_TOPIC)
#define on_message event Handler
def on_message(mosq, obj, msg):
	'''
	Results packet received and saved into xml format for final plotting
	'''
	print msg.payload
	
	if(msg.payload == '<packet>\n\
  <destdevice>65532</destdevice>\n\
  <sourcedevice>65535</sourcedevice>\n\
  <simulation>2</simulation>\n\
  <command>25</command>\n\
  <timestamp>0</timestamp>\n\
  <type>3</type>\n\
  <status>1</status>\n\
</packet>\n\
'):
		resultsFile.write('<?xml version="1.0" encoding="UTF-8"?>\n<results>\n')
			

	elif(msg.payload == '\
<packet>\n\
  <destdevice>65532</destdevice>\n\
  <sourcedevice>65535</sourcedevice>\n\
  <simulation>2</simulation>\n\
  <command>25</command>\n\
  <timestamp>1000</timestamp>\n\
  <type>3</type>\n\
  <status>4</status>\n\
</packet>\n\
'):
		resultsFile.write('</results>')		
		resultsFile.close()
					
			
	else:
		resultsFile.write(msg.payload)

#Initiate MQTT client
mqttc = mqtt.Client()

#Register event handlers
mqttc.on_message = on_message
mqttc.on_connect = on_connect
mqttc.on_subscribe = on_subscribe


#connect with MQTT broker
mqttc.connect(MQTT_HOST, MQTT_PORT, MQTT_KEEPALIVE_INTERVAL)

#continue the network loop
mqttc.loop_forever()

if(mqttc.on_message):
	print ("received")

