#!/usr/bin/env python

from PiStorms import PiStorms
import sys, subprocess, json
import socket

version_json_file = '/var/tmp/ps_versions.json'

psm = PiStorms()
    
def available():
    try:
        socket.setdefaulttimeout(5)
        socket.socket(socket.AF_INET, socket.SOCK_STREAM).connect(("8.8.8.8", 53))
        return True
    except Exception as e: pass
    return False

opt = str(sys.argv[1])

isConnected = available()
if (isConnected == False):
    m = ["Software Updater", "You are not connected to Internet.",
      "Internet connection required"]
    psm.screen.askQuestion(m,["OK"])
    sys.exit(-1)

print "running software_update.py"

try:
    f = open(version_json_file, 'r')
    data = json.loads(f.read())
    sw_version = data['sw_ver']
    f.close()
except:
    # no local json
    # this can happen on old systems, so upgrade them to 4.000
    sw_version = "4.000"

#
# Download the update from mindsensors server.
#
psm.screen.termPrintAt(3, "Downloading the update")
psm.screen.termPrintAt(4, "Please wait...")

sw_file_name = "PiStorms." + sw_version + ".tar.gz"
cmd = "wget http://www.mindsensors.com/largefiles/updater/" + sw_file_name
status = subprocess.call(cmd, shell=True)

if ( status != 0 ):
    m = ["Software Updater", "Error while downloading update:",
      sw_file_name]
    psm.screen.askQuestion(m,["OK"])
    psm.screen.clearScreen()
    sys.exit(-1)
else:
    psm.screen.termPrintAt(3, "Download complete")
    psm.screen.termPrintAt(4, "              ")

#
# rename the prior folder with old version prefix
# Extract the new update in its place
#
cmd = "sudo -u pi mv /home/pi/PiStorms /home/pi/old_PiStorms"
status = subprocess.call(cmd, shell=True)
if ( status != 0 ):
    m = ["Software Updater", "Error while renaming PiStorms folder" ]
    psm.screen.askQuestion(m,["OK"])
    psm.screen.clearScreen()
    sys.exit(-1)
else:
    psm.screen.termPrintAt(3, "Unzipping ...")
    psm.screen.termPrintAt(4, "              ")

cmd = "cd /home/pi; tar -zxvf /var/tmp/upd/" + sw_file_name
status = subprocess.call(cmd, shell=True)
if ( status != 0 ):
    m = ["Software Updater", "Error while unzipping PiStorms folder" ]
    psm.screen.askQuestion(m,["OK"])
    psm.screen.clearScreen()
    sys.exit(-1)
else:
    psm.screen.termPrintAt(3, "Unzip complete.")
    psm.screen.termPrintAt(4, "Configuring...")

#
# run setup script.
#
cmd = "cd /home/pi/PiStorms/setup;chmod +x setup.sh"
status = subprocess.call(cmd, shell=True)

psm.screen.termPrintAt(3, "Configuration in process...")
psm.screen.termPrintAt(4, "Please wait...")
cmd = "cd /home/pi/PiStorms/setup;./setup.sh"
status = subprocess.call(cmd, shell=True)


psm.screen.termPrintAt(3, "Update complete.")
psm.screen.termPrintAt(4, "Please restart your Pi.")



