#!/usr/bin/python

'''
Parsing xml results from the FPGA cluster to a csv file with timestamps for each spike train.
Each line contains timestamps separated with a space for a particular spike .
For eg: 12 50 100 120 300 500 ....... ->these are timestamps in ms for one spike trains

'''
import sys
import numpy as np
import xml.etree.ElementTree as ET
import csv

global tree,root,child

def spikeTrainsFromXML(xmlFileLoc,filename, noOfNeurons):
	print("No of Neurons: {}".format(noOfNeurons))

	#creating empty list to store spiketrains before saving into a csv/txt file
	spiketrains = [[0 for _ in range(1)] for i in range(noOfNeurons) ]
	
	xmlFile = xmlFileLoc + filename;

	tree = ET.parse(xmlFile)
	root = tree.getroot()
	#print("root: {}".format(root.tag))

	for elem in tree.iter(root[0].tag):
		'''
		#Here is the format of the xml results
		<results> -->root
			<packet>-->child
				<simulation>4</simulation>   -->elem
				<timestamp>2</timestamp>	 -->timstamp at which neuron fires
				<neuronid>1</neuronid>		 -->Firing neuron
			</packet>
			<packet>
				<simulation>4</simulation>
				<timestamp>3</timestamp>
				<neuronid>1</neuronid>
				<neuronid>2</neuronid>
			</packet>
		</results>
		'''
		#print ("elem tag: {}".format(elem.tag))
		#print ("Neurons firing at timestamp {}".format(elem[1].text))
		if len(elem) < 3: # if only headers are present, it means no neurons spiked at this timestamp
			#print("No neurons fired at this timestamp {}".format(elem[1].text))
			pass
		else:
			for j in range(len(elem)):
				#looking into for
				if j > 1:
					neuronNum = int(elem[j].text)
					#print(neuronNum)
					timestamp = elem[1].text
					#print("Neuron firing {}".format(elem[j].text))
					#neuronNum -1 since first neuron will at index 0:
					#while reading, index zero will read neuron 1.
					###change: the first element is always zero since we created an empty list with initial zerovalue
					spiketrains[neuronNum - 1].append(timestamp)

	for i in range(len(spiketrains)):
		#removing first element, it is just a value for empty list
		#having a zero indicates that neuron has fired at timestamp 0/
		#so remove the very first element from the list	
		spiketrains[i].pop(0)

	############################
	#here we have a list of list of spike trains
	#export these into a text file for further analysis
	filename = filename[:-4]
	csvfile = xmlFileLoc + filename + "resultscsv.txt"
	with open(csvfile, "w") as outFile:
			write = csv.writer(outFile, delimiter = ",", lineterminator = "\n")
			write.writerows(spiketrains)
	print("Conversion complete.....")
	
if __name__ == '__main__':
	
	#getting xml file as user input which will be read automaically via php scripts
	#similarly for neuron numbers too.  
	
	xmlFileLoc = sys.argv[1]
	filename = sys.argv[2]	
	noOfNeurons = sys.argv[3]
	#xmlFile = 'Results_nikeshlama2018_4.xml'
	#noOfNeurons = 10	
	#xmlFileLoc will give locationa and filename gives the actual name of the file
	#these are separted so that we can save the csv file in the same folder
	spikeTrainsFromXML(xmlFileLoc = xmlFileLoc,filename = filename , noOfNeurons = int(noOfNeurons))





