import paho.mqtt.client as mqtt
import time
global flag
#subscribed to the acknowledgement topic webapp/post/ack

#Interface Manager
#Your broker IP
MQTT_HOST= "100.100.1.254"
MQTT_PORT = 3000
MQTT_KEEPALIVE_INTERVAL = 5
MQTT_TOPIC = "webapp/post/ack"

	## TODO handling error
#Define on_connect event handler
def on_connect(mosq, obj,flags, rc):
	#subscribe to the topic
	mosq.subscribe(MQTT_TOPIC, 0)

#Define on_subscribe event handler
def on_subscribe(mosq, obj, mid, granted_qos):
	print ("Subscribed to '%s' Topic"%MQTT_TOPIC)

#define on_message event Handler
def on_message(mosq, obj, msg):
	global flag
	message=msg.payload
	print("I am here")
	print message
	if int(message)==200:
		flag=True
	else:
		flag = False
	print (flag)
	mosq.disconnect()

# #Initiate MQTT client
# mqttc = mqtt.Client()

# #Register event handlers
# mqttc.on_message = on_message
# mqttc.on_connect = on_connect
# mqttc.on_subscribe = on_subscribe


#connect with MQTT broker
#mqttc.connect(MQTT_HOST, MQTT_PORT, MQTT_KEEPALIVE_INTERVAL)

def returnMsg():
	msg.payload

#continue the network loop
#mqttc.loop_forever()



