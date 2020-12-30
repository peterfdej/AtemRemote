#!/usr/bin/env python
#Atemsocketserver parses PyATEMMax JSON commands from a websocket client to the Atem
#and return the response.
#ws.send('{"command" : "execCutME(0)"}')

import asyncio
import json
import time
import websockets
import argparse
import PyATEMMax #https://github.com/clvLabs/PyATEMMax

parser = argparse.ArgumentParser()
parser.add_argument('ip', help='switcher IP address')
args = parser.parse_args()

USERS = set()
serverhost = '0.0.0.0'
port = 1080

usermessage= ""
switcher = PyATEMMax.ATEMMax()
switcher.connect(args.ip)
switcher.waitForConnection(timeout=10)
while True:
	while switcher.connected:
		print("connected to Atem")
		try:
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

			start_server = websockets.serve(atem, serverhost, port)

			asyncio.get_event_loop().run_until_complete(start_server)
			#asyncio.get_event_loop().run_until_complete(asyncio.wait([   
			#	start_server,
			#	notify_process()
			#]))
			asyncio.get_event_loop().run_forever()
			
		except Exception:
			print("Connection error")
			time.sleep(10)
