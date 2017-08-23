import sys
import xml.etree.ElementTree as ET


#reading the xml file location passed from the php
def xmlParseBeforePublishing(xmlFile):
	'''
		This function takes the path of xml file generated from the web interface and breaks the packets into 
		different chunks and each packet is stored along row of a multidimensional array

		output: returns a list with all the packets; each element being a different packet
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

	packetContent = [[0 for i in range(100)]for j in range(50)]
	#packets = x.find('packet')

	for child in root:
		#packets =x.find('packet')
		#print(child)
		#print("packet counter:", packetCounter)
		subchildCounter = 0
		for subchild in child:
			#print (subchild)
			#printing subchild elements to string
			#print("packetcounter: ",packetCounter, "sunchild counter: ", subchildCounter)
			#write to a multidimensional array
			packetContent[packetCounter][subchildCounter] = ET.tostring(subchild)
			#print(ET.tostring(subchild))
			subchildCounter = subchildCounter + 1
			#x = etree.parse(subchild)
			#print(etree.tostring(x, pretty_print = True))
		#print("--------------------------------------------")
		#print(etree.tostring(x.find('packet'),pretty_print = True))
		#packets = x.find('packet')
		#print(etree.tostring(root[packetCounter], pretty_print = True))
		packetCounter = packetCounter + 1
	#print("There are %d of packets"%packetCounter)

	#for i in range(50):
		#for j in range(100):
	#print(packetContent[0][1])
	return packetContent
#x = etree.parse(xmlFile)
#print(etree.tostring(xmlFile.child, pretty_print = True))

output = xmlParseBeforePublishing('SimulationXML/nikeshLama/Initialisation_file_nikeshLama1.xml')

#print(output[0][0])
