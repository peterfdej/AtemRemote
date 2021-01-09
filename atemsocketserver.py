#!/usr/bin/env python
#Atemsocketserver parses PyATEMMax JSON commands from a websocket client to the Atem
#and return the response.
#ws.send('{"command" : "execCutME(0)"}')
#notify_process thread process is polling pgm, prv and mix style of the Atem and publish it

import asyncio
import json
import time
import websockets
import websocket as websocket_c

import argparse
import PyATEMMax #https://github.com/clvLabs/PyATEMMax
try:
    import thread
except ImportError:
	import _thread as thread

parser = argparse.ArgumentParser()
parser.add_argument('ip', help='switcher IP address')
args = parser.parse_args()

USERS = set()
serverhost = '0.0.0.0'	#broadcast on all 
serverclient = 'localhost'
port = 1080

usermessage= ""
switcher = PyATEMMax.ATEMMax()
switcher.connect(args.ip)
switcher.waitForConnection(timeout=10)
while True:
	while switcher.connected:
		print("Connected to Atem")
		try:
			def notify_process():
				def on_message(ws, message):
					time.sleep(0.1)
					print("Client message: ",message)

				def on_error(ws, error):
					print("Client error")
					print(error)

				def on_close(ws):
					print("### Client closed ###")

				def on_open(ws):
					def run(*args):
						print("Client connected")
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
					websocket_c.enableTrace(False)
					ws = websocket_c.WebSocketApp("ws://" + serverclient + ":" + str(port),
						on_message = on_message,
						on_error = on_error,
						on_close = on_close)
					ws.on_open = on_open
					ws.run_forever()
					
			def users_event():
				global usermessage
				return usermessage
			
			async def notify_users():
				if USERS:  # asyncio.wait doesn't accept an empty list
					message = users_event()
					#await asyncio.wait([user.send(message) for user in USERS])
					for user in USERS:
						await asyncio.wait([user.send(message)])
					
			async def register(websocket):
				USERS.add(websocket)
				await websocket.send("welcome, you are connected to the Atem socketserver")

			async def unregister(websocket):
				USERS.remove(websocket)

			async def atem(websocket, path):
				global usermessage
				await register(websocket)
				try:
					async for message in websocket:
						try:
							data = json.loads(message)
							print(str(data))
							if list(data.keys())[0] == 'pgm' or list(data.keys())[0] == 'prv' or list(data.keys())[0] == 'style':
								usermessage = '{"' + list(data.keys())[0] + '": "' + data[list(data.keys())[0]] + '"}'
								await notify_users()
							else:
								command = data["command"]
								atemcommand = ("switcher." + command)
								response = str(eval(atemcommand))
								replymessage = '{"'+ command + '": "' + response + '"}'
								await websocket.send(replymessage)
						except:
							await websocket.send("Socket error: 13 – Permission denied")
				finally:
					await unregister(websocket)
			thread.start_new_thread(notify_process, ())
			start_server = websockets.serve(atem, serverhost, port)

			asyncio.get_event_loop().run_until_complete(start_server)
			asyncio.get_event_loop().run_forever()
			
		except Exception:
			print("Connection error")
			time.sleep(10)
		