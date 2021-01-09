Atemremote is a web application where users can switch program and preview of a BlackMagic Atem switcher.
There are 3 parts
- mySQL database atemdb
- python scripts atemsocketserver.py
	include the PyATEMMax lib from clvLabs  https://github.com/clvLabs/PyATEMMax
- web application.

The python script atemsocketserver.py translate the Atem commands PyATEMMax is using to JSON messages and replies a JSON.
It also publish every change in pgm, prv and mix types of the Atem.

The BlackMagic Atem switcher is limited to max 5 connections.
Using the websocket server you can make numerous connections and the JSON messages are easier to handle in web applications.

Default user is admin@atem.org pw adminadmin

A user can be limited in using the prm and prv buttons. 
This has to be done in the sources field.
1,1,1,1,1,1,1,1 : all 8 buttons can be used.
0,0,1,1,0,0,0,0 : only button 3 and 4 can be used.



