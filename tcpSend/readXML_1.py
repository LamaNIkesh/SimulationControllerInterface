import sys
import xml.etree.ElementTree as ET


#reading the xml file location passed from the php
def xmlParseBeforePublishing(xmlFile):
	'''
		This function takes the path of xml file generated from the web interface and breaks the packets into 
		different chunks and each packet is stored along row of a multidimensional array

		output: returns a list with all the information about the packets
				each append is appending a full packet 
	'''
	try:
		tree = ET.parse(xmlFile)
	except:
		print("cannot open xml...")
	#gets root which is <newSimulation>
	root = tree.getroot()
	print (root)
	packetContent = []
	for child in root:
		#print(ET.tostring(child))
		packetContent.append( ET.tostring(child))
		#packetCounter = packetCounter + 1

	return packetContent


#reading the xml file location passed from the php
def xmlParseBeforePublishing_OnlyFPGA(xmlFile):
	'''
		This function takes the path of xml file generated from the web interface and breaks the packets into 
		different chunks and each packet is stored along row of a multidimensional array

		output: returns a list with all the information about the packets
				each append is appending a full packet 
	'''
	try:
		tree = ET.parse(xmlFile)
	except:
		print("cannot open xml...")
	#gets root which is <newSimulation>
	root = tree.getroot()
	#print (root)
	packetContent = [[]]
	counter = 0
	for child in root:
		#print("child 3",child[3].text)
		#print(ET.tostring(child))
		if (child[3].text == '30'):
			#Target FPGA packet
			print("Target FPGA: ",child[5].text)
			print("Target Model: ", child[6].text)
			#Lets write into an array
			packetContent[counter][0] = child[5].text
			packetContent[counter][1] = child[6].text		
		#packetContent.append( ET.tostring(child))
		#packetCounter = packetCounter + 1
		counter = counter + 1

	return packetContent

