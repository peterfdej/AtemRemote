<?php

if (!defined('IN_SCRIPT'))
{
	header('location: ../index.php');
	exit;
}
//print_r("host".$hostname);
//print_r("sources: ".$sources);
?>
<script type="text/javascript"></script>
<script>
	window.addEventListener("load", init, false);
	var serverip = "<?php echo $hostname ?>";
	var sources = [<?php echo $sources ?>];
	var socketisOpen = 0;
	var intervalID = 0;
	var pgmscene = "";
	var pvwscene = "";
	var style = "";
	var btn_margin=10;

	var getprograminput = {
		"command":"programInput[0].videoSource"
	};
	var getpreviewinput = {
		"command":"previewInput[0].videoSource"
	};
	var gettransitionstyle = {
		"command":"switcher.transition[0].style"
	};
	var SceneList = [
		{"name":"<?php echo $inputname1 ?>", "input":"input1", "source":"1"},
		{"name":"<?php echo $inputname2 ?>", "input":"input2", "source":"2"},
		{"name":"<?php echo $inputname3 ?>", "input":"input3", "source":"3"},
		{"name":"<?php echo $inputname4 ?>", "input":"input4", "source":"4"},
		{"name":"<?php echo $inputname5 ?>", "input":"input5", "source":"5"},
		{"name":"<?php echo $inputname6 ?>", "input":"input6", "source":"6"},
		{"name":"<?php echo $inputname7 ?>", "input":"input7", "source":"7"},
		{"name":"<?php echo $inputname8 ?>", "input":"input8", "source":"8"}
	];
	var Transitionlist = [
		{"name":"MIX", "style":"mix"},
		{"name":"DIP", "style":"dip"},
		{"name":"WIPE", "style":"wipe"},
		{"name":"DVE", "style":"dVE"},
		{"name":"", "style":"blank"},
		{"name":"", "style":"blank"},
		{"name":"CUT", "style":"cut"},
		{"name":"AUTO", "style":"auto"}
	];

//Hier begint de script
	function init() {
		document.getElementById("pgm_bars").style.display = "block";
		document.getElementById("pvw_bars").style.display = "block";
		document.getElementById("transition").style.display = "block";
		doConnect();
	}

	function sendCommand(p1) {
		if (socketisOpen) {
			websocket.send(p1);
		} else {
			console.log('Fail: Not connected\n');
		}
	}

	function event(data) {
		//document.write(data);
		var jsonOBJ = JSON.parse(data);
		//document.write(JSON.stringify(jsonOBJ));
		if (jsonOBJ["programInput[0].videoSource"]) {
			sendCommand(JSON.stringify(getpreviewinput));
			sendCommand(JSON.stringify(gettransitionstyle));
			generateScenes();
			pgmscene = jsonOBJ["programInput[0].videoSource"];
			redthis(document.getElementById("pgm_"+pgmscene));
			//document.write(" get pgm: " + jsonOBJ["programInput[0].videoSource"]);
		} else if (jsonOBJ["previewInput[0].videoSource"]) {
			pvwscene = jsonOBJ["previewInput[0].videoSource"];
			greenthis(document.getElementById("pvw_"+pvwscene));
			generateTransitions();
			//document.write(" get prv: " + jsonOBJ["previewInput[0].videoSource"]);
		} else if (jsonOBJ["switcher.transition[0].style"]) {
			style = jsonOBJ["switcher.transition[0].style"];
			goldthis(document.getElementById("trans_"+style));
		} else if (jsonOBJ["pgm"]) {
			pgmscene = jsonOBJ["programInput[0].videoSource"];
			pgmscene = jsonOBJ["pgm"];
			redthis(document.getElementById("pgm_"+pgmscene));
		} else if (jsonOBJ["prv"]) {
			pvwscene = jsonOBJ["prv"];
			greenthis(document.getElementById("pvw_"+pvwscene))
		}  else if (jsonOBJ["style"]) {
			style = jsonOBJ["style"];
			goldthis(document.getElementById("trans_"+style));
			//document.write(" style: "+ jsonOBJ["style"]);
		}
		else {
			//document.write(JSON.stringify(jsonOBJ));
			//document.write("else");
		}
	}

	function generateScenes() {
		for (i = 0; i < SceneList.length; i++) {
			var source_quotes='"'+SceneList[i]["source"]+'"';
			document.getElementById("buttons_PGM").innerHTML+="<input class='buttonscene classicbutton buttonscenepgm' style='margin: "+btn_margin+"px "+btn_margin+"px;' id='pgm_"+SceneList[i]["input"]+"' onclick='switch_pgm("+source_quotes+");' type='submit' name='"+SceneList[i]["name"]+"' value='"+SceneList[i]["name"]+"'/>";
			document.getElementById("buttons_PVW").innerHTML+="<input class='buttonscene classicbutton buttonscenepvw' style='margin: "+btn_margin+"px "+btn_margin+"px;' id='pvw_"+SceneList[i]["input"]+"' onclick='switch_pvw("+source_quotes+");' type='submit' name='"+SceneList[i]["name"]+"' value='"+SceneList[i]["name"]+"'/>";
		}
	}

	function generateTransitions() {
		for (i = 0; i < (Transitionlist.length); i++) {
			var transname_quotes='"'+Transitionlist[i]["style"]+'"';
			document.getElementById("buttons_TRANS").innerHTML+="<input class='buttonscene classicbutton buttontrans' style='margin: "+btn_margin+"px "+btn_margin+"px;' id='trans_"+Transitionlist[i]["style"]+"' onclick='cut("+transname_quotes+");' type='submit' name='"+Transitionlist[i]["name"]+"' value='"+Transitionlist[i]["name"]+"'/>";
		}
	}
	
	function changeElement(id, newContent) {
		document.getElementById(id).innerHTML = newContent
	}

	function doConnect() {
		websocket = new WebSocket("ws://" + serverip);
		websocket.onopen = function(evt) {
			onOpen(evt)
		};
		websocket.onclose = function(evt) {
			onClose(evt)
		};
		websocket.onmessage = function(evt) {
			onMessage(evt)
		};
		websocket.onerror = function(evt) {
			onError(evt)
		};
	}

	function onClose(evt) {
		socketisOpen = 0;
		if (!authfail){
			if (!intervalID) {
				intervalID = setInterval(doConnect, 5000);
			}
		}
	}

	function onOpen(evt) {
		socketisOpen = 1;
		clearInterval(intervalID);
		intervalID = 0;
		//document.write('Connected');
		sendCommand(JSON.stringify(getprograminput));
	}

	function onMessage(evt) {
		event(evt.data);
	}

	function onError(evt) {
		socketisOpen = 0;
		if (!intervalID) {
			intervalID = setInterval(doConnect, 5000);
		}
	}

	function doDisconnect() {
		socketisOpen = 0;
		websocket.close();
	}
	
	function redthis(elem)
	{
		var buttonsscenepgm=document.getElementsByClassName("buttonscenepgm");
		for(var i=0;i<buttonsscenepgm.length;i++)
		{
			buttonsscenepgm[i].classList.add("classicbutton")
		}
		elem.classList.remove("classicbutton");elem.classList.add("redbutton")
	}

	function greenthis(elem)
	{
		var buttonsscenepvw=document.getElementsByClassName("buttonscenepvw");
		for(var i=0;i<buttonsscenepvw.length;i++)
		{
			buttonsscenepvw[i].classList.add("classicbutton")
		}
		elem.classList.remove("classicbutton");
		elem.classList.add("greenbutton");
	}
	
	function goldthis(elem)
	{
		var buttonsscenetrans=document.getElementsByClassName("buttontrans");
		for(var i=0;i<buttonsscenetrans.length;i++)
		{
			buttonsscenetrans[i].classList.add("classicbutton")
		}
		elem.classList.remove("classicbutton");
		elem.classList.add("goldbutton");
	}
	
	function switch_pgm(source)
	{
		if(sources[parseInt(source)-1] == "0") {
			alert("Deze schakeling is niet toegestaan");
		} else {
		message = {"command" : "setProgramInputVideoSource(0,"+source+")"};
		sendCommand(JSON.stringify(message));
		}
	}
	
	function switch_pvw(source)
	{
		if(sources[parseInt(source)-1] == "0") {
			alert("Deze schakeling is niet toegestaan");
		} else {
		message = {"command" : "setPreviewInputVideoSource(0,"+source+")"};
		sendCommand(JSON.stringify(message));
		}
	}

	function cut(trans_type)
	{
		if ((sources[parseInt(pgmscene.substr(-1))-1] == "0") || (sources[parseInt(pvwscene.substr(-1))-1] == "0")){
			alert("Deze schakeling is niet toegestaan");
		} else if (trans_type == "cut") {
			message = {"command" : "execCutME(0)"};
			sendCommand(JSON.stringify(message));
		} else if (trans_type == "auto") {
			message = {"command" : "execAutoME(0)"};
			sendCommand(JSON.stringify(message));
		} else {
			message = {"command" : "setTransitionStyle(0,'"+trans_type+"')"};
			sendCommand(JSON.stringify(message));
		}
	}
	
</script>
<style>
html {
	height: 100%;
	width: 100%;
}
body {
	height: 100%;
	width: 100%;
	overflow: hidden;
}
h1{
	color: rgb(105,105,105);
	font-weight: bold;
	margin: 0px 0;
	font-size: 1.3rem;
}
h2{
	font-weight: bold;
}
button {
	margin: 8px;
	font-size: 1.5em;
}
label {
	display: inline-block;
	font-size: 2em;
	margin: 8px;
	width:300px;
	text-align: left;
}
input {
	margin: 8px;
	font-size: 1.5em;
}
input[type="submit"] {
    white-space: normal;
    width: 100px;
    float:left;
}
@media screen and (max-width: 900px) {
		#buttons_TRANS{
			width: 140px;
	}
	.pgm_pvw_bars{
		width: calc(100% - 210px);
	}
}
@media screen and (max-width: 790px) {
		#buttons_TRANS{
			width: auto;
		}
		.pgm_pvw_bars{
			width: auto;
		}
}
.transition{
	float: right;
}
#buttons_PGM, #buttons_PVW, #buttons_TRANS{
	background-color: rgb(34,34,34);
	padding: 1px;
	display: inline-block;
	border: 3px solid rgb(25,25,25);
	border-radius: 10px;
}
#buttons_TRANS{
	width: 240px;
}		
.pgm_pvw_bars{
	width: calc(100% - 350px);
	float: left;
}
.buttonscene{
	display: inline-block;
	width: 100px;
	height: 100px;
	background-image: url(images/blank_button.png);
	border: none;
	text-align: center;
	font-weight: bold;
	outline:none;
	cursor: pointer;
	font-size: 1rem;
}
.greenbutton{
	background-color:  rgb(34,34,34);
	background-image: url(images/blank_green_button.png);
	background-size: 100px 100px;
	box-shadow: 0px 0px 30px rgb(0,255,0);
}
.redbutton{
	background-color:  rgb(34,34,34);
	background-image: url(images/blank_red_button.png);
	box-shadow: 0px 0px 30px rgb(255,0,0);
	background-size: 100px 100px;
}
.goldbutton{
	background-color:  rgb(34,34,34);
	background-image: url(images/blank_yellow_button.png);
	box-shadow: 0px 0px 30px rgb(255,255,0);
	background-size: 100px 100px;
}
.classicbutton{
	background-color:  rgb(34,34,34);
	background-image: url(images/blank_button.png);
	background-size: 100px 100px;
	box-shadow:none;
}
</style>
<meta name="viewport" content="width=device-width, initial-scale=0.8"/>
</head>
<body><div>
	<div class="pgm_pvw_bars" id="pgm_pvw_bars" style="display: inline-block;">
		<div class="pgm_bars" id="pgm_bars">
			<h1 id="pgm_title">Program</h1>
			<div id="buttons_PGM"></div>
		</div>
		<div class="pvw_bars" id="pvw_bars">
			<h1 id="pvw_title">Preview</h1>
			<div id="buttons_PVW"></div>
		</div>
	</div>
	<div class="transition" id="transition">
		<h1>Transition <class="transition_input"/></h1>
		<div id="buttons_TRANS"></div>
	</div>
</div></body>
<?php
//print_r($sources);
?>



