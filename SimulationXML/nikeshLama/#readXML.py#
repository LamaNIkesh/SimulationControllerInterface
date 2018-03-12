import sys
import xml.etree.ElementTree as ET


#reading the xml file location passed from the php
def xmlParseBeforePublishing(xmlFile):
	'''
		This function takes the path of xml file generated from the web interface and breaks the packets into 
		different chunks and each packet is stored along row of a multidimensional array

		output: returns array of multidimensional matrix with all the information about the packets
	'''
	#xmlFile = sys.argv[1]
	#print(xmlFile)
	#xml parsing
	tree = ET.parse(xmlFile)
	#gets root which is <newSimulation>
	root = tree.getroot()
	#print (root)
	#counts how many packets are there
	packetCounter = 0
	#packetContent = [[0 for i in range(100)]for j in range(50)]
	#packets = x.find('packet')
	packetContent = []
	for child in root:
		#packets =x.find('packet')
		#print(child)
		#print("packet counter:", packetCounter)
		subchildCounter = 0
		#print(ET.tostring(child))
		packetContent = ET.tostring(child)
		packetCounter = packetCounter + 1

	return packetContent
#x = etree.parse(xmlFile)
#print(etree.tostring(xmlFile.child, pretty_print = True))

output = xmlParseBeforePublishing('SimulationXML/nikeshLama/Initialisation_file_nikeshLama1.xml')

#print(output[0][0])
