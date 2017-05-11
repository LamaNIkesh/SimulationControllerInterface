'''
                 *************************************************************************************
                 *************************************************************************************
                                        THIS VERSION CONVERTS XML TO PLAIN RAW HEX
                                                     WITH EROOR DETECTION
                ****************************************************************************************
                ****************************************************************************************


                -->This program changes stream of valid hex characters to
                   xml and also parses th xml into Raw hex stream which will be then directed
                   to the FPGA via IM.

                -->the operatin is carried out via terminal
                   the command goes like this;
                   python <name of the program.py> -H or h (to convert xml into hex) or
                   -X or-x (to convert hex to xml) <xml or hex file> <logging xml or hex file>
'''
# -*- coding: utf-8 -*-
"""
Created on Oct 10 11:26:36 2015

@author: ntu-nikesh
version 7
"""

'''
#*********************************************
#            importing necessary libraries
#*********************************************
'''

import sys # library required to read and write the files
import requests
import xml.etree.ElementTree as ET # this library is necesssary to parse xml files
from string import maketrans
import numpy as np
from numpy import binary_repr
import binascii  # this library converts hex string to raw hex, this helps in keeping the hex file size very concise
import sys,getopt
import os
import os.path
import threading
import socket
import shutil
from stat import ST_SIZE

global file4
global errorDetected

#defining all the global variables for the conversion
global destDev,srcDev,Cmd,timestmp,payload,_destDev,_srcDev,_Cmd,_timestmp
global file2
global invalidCommandNopld, InvalCommand,Inval_packet_length,Inval_query, Inval_dataType, Inval_busSize,dataType_busMismatch,timestmpError,samplingPrdError
#this error_flag indicates if there is any error
global inval_inLsb, inval_outLsb, inval_destDev,inval_srcDev
global dID, sID, cMD, tStp, payload
global invalidCommandNopld, InvalCommand,Inval_packet_length,Inval_query, Inval_dataType, Inval_busSize,dataType_busMismatch,timestmpError,samplingPrdError
#this error_flag indicates if there is any error
global error_flag
global inval_inLsb, inval_outLsb, inval_destDev,inval_srcDev,Inval_busSize

'''
#******************************************************************************
#--------------declaring variables for right command for without payload packet
#*******************************************************************************
'''
startSim = 0
StopSim = 2
AbortSim = 5
Reset = 15  #0x0f
#CmdFail = 25 #0x19

SynapticInStates = 9
MuscleParam = 20
#-----NewBCP now is spikes at the previous timestamp-----------
NewBCP = 13
EndSim = 17
UploadComplete = 18
NeuronControllerId = 27 #########################################################pending
#UploadRdy = 18

#-----------------declaring variables for command with fixed payload
#-----payload 18
SpkngNrnRslts = 22 # 0x16
SimStateResults = 28
ErrorDetected = 6  # packet content not recognised

#----payload 19
MuscleResults = 23 # 0x17
sdcpID = 1
Query = 3
Neuron_Muscle_Id = 8
SimulationId = 31

#-----payload 21
sdcpDisp = 7
#---payload 23

RuntimeStimulus = 26 #0x1a

#----payload 31
preconfStim = 19

#-----payload 39
SimParaInit = 14 # 0x0E

#-----payload 54
ConfigNetworkTplgy = 11 #0x0b

NewSDCP = 21
RuntimeStimEnd = 25

#---payload 20
MuscleCollatedRes = 12 #0x0c

#----------------------parameters for variable payload
def_neuron_para = 24
def_muscle_para = 20
def_RTW_para = 16
def_lsVar = 30
def_itemVal = 29
def_RTWResults = 4
SensoryNrnRslts = 10 # 0x0a


#packet size for without payload packet
pktSize_noPld = 34
pktSize_fxd18 = 36 # 18 bytes
pktSize_fxd19 = 38 #19 bytes
pktSize_fxd21 = 42 #21bytes
pktSize_fxd23 = 46 #23bytes
pktSize_fxd31 = 62 #31 bytes
pktSize_fxd39 = 78 #39bytes
pktSize_fxd55 = 110 #55 bytes -----the neuron id are represented with one hot representation
pktSize_fxd287 = 574 # 160 bytes

#-------------------Variable Payload variables----------------

header_size_neuron_muscle = 88 #number of hex characters
_payload_neurn = 50 # number of hex characters' its 25 bytes so 50 hex characters

header_size_RTW = 56 #in hex actually 28 bytes
_payload_RTW = 4 # in hex, actually 2 bytes

header_size_lsVar = 38
_payload_lsVar = 4 #2 bytes

header_size_itemVal = 46
_payload_itemVal = 12 # 6bytes

header_size_RTWResults = 62
_payload_RTWResults = 12

header_size_MuscleCollatedRes = 56 # 20 bytes
_payload_MuscleCollatedRes = 6 # 3bytes, 2 bytes for muscle id and 1 byte for muscle Value

header_size_SensoryNrnRslts = 46 # 23 bytes of header
_payload_SensoryNrnRslts = 12  # 6bytes of payload
#different datatype in decimal rep----------
eight_bit_ufix = 0
sixteen_bit_ufix = 1
twentyFour_bit_ufix = 2
thirtyTwo_bit_ufix = 3
eight_bit_sfix = 4
sixteen_bit_sfix = 5
twentyFour_bit_sfix = 6
thirtyTwo_bit_sfix = 7
eight_bit_uint = 8
sixteen_bit_uint = 9
twentyFour_bit_uint = 10
thirtyTwo_bit_uint = 11
eight_bit_sint = 12
sixteen_bit_sint =13
twentyFour_bit_sint =14
thirtyTwo_bit_sint = 15
thirtyTwo_bit_float = 16
#================end of variable declaration=======================
#statement that defines the type of conversion
# -X or -x is for hex to xml conversion
# -H or -h for xml to hex conversion
#global file2


'''
#*******************************************************************************************************************************
#******************************************************************************************************************************
#                                         LIST OF ALL THE FUNCTIONS
#*******************************************************************************************************************************
'''
##this function is the file transfer function, opens the socket and sends the
#file to the PE
'''
def FTP():
    #HOST='192.168.2.104'
    HOST = '127.0.0.1'
    PORT=5000
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect((HOST,PORT))
    if s.recv(5)!='READY':
        raw_input('Unable to connect \n\n Press any key to exit ...')
        s.close()
        exit()
    path=file2.name
    print "Filename:", path

    f=open(path,'rb')
    fname=path
    s.sendall(str(fname))
    sfile = s.makefile("wb")
    shutil.copyfileobj(f, sfile)
    sfile.close()
    s.close()
    f.close()
    print "File successfuly transferred to the Physics Engine"
'''


def send_pe():
    BASE_DIR = os.path.dirname(os.path.dirname(__file__))
    endpoint_pe = "https://150.241.250.4:2730/PE/api/input"
    muscleresultsfile = file2.name

    payload1 = open(muscleresultsfile, 'rb').read()
    request1 = requests.post(endpoint_pe, data=payload1, verify=False, auth=('admin', '1sielegans!'))
    print request1.text
#return

#this method creates xml file and further tags can be added based upon the type of packet.
def packetHeaderXML():
    #file2.write('<?xml version="1.0" encoding="UTF-8"?>\n')
    file2.write('<packet>\n')
    file2.write('\t<destdevice>%s</destdevice>\n'%dID)
    file2.write('\t<sourcedevice>%s</sourcedevice>\n'%sID)
    file2.write('\t<command>%s</command>\n'%cMD)
    file2.write('\t<timestamp>%s</timestamp>\n'%tStp)

# this functionn is used to avoid re typing the same thing again and again for the zero payload
def zeropldXML_layout():
    packetHeaderXML()
    file2.write('</packet>')

#this method changes xml to hex based on the root size
#the root size is fed after reading the xml file
#all the conversion happens within in function
''' -------------This is the main function that does the xml to hex conversion
    -------------It takes two arguements, child (child element of XML) and its size
    -------------If there are no child elements then root is considered as a child
'''
def xml_layout(childSize,child):
    global destDev,srcDev,Cmd,timestmp,payload,_destDev,_srcDev,_Cmd,_timestmp
    global file2

    #storing xml data into an int variable
    _destDev = int(child[0].text)
    _srcDev = int(child[1].text)
    _Cmd = int(child[2].text)
    _timestmp = int(child[3].text)
    print "command :",_Cmd
    print "dest dev;", _destDev
    #checking for the invalid destination id
    if (_destDev>-1 and _destDev < 438 ) or (_destDev>65531 and _destDev < 65536):
        error_flag = 0
    else:
        print "Error detected in the destination device id"
        error_flag = 1
        inval_destDev = 1
    #since source id cannot be a broadcast address, source id 0 is considered as an invalid id
    if (_srcDev > 0 and _srcDev < 438) or (_srcDev>65531 and _srcDev < 65536):
        error_flag = 0
    else:
        print "Error detected in the destination device id"
        error_flag = 1
        inval_srcDev = 1


    #changing into to hex and storing it into another variable
    #the hex strings are converted into raw hex numbers without any ascii encoding using binascii function
    #this reduces the space consumed and the processing time
    destDev = hex(_destDev)[2:].zfill(4)
    print "destination device id:", destDev
    destDev=binascii.a2b_hex(destDev)
    srcDev = hex(_srcDev)[2:].zfill(4)
    srcDev=binascii.a2b_hex(srcDev)
    Cmd = hex(_Cmd)[2:].zfill(2)
    print "Command", Cmd
    Cmd = binascii.a2b_hex(Cmd)
    timestmp = hex(_timestmp)[2:].zfill(16)
    timestmp = binascii.a2b_hex(timestmp)
    #payload = hex(int(0))[2:].zfill(8)
    #print childSize
    no_of_dataType = 16
    #write the header for the hex without the payload as payload differs as per the packet
    hex_convert()
    #--------------------------for variable payload packets
    #--------------------------binascii is used to convert all the hex strings into a raw hex numbers

    if _Cmd == def_neuron_para or _Cmd == def_muscle_para or _Cmd == def_RTW_para or _Cmd == def_lsVar or _Cmd == def_RTWResults or  _Cmd == def_itemVal or _Cmd == SensoryNrnRslts:
          #print "Variable Payload"
        for i in range (200,0,-1):
            #print "Variable Payload",i
            #muscle and neuron parameters have the same format for the XML
            if childSize == (5 + i*9) and ( _Cmd == def_neuron_para or _Cmd == def_muscle_para) :

                for k in range (0,i,1):
                    payloadSize = i
                    print "payloadSize:", payloadSize
                    payloadBytes = payloadSize * 25 + 2
                    print "Payload Bytes: ", payloadBytes
                    #payload size in hex.
                    payload = hex(payloadBytes)[2:].zfill(8)
                    payload = binascii.a2b_hex(payload)
                    print "i:", i
                    print "K:", k
                    if k == 0:
                        _modelID = int(child[4].text)
                        modelID = hex(_modelID)[2:].zfill(4)
                        modelID = binascii.a2b_hex(modelID)
                        file2.write(payload)
                        file2.write(modelID)
                        _itemID = int(child[5+k*9].text)
                        itemID = hex(_itemID)[2:].zfill(4)
                        itemID = binascii.a2b_hex(itemID)
                    _itemType = int(child[6+k*9].text)
                    itemType = hex(_itemType)[2:].zfill(2)
                    itemType = binascii.a2b_hex(itemType)
                    _itemDataType = int(child[7+k*9].text)
                    itemDataType = hex(_itemDataType)[2:].zfill(2)
                    itemDataType = binascii.a2b_hex(itemDataType)
                    _itemIntPart = int(child[8+k*9].text)
                    itemIntPart = hex(_itemIntPart)[2:].zfill(2)
                    itemIntPart = binascii.a2b_hex(itemIntPart)
                    _itemInBusLsb = int(child[9+k*9].text)
                    itemInBusLsb = hex(_itemInBusLsb)[2:].zfill(8)
                    itemInBusLsb = binascii.a2b_hex(itemInBusLsb)
                    _itemInBusMsb = int(child[10+k*9].text)
                    itemInBusMsb = hex(_itemInBusMsb)[2:].zfill(8)
                    itemInBusMsb = binascii.a2b_hex(itemInBusMsb)
                    _itemOutBusLsb = int(child[11+k*9].text)
                    itemOutBusLsb = hex(_itemOutBusLsb)[2:].zfill(8)
                    itemOutBusLsb = binascii.a2b_hex(itemOutBusLsb)
                    _itemOutBusMsb = int(child[12+k*9].text)
                    itemOutBusMsb = hex(_itemOutBusMsb)[2:].zfill(8)
                    itemOutBusMsb = binascii.a2b_hex(itemOutBusMsb)
                    _itemValue = child[13+k*9].text
                    _itemValue = float(_itemValue)

                    #error checking for bus sizes
                    InputBusSize = _itemInBusMsb - _itemInBusLsb+1
                    OutputBusSize = _itemOutBusMsb - _itemOutBusLsb+1
                    print "Input Bus Size:", InputBusSize
                    print "Output Bus Size:", OutputBusSize
                    print "ItemData Type:", _itemDataType

                    #only for item type 2,  the input and the output bus has to be same
                    #for item 1, the output bus is not defined.
                    if InputBusSize != OutputBusSize and  _itemType == 2:
                        error_flag = 1
                        Inval_busSize = 1
                        print "Different Input and output Bus Size for item type 2!!!"
                    else:
                        print "Input and output bus match"
                        #error checking for data type and bus size mismatch
                        if _itemDataType == eight_bit_ufix and InputBusSize != 8:
                            print "Wrong bus size"
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == sixteen_bit_ufix  and InputBusSize != 16:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == twentyFour_bit_ufix and InputBusSize != 24:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == thirtyTwo_bit_ufix and InputBusSize != 32:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == eight_bit_sfix and InputBusSize != 8:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == sixteen_bit_sfix  and InputBusSize != 16:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == twentyFour_bit_sfix and InputBusSize != 24:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == thirtyTwo_bit_sfix and InputBusSize != 32:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == eight_bit_uint and InputBusSize != 8:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == sixteen_bit_uint  and InputBusSize != 16:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == twentyFour_bit_uint and InputBusSize != 24:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == thirtyTwo_bit_uint and InputBusSize != 32:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == eight_bit_sint and InputBusSize != 8:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == sixteen_bit_sint  and InputBusSize != 16:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == twentyFour_bit_sint and InputBusSize != 24:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == thirtyTwo_bit_sint and InputBusSize != 32:
                            error_flag = 1
                            dataType_busMismatch = 1
                        elif _itemDataType == thirtyTwo_bit_float and InputBusSize != 32:
                            eroor_flag = 1
                            dataType_busMismatch = 1
                    #error checking for invalid data type
                    if _itemDataType > no_of_dataType:
                        error_flag = 1
                        Inval_dataType = 1
                        print "ERROR DETECTED!!!! Invalid DataType"
                    # error for if msb is less than the lsb
                    if _itemInBusMsb < _itemInBusLsb:
                        error_flag = 1
                        inval_inLsb = 1
                    if _itemOutBusMsb < _itemOutBusLsb:
                        error_flag = 1
                        inval_outLsb = 1
                    if error_flag == 1:
                        break
                    #itemValue = hex(_itemValue)[2:].zfill(8)
                    if _itemValue>0:
                        print "string version of the _itemValue:", _itemValue
                        itemValue_intpart = int(_itemValue)
                        itemValue_fractionPart = float(_itemValue-itemValue_intpart)
                        print "fraction part:", itemValue_fractionPart
                        int_part_itemValue = int(_itemValue)

                        #checking for floating data type
                        #IEEE 32 bit float for positive values
                        if _itemDataType == thirtyTwo_bit_float: # i.e.16

                            fractionalPartBinary = decFractionToBinary(itemValue_fractionPart, _itemDataType, _itemIntPart)

                            itemValue_intpart = itemValue_intpart
                            print "thirty two bit floating point:"
                            print "fractional binary :" , fractionalPartBinary

                            sign = 0 # since the value is negative
                            #this method returns the hex representation of IEEE 754 floating number
                            itemValue = IEEE_float_hex_representation(itemValue_intpart, fractionalPartBinary, sign)
                            itemValue = binascii.a2b_hex(itemValue)

                        #---for other data types
                        else:
                            bin_int_part_itemValue = np.binary_repr(int_part_itemValue, width = _itemIntPart)
                            print "integer part binary repr:" ,bin_int_part_itemValue

                            fractionalPartBinary = decFractionToBinary(itemValue_fractionPart, _itemDataType, _itemIntPart)
                            _itemValueBinary = bin_int_part_itemValue + fractionalPartBinary
                            _itemValueInt = int(_itemValueBinary,2)
                            print "int final val:" , _itemValueInt
                            itemValue = hex(_itemValueInt )[2:].zfill(8)
                            itemValue = binascii.a2b_hex(itemValue)
                            print "final Hex: ", itemValue
                            print "Final binary stream: ", _itemValueBinary

                    elif _itemValue < 0:
                        print "Negative value:" ,_itemValue
                        print "Item Val negative: true"
                        _itemValue = -(_itemValue)
                        print "Value: ", _itemValue
                        itemValue_intpart = int(_itemValue)
                        itemValue_fractionPart = float(_itemValue-itemValue_intpart)
                        int_part_itemValue = int(_itemValue)

                        #checking for floating data type
                        if _itemDataType == thirtyTwo_bit_float: # i.e.16
                            fractionalPartBinary = decFractionToBinary(itemValue_fractionPart, _itemDataType, _itemIntPart)

                            itemValue_intpart = itemValue_intpart
                            print "thirty two bit floating point:"
                            print "fractional binary :" , fractionalPartBinary

                            sign = 1 # since the value is negative
                            #this method returns the hex representation of IEEE 754 floating number
                            itemValue = IEEE_float_hex_representation(itemValue_intpart, fractionalPartBinary, sign)
                            itemValue = binascii.a2b_hex(itemValue)


                        #---for other data types
                        else:
                            bin_int_part_itemValue = np.binary_repr(int_part_itemValue, width = _itemIntPart)

                            print "integer part binary repr:" ,bin_int_part_itemValue

                            fractionalPartBinary = decFractionToBinary(itemValue_fractionPart, _itemDataType, _itemIntPart)
                            if fractionalPartBinary == bin(0)[2:].zfill(1):
                                _itemValueBinary = bin_int_part_itemValue
                            else:
                                _itemValueBinary = bin_int_part_itemValue + fractionalPartBinary
                            _itemValueInt = int(_itemValueBinary,2)

                            bitString = _itemValueBinary

                            #since the value is negative, twos compliment need to be calculated to represent the negative value
                            flippedString = bitString.translate(maketrans("10","01"))
                            print "1's compliment binary", flippedString

                            twosCompl = int(flippedString,2) + 1
                            twosCompl = bin(twosCompl)[2:].zfill(8)
                            print "Twos Compl:", twosCompl
                            _itemValueBinary = twosCompl
                            _itemValueInt = int(_itemValueBinary,2)
                            print "int final val:" , _itemValueInt
                            print "Final binary stream: ", _itemValueBinary
                            itemValue = hex(_itemValueInt)[2:].zfill(8)
                            print "Final Hex:", itemValue
                            itemValue = binascii.a2b_hex(itemValue)

                    file2.write(itemID)
                    file2.write(itemType)
                    file2.write(itemDataType)
                    file2.write(itemIntPart)
                    file2.write(itemInBusLsb)
                    file2.write(itemInBusMsb)
                    file2.write(itemOutBusLsb)
                    file2.write(itemOutBusMsb)
                    file2.write(itemValue)
                break

                #file2.close()
            #defining Readback Time Window (RTW)
            if childSize == (6 + i) and _Cmd == def_RTW_para : #16
                #hex_convert()
                print i
                for k in range (0,i,1):
                    payloadSize = i
                    print "payloadSize:", payloadSize
                    payloadBytes = payloadSize * 2 + 9
                    #payload size in hex.
                    payload = hex(payloadBytes)[2:].zfill(8)
                    payload = binascii.a2b_hex(payload)
                    print k
                    if k == 0:
                        _endtimestamp = int(child[4].text)
                        endtimestamp = hex(_endtimestamp)[2:].zfill(16)
                        endtimestamp = binascii.a2b_hex(endtimestamp)
                        _samplingPeriod = int(child[5].text)
                        samplingPeriod = hex(_samplingPeriod)[2:].zfill(2)
                        sampiingPeriod = binascii.a2b_hex(samplingPeriod)
                        #error checking
                        print "endtimestamp:", _endtimestamp
                        print "timestamp:", _timestmp
                        if _endtimestamp < _timestmp:
                            error_flag = 1
                            timestmpError = 1

                        if _samplingPeriod == 0:
                            print "Sampling period error!!"
                            error_flag = 1
                            samplingPrdError = 1
                            break
                        else:
                            file2.write(payload)
                            file2.write(endtimestamp)
                            file2.write(samplingPeriod)

                    _itemID = int(child[6+k].text)
                    itemID = hex(_itemID)[2:].zfill(4)
                    itemID = binascii.a2b_hex(itemID)
                    file2.write(itemID)

                break

                #file2.close()
            #list variables
            elif childSize == (4 + i) and _Cmd == def_lsVar : #8
                #hex_convert()
                print i
                payloadSize = i
                print "payloadSize:", payloadSize
                payloadBytes = payloadSize * 2
                #payload size in hex.
                payload = hex(payloadBytes)[2:].zfill(8)
                payload = binascii.a2b_hex(payload)
                file2.write(payload)

                for k in range (0,i,1):
                    _itemID = int(child[4+k].text)
                    itemID = hex(_itemID)[2:].zfill(4)
                    itemID = binascii.a2b_hex(itemID)
                    file2.write(itemID)

                break

                #file2.close()

            #defining item value
            elif childSize == (4 + i*2) and _Cmd == def_itemVal : #33
                print 'itemval'
                #hex_convert()
                print i
                payloadSize = i
                print "payloadSize:", payloadSize
                # itemid and itemval takes 6 bytes and there is a single endtimestamp which requires 8 bytes
                payloadBytes = payloadSize * 6
                #payload size in hex.
                payload = hex(payloadBytes)[2:].zfill(8)
                payload = binascii.a2b_hex(payload)
                file2.write(payload)
                for k in range (0,i,1):
                    _itemID = int(child[4+k*2].text)
                    itemID = hex(_itemID)[2:].zfill(4)
                    itemID = binascii.a2b_hex(itemID)
                    _itemVal = int(child[5+k*2].text)
                    itemVal = hex(_itemVal)[2:].zfill(8)
                    itemVal = binascii.a2b_hex(itemVal)
                    file2.write(itemID)
                    file2.write(itemVal)

                break

                #file2.close()

            #defining sensory neuron results
            elif childSize == (4 + i*2) and _Cmd == SensoryNrnRslts : #33
                print 'Sensory neuron Results'
                #hex_convert()
                print i
                payloadSize = i
                print "payloadSize:", payloadSize
                # itemid and itemval takes 6 bytes and there is a single endtimestamp which requires 8 bytes
                payloadBytes = payloadSize * 6
                #payload size in hex.
                payload = hex(payloadBytes)[2:].zfill(8)
                payload = binascii.a2b_hex(payload)
                file2.write(payload)
                for k in range (0,i,1):
                    _itemID = int(child[4+k*2].text)
                    itemID = hex(_itemID)[2:].zfill(4)
                    itemID = binascii.a2b_hex(itemID)
                    _itemVal = int(child[5+k*2].text)
                    itemVal = hex(_itemVal)[2:].zfill(8)
                    itemVal = binascii.a2b_hex(itemVal)
                    file2.write(itemID)
                    file2.write(itemVal)
                break

                #file2.close()

            #defining RTW results
            elif childSize == (5 + i*2) and _Cmd == def_RTWResults : #4
                print 'itemRTWRes'
                #hex_convert()
                print i
                payloadSize = i
                print "payloadSize:", payloadSize
                # itemid and itemval takes 6 bytes and there is a single endtimestamp which requires 8 bytes
                payloadBytes = payloadSize * 6 + 8
                #payload size in hex.
                payload = hex(payloadBytes)[2:].zfill(8)
                payload = binascii.a2b_hex(payload)
                file2.write(payload)
                for k in range (0,i,1):
                    if k == 0:
                        _endtimestamp = int(child[4].text)
                        endtimestamp = hex(_endtimestamp)[2:].zfill(16)
                        endtimestamp = binascii.a2b_hex(endtimestamp)
                        file2.write(endtimestamp)
                    _itemID = int(child[5+k*2].text)
                    itemID = hex(_itemID)[2:].zfill(4)
                    itemID = binascii.a2b_hex(itemID)
                    _itemVal = int(child[6+k*2].text)
                    itemVal = hex(_itemVal)[2:].zfill(8)
                    itemVal = binascii.a2b_hex(itemVal)
                    file2.write(itemID)
                    file2.write(itemVal)

                break
                #file2.close()
#-----------Network Topology Configuration
    #converting xml to hex with neuron spikes representated as one hot representation
    if _Cmd == ConfigNetworkTplgy or _Cmd == NewBCP:
        #creating an array with fixed size of 304
        lst = [0] * 304
        #the neuron ranges from 1-302 but the total bytes used for onehot is 38 i.e. 304 hex nums
        #since 303 and 304 are always zero, they are set to zeros.
        spikes =bin(0)[2:].zfill(2)
        hexNum = ''
        for i in range (0,304,1):
            #print "childsize:", childSize
            if childSize == (4 + i):
                print "child Size", childSize
                print 'Sensory neuron Results'
                print "printing i,", i
                #hex_convert()

                payloadSize = 38
                print "payloadSize:", payloadSize
                #payload size in hex.
                payload = hex(payloadSize)[2:].zfill(8)
                payload = binascii.a2b_hex(payload)
                file2.write(payload)
                #looping through the preneuron id, 4 is deducted we are not looking through the headers
                for k in range (0,childSize-4,1):
                    #print k
                    _preNeuronId = int(child[4 + k].text)
                    print "preNeuronId,", _preNeuronId
                    lst[_preNeuronId -1] = 1
                #ranges from 301 to 0 making 302 neurons
                for j in range (301,-1,-1):
                    print j
                    print lst[j]
                    spikes = spikes + bin(lst[j])[2:].zfill(1)
                print spikes
        #_spikes = int(spikes)
        #print "_spikes", _spikes
        for m in range (0, len(spikes), 4):
            print spikes[m:m+4]
            #takes a chunk of 4 binary numbers and convert that to a hex number
            hexChar = int(spikes[m:m+4],2)
            hexRep = hex(hexChar)[2:].zfill(1)
            print "Hex Representation", hexRep
            hexNum = hexNum + hexRep

        #print hexNum
        #converting the final hex stream into a raw hex numbers
        OneHotRepHex = binascii.a2b_hex(hexNum)
        file2.write(OneHotRepHex)


    #print "fixed payload starts here!!!"

    if childSize == 140 and _Cmd == MuscleCollatedRes:

        if _Cmd == MuscleCollatedRes:
            _endTimestamp = int(child[4].text)
            endTimestamp = hex(_endTimestamp)[2:].zfill(16)
            endTimestamp =binascii.a2b_hex(endTimestamp)
            _payload = 143
            payload = hex(_payload)[2:].zfill(8)
            payload = binascii.a2b_hex(payload)
            #hex_convert()

            file2.write(payload)
            file2.write(endTimestamp)
            for i in range (1,136,1):
                muscleValue = int(child[i+4].text)
                MuscleValue = hex(muscleValue)[2:].zfill(2)
                MuscleValue = binascii.a2b_hex(MuscleValue)
                file2.write(MuscleValue)

        else:
            error_flag = 1
            InvalCommand = 1

    elif childSize == 10 and _Cmd == SimParaInit:
        if _Cmd == SimParaInit: # total of 39 bytes, with payload of 22 bytes
            #global timeStep_size, Cycles_Num, Sim_id, Watchdog_prd
            _timeStep_size = int(child[4].text)
            _Cycles_Num = int(child[5].text)
            _Sim_id = int(child[6].text)
            _Watchdog_prd = int(child[7].text)
            _Neuron_num = int(child[8].text)
            _Muscle_num = int(child[9].text)

            timeStep_size = hex(_timeStep_size)[2:].zfill(8)
            timeStep_size = binascii.a2b_hex(timeStep_size)
            Cycles_Num = hex(_Cycles_Num)[2:].zfill(16)
            Cycles_Num = binascii.a2b_hex(Cycles_Num)
            Sim_id = hex(_Sim_id)[2:].zfill(16)
            Sim_id = binascii.a2b_hex(Sim_id)
            Watchdog_prd = hex(_Watchdog_prd)[2:].zfill(4)
            Watchdog_prd = binascii.a2b_hex(Watchdog_prd)
            Neuron_num = hex(_Neuron_num)[2:].zfill(4)
            Neuron_num = binascii.a2b_hex(Neuron_num)
            Muscle_num = hex(_Neuron_num)[2:].zfill(2)
            payload = hex(25)[2:].zfill(8) # for initialisation of fixed para , the payload bytes in hex is 16 which is 22 in dec
            payload = binascii.a2b_hex(payload)

            #hex_convert()
            file2.write(payload)
            file2.write(timeStep_size)
            file2.write(Cycles_Num)
            file2.write(Sim_id)
            file2.write(Watchdog_prd)
            file2.write(Neuron_num)
            file2.write(Muscle_num)

            #file2.close()
    elif childSize == 7 and _Cmd == preconfStim:
        print "pre configuration stimuli"
        #global end_Tmsp,item_val
        _end_Tmsp = int(child[4].text)
        _item_val = int(child[5].text)
        end_Tmsp = hex(_end_Tmsp)[2:].zfill(4)
        end_Tmsp = binascii.a2b_hex(end_Tmsp)
        item_val = hex(_item_val)[2:].zfill(8)
        item_val = binascii.a2b_hex(item_val)
        payload = hex(6)[2:].zfill(8)
        payload = binascii.a2b_hex(payload)
        print "Checking for errors!!"
        #error checking for timestamp
        if _end_Tmsp < timestmp:
            error_flag = 1
            timestmpError = 1
        else:

            file2.write(payload)
            file2.write(end_Tmsp)
            file2.write(item_val)

            #file2.close()

    elif childSize == 6 and (_Cmd == sdcpDisp or _Cmd == RuntimeStimulus):
        if _Cmd == sdcpDisp: #21 bytes total packet size # paylod of 4
            #global sdcp_StartId
            #global sdcp_EndId
            _sdcp_StartId = int(child[4].text)
            _sdcp_EndId = int(child[5].text)
            sdcp_StartId = hex(_sdcp_StartId)[2:].zfill(4)
            sdcp_StartId = binascii.a2b_hex(sdcp_StartId)
            sdcp_EndId = hex(_sdcp_EndId)[2:].zfill(4)
            sdcp_EndId = binascii.a2b_hex(sdcp_EndId)
            payload = hex(4)[2:].zfill(8)
            payload = binascii.a2b_hex(payload)
            #hex_convert()
            file2.write(payload)
            file2.write(sdcp_StartId)
            file2.write(sdcp_EndId)

            #file2.close()
        if _Cmd == RuntimeStimulus: #23 bytes total packet size #payload of 6 bytes
            #global Item_Id, Item_Value
            _Item_Id = int(child[4].text)
            _Item_Value = int(child[5].text)
            Item_Id = hex(_Item_Id)[2:].zfill(4)
            Item_Id = binascii.a2b_hex(Item_Id)
            Item_Value = hex(_Item_Value)[2:].zfill(8)
            Item_Value = binascii.a2b_hex(Item_Value)
            payload = hex(6)[2:].zfill(8)
            payload = binascii.a2b_hex(payload)

            #hex_convert()
            file2.write(payload)
            file2.write(Item_Id)
            file2.write(Item_Value)

            #file2.close()


    elif childSize == 5 or (_Cmd ==  Query or _Cmd == SpkngNrnRslts or _Cmd ==  MuscleResults or _Cmd == sdcpID or _Cmd == SimStateResults or _Cmd == SimulationId or _Cmd == NeuronControllerId):
        #payload is 2 bytes for 19 bytes packet and 1 or 18
        if _Cmd == Query:
            payload = hex(2)[2:].zfill(8)
            payload = binascii.a2b_hex(payload)

            query_type = int(child[4].text)
            queryType = hex(query_type)[2:].zfill(4)
            QueryType = binascii.a2b_hex(queryType)

            #hex_convert()
            file2.write(payload)
            file2.write(QueryType)

            #file2.close()

        elif _Cmd == SpkngNrnRslts:
            #global spike
            _spike = int(child[4].text)
            spike = hex(_spike)[2:].zfill(2)
            spike = binascii.a2b_hex(spike)
            payload = hex(1)[2:].zfill(8)
            payload = binascii.a2b_hex(payload)
            #hex_convert()
            file2.write(payload)
            file2.write(spike)

            #file2.close()
        elif _Cmd == MuscleResults:
            print MuscleResults
            #global forceValue
            _forceValue = int(child[4].text)
            print "force Value",_forceValue
            forceValue = hex(_forceValue)[2:].zfill(4)
            print forceValue
            forceValue = binascii.a2b_hex(forceValue)
            payload = hex(2)[2:].zfill(8)
            payload = binascii.a2b_hex(payload)
            #hex_convert()
            file2.write(payload)
            file2.write(forceValue)

            #file2.close()
        elif _Cmd == sdcpID:
            #global sdcp_id
            _sdcp_id = int(child[4].text)
            sdcp_id = hex(_sdcp_id)[2:].zfill(4)
            sdcp_id = binascii.a2b_hex(sdcp_id)
            payload = hex(2)[2:].zfill(8)
            payload = binascii.a2b_hex(payload)
            #hex_convert()
            file2.write(payload)
            file2.write(sdcp_id)

            #file2.close()
        elif _Cmd == SimStateResults:
            payload = hex(1)[2:].zfill(8)
            payload = binascii.a2b_hex(payload)
            current_State = int(child[4].text)
            currentState = hex(current_State)[2:].zfill(2)
            currentState = binascii.a2b_hex(currentState)
            #hex_convert()
            file2.write(payload)
            file2.write(currentState)

            #file2.close()
        elif _Cmd == SimulationId:
            payload = hex(2)[2:].zfill(8)
            payload = binascii.a2b_hex(payload)
            simID = int(child[4].text)
            simulationID = hex(simID)[2:].zfill(2)
            simulationID = binascii.a2b_hex(simulationID)
            #hex_convert()
            file2.write(payload)
            file2.write(simulationID)

            #file2.close()
        elif _Cmd == NeuronControllerId:
            payload = hex(1)[2:].zfill(8)
            payload = binascii.a2b_hex(payload)

            version_id = int(child[4].text)
            version_ID = hex(version_id)[2:].zfill(2)
            versionID = binascii.a2b_hex(version_ID)

            #hex_convert()
            file2.write(payload)
            file2.write(versionID)

            #file2.close()

    elif childSize == 4 and \
        (_Cmd == startSim or
        _Cmd == StopSim or
        _Cmd == AbortSim or
        _Cmd == Reset or
        _Cmd == SynapticInStates or
        _Cmd == MuscleParam or
        _Cmd == EndSim or
        _Cmd == UploadComplete or
        _Cmd == NeuronControllerId):

        print "WITHOUT PAYLOAD"
        payload = hex(0)[2:].zfill(8)
        payload = binascii.a2b_hex(payload)
        #hex_convert()
        file2.write(payload)
        #file2.close()
    else:
        if _Cmd < 0 or _Cmd > 31:
            print "Invalid Command!!!"
            error_flag = 1
            InvalCommand = 1
            print "ERROR FLAG HIGH"
    print "error Flag:", error_flag
    if error_flag == 1:
        errorCheck(_destDev,_srcDev,_timestmp,_Cmd,sys.argv[3])
    else:
        print "CONVERSION SUCCESSFUL!! NO ERRORS ARE FOUND"


#this function opens the hex file to write the hex values
#the header information is always constant so the header information is embedded in this function as it repeats for all the packets
#the payload information is not in the xml so it needs to calculated for each packet separately
def hex_convert():
    file2
    file2.write(destDev)
    file2.write(srcDev)
    file2.write(Cmd)
    file2.write(timestmp)
    #file2.write(payload)

#function that converts dec into unsigned representation
def unsigned_representation(intVal, size, int_part):
    print "inside the function"
    print intVal
    print "SIze : ",size
    integerValue = intVal
    binaryStream = bin(integerValue)[2:].zfill(size)
    print "binary stream: ",binaryStream
    print "binary msb: ", binaryStream[0]

    unsignedVal = 0.0
    count = 0
    power = int_part -1
    for i in range(0,size,1):
        print i , binaryStream[i]
        count = count +1
        print "count :",count
        print "integer size: ", int_part
        print "power :",(power -i)
        binary =  int(binaryStream[i])*2**(power-i)
        #binary = int(binary,2)
        print "binary dec conversion:" , binary

        unsignedVal = unsignedVal + binary
    print unsignedVal
    return unsignedVal
#--------end of method==-----------

#methodd for signed data types.

def signed_representation(intVal,size,int_part):

    print "inside the function for signed data type"
    print intVal
    print "SIze : ",size
    binaryStream = bin(intVal)[2:].zfill(size)
    print "binary stream: ",binaryStream

    signedVal = 0.0
    count = 0
    power = int_part - 1

    print binaryStream[0]
    if binaryStream[0] == bin(1)[2:].zfill(1):

        print "inside singed prt"
        print "original BInary stream", binaryStream
        print "-ve integer"

        bitString = binaryStream
        flippedString = bitString.translate(maketrans("10","01"))
        print "1's compliment binary", flippedString

        twosCompl = int(flippedString,2) + 1
        twosCompl = bin(twosCompl)[2:].zfill(8)
        print twosCompl
        integerVal = int(twosCompl,2)
        signedVal = -(unsigned_representation(integerVal, size, int_part))

    if binaryStream[0] == bin(0)[2:].zfill(1):
        #size = size-1
        signedVal = unsigned_representation(intVal, size, int_part)


    return signedVal


#------end of method


#--------single precision floating point representation ---
def IEEE_float_representation(Item_Value):
    bias = 127 # bias for 32 bit floating point is 127
    sign_bit = Item_Value[0]
    Exponent_bits = Item_Value[1:9]
    Mantissa_bits = Item_Value[9:32]
    print "Sign bit: ", sign_bit
    print "Exponent bits: ", Exponent_bits
    print "Mantissa bits: ", Mantissa_bits

    Exponent = int(Exponent_bits,2) - bias
    print "Exponent:" , Exponent

    # the decimal point shifted 3 places towards the LSB to get the fractional part
    # we add 1 to the fractional part and shift the decimal point as per the exponent bits
    if Exponent > 0 or Exponent == 0:
        Fractional_part = Item_Value[Exponent +9:32]
        Shifted_bits = Item_Value[9 :9 + Exponent]
        Integer_part = bin(1)[2:].zfill(1)
        IEEE_Rep_integer = Integer_part + Shifted_bits
    elif Exponent < 0:
        #negative expnent means the integer part is zero
        #so just the fractional part is calculated and then the final value is multiplied with 2^(exponent)
        print "Negative Exponent"
        Fractional_part = Item_Value[ 9:32]
        #Shifted_bits = Item_Value[9 +Exponent : 9 ]
        #we keep the integer part as implied 1 to meet the IEEE format
        # the format should look like 1.something
        # for negative exponent. 1.somthing is finlly multiplied with 2^exponent to give, 0.something...
        Integer_part = bin(1)[2:].zfill(1)
        IEEE_Rep_integer = Integer_part
    #print "SHifted bits: ", Shifted_bits

    print "Fractional Part: ", Fractional_part
    print "Integer Part: ", Integer_part

    print "IEEE representation Integer: ", IEEE_Rep_integer

    IEEE_Rep_integer = int(IEEE_Rep_integer,2)
    print "IEEE rep int in dec: ",IEEE_Rep_integer

    fractionalPart_size = len(Fractional_part)
    print "Fractional Part size", fractionalPart_size
    count = 0
    fractional_Value = 0
    #running computation on the fractional part
    for i in range(0,fractionalPart_size,1):

        print i , Fractional_part[i]
        count = count +1
        print "count :",count

        fraction_Value =  int(Fractional_part[i])*2**(-(i+1))
        #binary = int(binary,2)
        print "binary dec conversion:" , fraction_Value
        fractional_Value +=fraction_Value

    IEEE_Rep_num = IEEE_Rep_integer + fractional_Value

    #checking the sign bit for negative or positive value
    if sign_bit == bin(1)[2:].zfill(1):
        IEEE_Rep_num = -IEEE_Rep_num
        print "Negative number: ", IEEE_Rep_num
    #for negative exponent, the final result is multiplied with 2^exponent.
    # this is  special case,
    if Exponent <0:
        IEEE_Rep_num = (IEEE_Rep_num)*2**(Exponent)
    print "IEEE rep :", IEEE_Rep_num
    return IEEE_Rep_num

#-------------------hex to floating point

#this method takes sign, exponent and mantissa parts as arguements
'''
#########################################################################################
#This function converts the float hex chars to its corresponding decimal represenation
#########################################################################################
'''
def IEEE_float_hex_representation(_itemValue_intpart, fractionalpartBinary, sign):

    #converting hex characters to floating point numbers

    _itemValue_intpart_binary = np.binary_repr(_itemValue_intpart)
    print "integer part of float:", _itemValue_intpart_binary
    print "fractiona Part: " , fractionalpartBinary
    size_of_intPart = len(_itemValue_intpart_binary)
    print " size of the integer part: ", size_of_intPart
    bias = 127 # default bias for 32 bit single precision floating point
    #shift is the number of shifts the decimal point needs to be carried out to form a normal form
    # and create a 1.FF.. IEEE float format
    dec_shift =0

    if _itemValue_intpart == 0:
        #exponent_val_bin = bin(exponent_val)[2:].zfill(8)
        #print "exponent -- ", exponent_val_bin

        for i in range (0,len(fractionalpartBinary),1):
            dec_shift = dec_shift + 1
            if fractionalpartBinary[i] == bin(1)[2:].zfill(1):
                break
        print "shifted: ", dec_shift
        exponent_val = bias - dec_shift
        print "exponent value: ", exponent_val


    else:
        shift = size_of_intPart - 1 # for 4 bits, the shifting has to be done all the way upto 3 places so that we get 1.110 from 1110

        #its is relationship between bias and exponent with shift
        exponent_val = bias + shift # for shift 3, exponent becomes, 127+3 = 130. exponent value is 130 which is 10000010

    exponent_val_bin = bin(exponent_val)[2:].zfill(8)
    sign = bin(sign)[2:].zfill(1)
    #exponent val < 127 means that the integer part is zero
    #so we will only look at the fractional part without any shifting
    if exponent_val <= bias:
        print "exponent val less than 127"
        print "Fractional Prt:", fractionalpartBinary
        fractionalpartBinary =  fractionalpartBinary[dec_shift:23+dec_shift]


        #implied int as 1 to meet the format
        _itemValue_intpart_binary = bin(1)[2:].zfill(1)
        print "dec shift:", dec_shift
        print "new fractional part: ", fractionalpartBinary

    #exponent_val >=127 means that the integer part is non zero, hence decimal point shifting is carried out
    elif exponent_val > bias:

        shiftedPart = _itemValue_intpart_binary[1:size_of_intPart]
        print "shifted bits: ", shiftedPart
        # the shifted part is added to the fractional part which creates the mantissa of the float
        fractionalpartBinary = shiftedPart + fractionalpartBinary


        fractionalSize = len(fractionalpartBinary)
        #the mantissa will always be 23 bits in size.
        fractionalpartBinary = fractionalpartBinary[0:fractionalSize - shift]

    print "Sign: ",sign
    print "Exponent: ", exponent_val
    print "fractionalpartBinary", fractionalpartBinary

    Final_binary_representation = sign + exponent_val_bin + fractionalpartBinary
    print " final binary rep: ", Final_binary_representation

    Final_hex_representation = hex(int(Final_binary_representation,2))[2:].zfill(8)
    print "final hex representation: ", Final_hex_representation

    return Final_hex_representation

#this method converts fractinal part in decimal to binary representation
def decFractionToBinary(itemValue_fractionPart, _itemDataType, _itemIntPart):
    count  = 0
    binary_rep = ''
    finishFlag = 0
    limit = 0
    loop = 0
    floatingFlag = 0

    #the fractiona part is multiplied by 2 and is done as long as the frctinal part is either 1 or 0

    while itemValue_fractionPart!=1 and itemValue_fractionPart!=0:

        print "fractional part: " , itemValue_fractionPart
        newVal = float(itemValue_fractionPart * 2)
        #if the product gives a 1.something, then that particular bit is set to 1
        #if less than zero , that bit is set to 0
        print "new value:", newVal
        loop= loop +1
        print "number of loops: ", loop
        print " "

        if(newVal>1):
            #in 1.something, 1 is truncated and the the process is carried out again
            # taking int value gives only the int part, discarding the fractional part
            int_part = int(newVal)
            print "integer part:" , int_part
            itemValue_fractionPart = newVal - int_part # if the new val is 1 it means the bit should be 1

            print "New fractional part:" , itemValue_fractionPart
            count = count + 1
            #print int_part
            print "count: ",count
            binary_rep += bin(int_part)[2:].zfill(1)
            print "binary rep: ",binary_rep
            print " "

        elif (newVal<1):
            int_part = 0
            count = count + 1
            print"integer part with less than 1  :", int_part
            print "count: ",count
            itemValue_fractionPart = newVal
            binary_rep += bin(int_part)[2:].zfill(1)
            print "binary rep: ",binary_rep
            print " "
        elif newVal == 1 and finishFlag == 0:
            int_part = 1
            count = count + 1
            print "one:", int_part
            print "count: ",count
            binary_rep += bin(int_part)[2:].zfill(1)
            print "binary rep: ",binary_rep
            print " "
            finishFlag = 1


        elif newVal == 1 and finishFlag == 1    :
            int_part = 0
            count = count + 1
            binary_rep += bin(int_part)[2:].zfill(1)
            print "count: ",count
            print "binary rep: ",binary_rep
            print " "
            #break
        if _itemDataType == eight_bit_ufix:
            limit = 8 - _itemIntPart
            print "Limit: ", limit
        elif _itemDataType == sixteen_bit_ufix:
            limit = 16 -  _itemIntPart
        elif _itemDataType == twentyFour_bit_ufix:
            limit = 24 - _itemIntPart
        elif _itemDataType == thirtyTwo_bit_ufix:
            limit = 32- _itemIntPart
        elif _itemDataType == eight_bit_ufix:
            limit = 8- _itemIntPart
        elif _itemDataType == sixteen_bit_sfix:
            limit = 16- _itemIntPart
        elif _itemDataType == twentyFour_bit_sfix:
            limit = 24- _itemIntPart
        elif _itemDataType == thirtyTwo_bit_sfix:
            limit = 32- _itemIntPart
        elif _itemDataType == eight_bit_uint:
            limit = 8- _itemIntPart
        elif _itemDataType == sixteen_bit_uint:
            limit = 16- _itemIntPart
        elif _itemDataType == twentyFour_bit_uint:
            limit = 24- _itemIntPart
        elif _itemDataType == thirtyTwo_bit_uint:
            limit = 32- _itemIntPart
        elif _itemDataType == eight_bit_sint:
            limit = 8- _itemIntPart
        elif _itemDataType == sixteen_bit_sint:
            limit = 16- _itemIntPart
        elif _itemDataType == twentyFour_bit_sint:
            limit = 24- _itemIntPart
        elif _itemDataType == thirtyTwo_bit_sint:
            limit = 32 - _itemIntPart
        elif _itemDataType == thirtyTwo_bit_float and floatingFlag == 0:
            print "FLOATING POINT REPRESENTATION!!!"
            if binary_rep[len(binary_rep)-1] == bin(1)[2:].zfill(1):

                limit = 23 + count # for floating point 23 bits is the mantissa bits representing fractions
                print "floating point limit ", limit
                floatingFlag =1
        if count == limit:
            break
            print "fract bin rep: ", binary_rep
    if itemValue_fractionPart == 0:

        if _itemDataType == eight_bit_ufix:
            size = 8 - _itemIntPart

        elif _itemDataType == sixteen_bit_ufix:
            size = 16 - _itemIntPart
        elif _itemDataType == twentyFour_bit_ufix:
            size = 24 -  _itemIntPart
        elif _itemDataType == thirtyTwo_bit_ufix:
            size = 32 - _itemIntPart
        elif _itemDataType == eight_bit_ufix:
            size = 8 - _itemIntPart
        elif _itemDataType == sixteen_bit_sfix:
            size = 16 - _itemIntPart
        elif _itemDataType == twentyFour_bit_sfix:
            size = 24 - _itemIntPart
        elif _itemDataType == thirtyTwo_bit_sfix:
            size = 32 - _itemIntPart
        elif _itemDataType == eight_bit_uint:
            size = 8 - _itemIntPart
        elif _itemDataType == sixteen_bit_uint:
            size = 16 - _itemIntPart
        elif _itemDataType == twentyFour_bit_uint:
            size = 24 - _itemIntPart
        elif _itemDataType == thirtyTwo_bit_uint:
            size = 32 - _itemIntPart
        elif _itemDataType == eight_bit_sint:
            size = 8 - _itemIntPart
        elif _itemDataType == sixteen_bit_sint:
            size = 16 - _itemIntPart
        elif _itemDataType == twentyFour_bit_sint:
            size = 24 - _itemIntPart
        elif _itemDataType == thirtyTwo_bit_sint:
            size = 32 - _itemIntPart
        elif _itemDataType == thirtyTwo_bit_float:
            size = 23# for floating point 23 bits is the mantissa bits representing fractions

        binary_rep = bin(0)[2:].zfill(size)

    return binary_rep

#--------------end of function


##########################################################
# This is the functions that takes in hex chars from the hardware and
# generates corresponding xml file
##########################################################
def hexToXML(NewRawHex):
    #declaring global variables
    global dID, sID, cMD, tStp, payload
    global error_flag

    error_flag = 0
    InvalCommand = 0
    invalidCommandNopld = 0
    Inval_packet_length = 0
    Inval_query = 0
    Inval_dataType = 0
    nval_busSize = 0
    dataType_busMismatch = 0
    inval_inLsb = 0
    inval_outLsb = 0
    timestmpError = 0
    samplingPrdError = 0
    inval_destDev = 0
    inval_srcDev = 0
    Inval_busSize = 0

    #variables storing different fields of the packet
    destField = NewRawHex[0:4]
    dID = int(destField,16)
    srcField  = NewRawHex[4:8]
    sID = int(srcField,16)
    cmdField  = NewRawHex[8:10]
    cMD = int(cmdField,16)
    tsmpField = NewRawHex[10:26]
    tStp = int(tsmpField,16)
    pldField  = NewRawHex[26:34]
    payload = int(pldField,16)
    hexfileSize = len(NewRawHex)
    print "destID      :", destField
    print "sourceID    :", srcField
    print "command     :", cmdField
    print "timestamp   :", tsmpField
    print "payload     :", pldField
    print "Number of Hex characters: ", hexfileSize
    #print "Command in dec: ", int(cmdField,16)

    command = int(cmdField,16)
    print "Command:",command
    print "checking for errors"
    #Error handling
    if (dID>-1 and dID < 438 ) or (dID>65531 and dID < 65536):
        error_flag = 0

    else:
        print "Error detected in the destination device id"
        error_flag = 1
        inval_destDev = 1

    #since source id cannot be a broadcast address, source id 0 is considered as an invalid id
    if (sID > 0 and sID < 438) or (sID>65531 and sID < 65536):
        error_flag = 0
    else:
        print "Error detected in the destination device id"
        error_flag = 1
        inval_srcDev = 1

    if command < 0 or command > 31:
            print "Invalid Command!!!"
            error_flag = 1
            InvalCommand = 1
            print "ERROR FLAG HIGH"

    print "error Flag:", error_flag

    #print "Command: ", command
    #print "Hex file size:", hexfileSize
    #print "Opening File"

    if error_flag == 0:
      #*********************SPIKING NEURON RESULTS SENT TO THE UPPER LAYER*************************
      #-----------------------fixed payload with packet size of 5 bytes-----------------------------
        #-------------------------Spiking neuron results are represented as a one hot to reduce the file size
        if hexfileSize == pktSize_fxd55 and command == SpkngNrnRslts:
            #================================Load SDCP
            #this function opens the xml file and writes the packet header in xml format
            packetHeaderXML()

            preNeuronIdField = NewRawHex[34:110]
            preNeuronId = int(preNeuronIdField,16)

            preNeuronId_bin = bin(preNeuronId)[2:].zfill(8)
            #print "Inside the configure network topology"
            newBinaryHolder =''
            #payload starts from 35th position and ends at 110
            for i in range (110,34,-2):
                print i
                #it is i+2 because i:i+1 is just same as writing i+1 only,
                #eg if i = 35 then, NewRawHex[i:i+1] is NewRawHex[36 to 36]

                    #print i
                    #takes two hex characters and convert it into int then 8 binary bits
                int_holder = int(NewRawHex[i-2:i],16)
                #print "integer val:", int_holder
                #print "___",int_holder


                bin_holder = bin(int_holder)[2:].zfill(8)
                #print "@@@@",bin_holder
                #since it is one hot represenation the hex numbers are read from the LSB
                for k in range (7,-1,-1):
                    #print "k", k
                    bin_holder[k]
                    #print "binary Holder: ",bin_holder[k]
                    newBinaryHolder = newBinaryHolder + bin_holder[k]
                #print "New BInary:", newBinaryHolder
            sizeOfBinary = len(newBinaryHolder)

            for j in range (0,sizeOfBinary,1):
                if newBinaryHolder[j] == bin(1)[2:].zfill(1) :
                    neuronSpikeId = j + 1
                    #print "spiking neuron:", neuronSpikeId
                    if command == ConfigNetworkTplgy:
                        file2.write('\t<preNeuronId>%s</preNeuronId>\n'%neuronSpikeId)
                    elif command == SpkngNrnRslts:
                        file2.write('\t<prevTmspSpikes>%s</prevTmspSpikes>\n'%neuronSpikeId)
            file2.write('</packet>\n')
                #file2.close()

    #-----------------------fixed payload with packet size of 160 bytes-----------------------------
    #-----------------------Muscle Collated Results------------------------

        elif hexfileSize == pktSize_fxd287 and command == MuscleCollatedRes:
            #================================Load SDCP
            #this function opens the xml file and writes the packet header in xml format
            packetHeaderXML()
            print "Muscle Collated Results!!!"
            print "\nNumber of bytes :", pktSize_fxd287/2
            print "Command         :", cmdField
            print "\n initialize Simulation Parameter!!\n"
                #MuscleValues = [135]
            for i in range (0,135,1):
                Muscle_Value = NewRawHex[34 + i*4 : 34 + i*4 +4]
                print "Muscle Value: ", Muscle_Value
                MuscleValue = int(Muscle_Value,16)
                #MuscleValues = MuscleValues[MuscleValue]
                #muscleID = [1:135]
                file2.write('\t<Muscle%dValue>%d</Muscle%dValue>\n'%(i+1,MuscleValue,i+1))
                #print MuscleValues
                file2.write('</packet>\n')
                #file2.close()

    else:
        errorCheck(dID,sID,tStp,command,sys.argv[3])
        file4.write('</ErrorLog>\n')
        file4.close()


'''
#########################################################################################
#-----------------------Error Handling and error message log
#-----------------------Errors are logged into a Error.xml file with error id and type of error
#########################################################################################
'''
def errorCheck(dest,src,timestamp,command,conversion):
    print "Error checking Active!!"
    print "error flag:", error_flag

    errorDetected = 0
    dID = dest
    sID = src
    tStp = timestamp
    cMD = command


    if error_flag == 1:
        #file for logging the errors--------------
        errorDetected = 1
        if conversion == sys.argv[3] and os.path.exists(sys.argv[3]):
            os.remove(sys.argv[3])
        elif conversion == sys.argv[2] and os.path.exists(sys.argv[2]):
            os.remove(sys.argv[2])
        #os.remove(sys.argv[3])

        print "Conversion Unsuccessfull!!!!!!!"
        print "There are errors!!! Pease refer to the ErrorLog.xml file for details"
        #print "error bus",Inval_busSize
        ErrorID = 0
        Error=3
        file4.write('\t<Error>\n')
        file4.write('\t\t<destDevice>%s</destDevice>\n'%dID)
        file4.write('\t\t<sourceDevice>%s</sourceDevice>\n'%sID)
        file4.write('\t\t<command>%s</command>\n'%Error)
        file4.write('\t\t<timestamp>%s</timestamp>\n'%tStp)


         #displaying error message for correct command
        if (command <0 or command > 31 or InvalCommand == 1) :
            print "Error:      Invalid packet command"
            ErrorID = 255
                #stores the error in the log file for further analysis

            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Out of Range Command</ErrorName>\n')

            #displaying appropriate error message
        if invalidCommandNopld == 1:
            print "Error:      Invalid command for zeropayload"
            ErrorID = 3
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Invalid command for zero payload</ErrorName>\n')
        if Inval_packet_length == 1:
            print "Error:         Invalid Length "
            ErrorID = '04'
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Invalid Length</ErrorName>\n')
        if Inval_query == 1:
            print "Error:     Invalid Query Number"
            ErrorID = 5
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Invalid Query Number</ErrorName>\n')
        if Inval_dataType == 1:
            print "Error:     Invalid Data Type"
            ErrorID = '10'
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Invalid DataType</ErrorName>\n')
        if Inval_busSize == 1:
            print "Error:    Different Input and Output Bus Sizes!!"
            ErrorID = '01'
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Different Input and output Bus Sizees</ErrorName>\n')
        if dataType_busMismatch  == 1:
            print "Error:    Data Type and bus size mismatch!!"
            ErrorID = '06'
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Data Type and Bus Size Mismatch</ErrorName>\n')
        if inval_outLsb == 1:
            print "Error:    output LSB is larger than the output MSB"
            ErrorID = '01'
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Output LSB is larger than the output MSB</ErrorName>\n')
        if inval_inLsb == 1:
            print "Error:   input LSB is larger than the input MSB"
            ErrorID = '02'
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Input LSB is larger than the input MSB</ErrorName>\n')
        if timestmpError == 1:
            print "Error:   EndTimestamp is less than the start timestamp"
            ErrorID = '03'
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Endtimestamp error less than starttimestamp</ErrorName>\n')
        if samplingPrdError == 1:
            print "Error:   Sampling time must be greater than zero!!"
            ErrorID = '02'
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Sampling time must be greater than zero</ErrorName>\n')
        if inval_destDev == 1:
            print "Error:   Invalid Destination ID!!"
            ErrorID = '253'
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Invalid Destination Id</ErrorName>\n')
        if inval_srcDev == 1:
            print "Error:   Invalid source ID!!"
            ErrorID = '252'
            file4.write('\t\t<errorType>%s|%s</errorType>\n'%(cMD,ErrorID))
            file4.write('\t\t<ErrorName>Invalid source Id</ErrorName>\n')


        file4.write('\t</Error>\n')

        #file4.close()


'''
#****************************************************************************************************************************
#                            START OF HEX TO XML CONVERSION FOR ZERO, FIXED AND VARIABLE PAYLOADS
#****************************************************************************************************************************
'''
if(sys.argv[1])=='-X' or (sys.argv[1])=='-x':

    file4 = open("ErrorLog.xml",'w+')
    file4.write('<?xml version="1.0" encoding="UTF-8"?>\n')
    file4.write('<ErrorLog>\n')


    #opening the file using sys.argv
    file1 = open(sys.argv[2],"rb")
    #reading block size
    blocksize = 450000000 #56.25 MB
    #reads from that block from the file and concatenates the values into string
    with file1:
        block = file1.read(blocksize)
        rawHex = ""
        for ch in block:
            # [2:] helps get rid of 0x and zfill(2) makes sure that the hex are represented into two hex numbers
            rawHex += hex(ord(ch))[2:].zfill(2)
    print rawHex
    RawHexSize = len(rawHex)
    cmdField  = rawHex[8:10]
    command = int(cmdField,16)
    print "commandField", command
    if command < 0 or command > 31:
        print "Invalid Command!!!"
        error_flag = 1
        InvalCommand = 1
        print "ERROR FLAG HIGH"


    cMD = int(cmdField,16)
    file2 = open(sys.argv[3],"w+")
    file2.write('<?xml version="1.0" encoding="UTF-8"?>\n')

    #checks if the results are spiking neuron results or muscle results
    #print "Spiking results"
    '''
    if command == SpkngNrnRslts:
        file2.write('<SpikingNeuronResults>\n')
        print "spiking neuron results"
    elif command == MuscleCollatedRes:
        file2.write('<MuscleCollatedResults>\n')
    '''


    count = 0
    #spiking neuron results is represented as one hot representation
    #the total byte size of the packet is 55 hence the number of hex numbers is 110.
    #the idea is to take a long stream of hex numbers and break it into group of 55 bytes
    if command == SpkngNrnRslts:
        for i in range (0,RawHexSize,110):
            NewRawHex = rawHex[i:i+110]
            count = count + 1
            print "NewRawHex,",NewRawHex
            hexToXML(NewRawHex)
    if command == MuscleCollatedRes:
        for i in range (0,RawHexSize,574): # 287*2 = 574, since it is 287 bytes long, the number of hex are 574
            NewRawHex = rawHex[i:i+574]
            count = count +1
            print "newRawHex", NewRawHex
            hexToXML(NewRawHex)
            if error_flag == 1:
                break
    file2.close()
    '''
    for i in range (0,RawHexSize,36):
            NewRawHex = rawHex[i:i+36]
            print "NewRawHex,", NewRawHex
            hexToXML(NewRawHex)
    '''
    '''
    if error_flag == 0:
        if command == SpkngNrnRslts:
            file2.write('</SpikingNeuronResults>\n')
        elif command == MuscleCollatedRes:
            file2.write('</MuscleCollatedResults>\n')

        file2.close()
    '''
    sendingFile = file2.name

    print "Result filename, ", sendingFile
    #send_pe()
    #FTP()




#---------------END OF HEX TO XML CONVERSION

'''
#********************************************************************************************************
#            START OF XML TO HEX CONVERSION
# THIS SECITION MAINLY READS THE XML child SIZE AND BASED ON THE child SIZE, IT EVALUATES THE HEX CONTENTS
# ALL THE COMPUTATION AND CONVERSION IS DONE INSIDE THE xml_layout FUNCTION WHICH TAKES child SIZE AS AN ARGUEMENT
#*********************************************************************************************************
'''

if (sys.argv[1]) == '-H' or (sys.argv[1]) == '-h':
        #(sys.argv[2])
        #import xml.etree.ElementTree as ET

    error_flag = 0
    InvalCommand = 0
    invalidCommandNopld = 0
    Inval_packet_length = 0
    Inval_query = 0
    Inval_dataType = 0
    nval_busSize = 0
    dataType_busMismatch = 0
    inval_inLsb = 0
    inval_outLsb = 0
    imestmpError = 0
    samplingPrdError = 0
    inval_destDev = 0
    inval_srcDev = 0

    errorDetected = 0
    file1 = open(sys.argv[2],"r+") #reading the xml file
    tree = ET.parse(file1)
    root = tree.getroot()
    file4 = open("ErrorLog.xml",'w+')
    file4.write('<?xml version="1.0" encoding="UTF-8"?>\n')
    file4.write('<ErrorLog>\n')

    print "Root", root
    #root.tag
    #creates the file as per the arguement, wb stands for write binary
    file2 = open(sys.argv[3],'w+')
    #this loop looks into the xml file and checks if it a multi packe or a single packet
    #if root child has sub child then it is considered as a multi packet
    for child in root:

        print(child.tag,child.attrib)
        packetLength = len(child)

        print "child length:", packetLength
        #converting xml into hex
        if packetLength > 0:
            #for multipacket xml files like simulation stimulus or neuron and muscle results
            xml_layout(packetLength,child)
        else:
            #for single packet xml files
            packetLength = len(root)
            xml_layout(packetLength,root)
            break

    file2.close()
    #print "Error Flag:", error_flag
    if errorDetected == 1:
        print "There are errors, please check the error log for further details!!"
    else:
        file4.write('\t<Errors>No errors are detected, conversion Successful</Errors>\n')
    file4.write('</ErrorLog>')
    file4.close()
    print "Finish"
    print "Error Detected:", errorDetected

    #This block of code converts the XML file without payload
    #xml_root_size = len(root)
    #converting xml into hex
    #xml_layout(packetLength)
         #print "Error flag outside the function,", error_flag

#---------------end of xml to hex conversion and writing into files
