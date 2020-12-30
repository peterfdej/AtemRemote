#!/usr/bin/env python

# WS client example

import websocket
import argparse
try:
    import thread
except ImportError:
	import _thread as thread
import time
import PyATEMMax #https://github.com/clvLabs/PyATEMMax

parser = argparse.ArgumentParser()
parser.add_argument('ip', help='switcher IP address')
args = parser.parse_args()

serverhost = 'localhost'
port = 1080

switcher = PyATEMMax.ATEMMax()
switcher.connect(args.ip)
switcher.waitForConnection(timeout=10)
while True:
	while switcher.connected:
		print("connected to Atem")
		try:
			def on_message(ws, message):
				#print("on_message")
				print(message)

			def on_error(ws, error):
				print("error")
				print(error)

			def on_close(ws):
				print("### closed ###")

			def on_open(ws):
				def run(*args):
					print("connected")
					programinput = ""
					previewinput = ""
					programinputprev = ""
					previewinputprev = ""
					style = ""
					styleprev = ""
					while True:
						programinput = str(switcher.programInput[0].videoSource)
						previewinput = str(switcher.previewInput[0].videoSource)
						style = str(switcher.transition[0].style)
						if programinput != programinputprev:
							programinputprev = programinput
							message = '{"pgm" : "'+ programinput + '"}'
							ws.send(message)
						if previewinput != previewinputprev:
							previewinputprev = previewinput
							message = '{"prv" : "'+ previewinput + '"}'
							ws.send(message)
						if style != styleprev:
							styleprev = style
							message = '{"style" : "'+ style + '"}'
							ws.send(message)
						time.sleep(0.2)
				thread.start_new_thread(run, ())


			if __name__ == "__main__":
				websocket.enableTrace(False)
				ws = websocket.WebSocketApp("ws://" + serverhost + ":" + str(port),
					on_message = on_message,
					on_error = on_error,
					on_close = on_close)
				ws.on_open = on_open
				ws.run_forever()
		except Exception:
			print("Connection error")
			time.sleep(10)
