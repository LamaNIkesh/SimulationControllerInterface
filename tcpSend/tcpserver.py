import time
import manageCommands as mc
import communicationsService as cs

class tcpserver():
    def __init__(self, port, tp_index, device):
        self.device=int(device)
        self.deviceName=mc.getIpName(device)[1]
        self.port = port
        self.host = mc.getIpName(device)[0]
        self.topic = cs.topic[tp_index]
        log = "[" + self.deviceName + " cs - " + time.strftime(
            "%d/%m/%Y %H:%M:%S") + "] Started the server serving: " + self.host+"/"+ self.topic + ":" + str(self.port)
        print(log)

    def getHost(self):
        return self.host

    def getPort(self):
        return self.port

    def getTopic(self):
        return self.topic

    def getDeviceName(self):
        return self.deviceName

    def getDevice(self):
        return self.device

    def __del__(self):
        log= "["+ self.deviceName+" cs - " + time.strftime("%d/%m/%Y %H:%M:%S") + "] Deleting the server serving the endpoint : "+self.host+"/"+self.topic+":"+str(self.port)
        print(log)
