import sys
import xml.etree.ElementTree as ET


#reading the xml file location passed from the php
def xmlParseBeforePublishing(xmlFile):
	'''
		This function takes the path of xml file generated from the web interface and breaks the packets into 
		different chunks and each packet is stored along row of a multidimensional array

		output: returns array of multidimensional matrix with all the information about the packets
	'''
	tree = ET.parse(xmlFile)
	#gets root which is <newSimulation>
	root = tree.getroot()
	#print (root)
	packetContent = []
	for child in root:
		packetContent.append( ET.tostring(child))
		#packetCounter = packetCounter + 1

	return packetContent

