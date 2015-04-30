/* 
 * Copyright (C) 2015 Maxim Lebedev
 *
 * This file is part of "myWebRTC"
 *
 * "myWebRTC" is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * "myWebRTC" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses/>
 */
 
<?php
setcookie('email', $_POST['email'], 0);
?>
<!DOCTYPE html>
<!--
Service Data for myWebRTC-System.
Project at the University of Applied Sciences Wildau (Germany)
Web: http://th-wildau.de/
Author: Maxim Lebedev
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Registration</title>
    </head>
    <body>
        <?php
        class DatabankService {

        public function GetValues() {
        global $name;
        global $surname;
        $inputValues = $_POST;
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        if (!empty($inputValues)) {
        $this->AddUser($inputValues);
        } else {
        exit('Enter your data for the registration!');
        }
        }

        public function AddUser($inputArray) {
        if (!empty($inputArray)) {
        $sqlRequest = 'INSERT INTO `webcall-db`.users SET ';
        foreach ($inputArray as $key => $value) {
        if (!empty($value)) {
        $sqlRequest .= '`' . $key . '` = "' . $value . '", ';
        }
        }
        $sqlRequest .= '`url` = "http://194.95.44.28:81/profile/' . $inputArray['name'] . $inputArray['surname'] . '.html' . '"';
        $this->Connector($sqlRequest);
        } else {
        exit('Enter your data for the registration!');
        }
        }

        public function Connector($sqlRequest) {
        if (!empty($sqlRequest)) {
        $log_info = parse_ini_file('../info/conf.ini', TRUE);
        $connection = mysqli_connect($log_info['connection']['host'], $log_info['connection']['user'], $log_info['connection']['password'], $log_info['connection']['db_name'], $log_info['connection']['port']);
        if (!$connection) {
        exit('No connection to database. Error - ' . mysqli_connect_errno() . ' : %s\n' . mysqli_connect_error());
        } else {
        if ($stmt = mysqli_prepare($connection, $sqlRequest)) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
        $res = TRUE;
        } else {
        $res = FALSE;
        }
        $this->ChangeStatus($res);
        }
        } else {
        exit('Please, enter your data for the registration!');
        }
        }

        public function ChangeStatus($res) {
        if ($res != TRUE) {
        exit('Database error!');
        } else {
        $sqlReq = 'INSERT INTO `webcall-db`.status SET `online` = 1, `offline` = 0';
        $log_info = parse_ini_file('../info/conf.ini', TRUE);
        $connection = mysqli_connect($log_info['connection']['host'], $log_info['connection']['user'], $log_info['connection']['password'], $log_info['connection']['db_name'], $log_info['connection']['port']);
        if (!$connection) {
        exit('No connection to database. Error - ' . mysqli_connect_errno() . ' : %s\n' . mysqli_connect_error());
        } else {
        if ($stmt = mysqli_prepare($connection, $sqlReq)) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
        $result = TRUE;
        } else {
        $result = FALSE;
        }
        $this->CreatePage($result);
        }
        }
        }

        public function CreatePage($res) {
        if ($res != TRUE) {
        exit('Database error!');
        } else {
        $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>

<title>' . $GLOBALS['name'] . ' ' . $GLOBALS['surname'] . '</title>

<style>
	video {
		width:  320px;
		height:  240px;
		border:  1px solid black;
	}

	hidden {
		display: none;
	}
			
	div {
		  display:  inline-block;
	}
			
	body {
		background-color:#FFF;
		font-family: calibri;
	}
		
	#table {
	    /*margin-left: auto; 
		margin: 0px; */
		width: 60%;
		border-collapse: collapse;
		padding: 0px;
		background-color:#f1f1f1;
	}
		
	.td {
		 border: 1px solid #98bf21;
		 /*background-color:#FFF; */
	}
	
	.tr {
		 border: 1px solid #98bf21;
	}
/*
.border {
	margin-left: auto;
    margin-right: auto;
    width: 90%;
	padding: 10px;
    border: 2px solid #98bf21;
}
*/
	span.color_h3 {
		color: #98bf21;
	}
		
/* for Navigation Buttons*/
	/*.right{
		position:relative;
		align: right;
	  /*right: 13px;
		width: 340px;
		top: 97px;	
		top: 79px; */
	}
		
	ul#menu {
		padding: 0px;
	}
		
	ul#menu li {
		display: inline;
	}
		
	ul#menu li a {
		background-color: #98bf21;
		color: #FFFFFF;
		padding: 10px 20px;
		text-decoration: none;
		border-radius: 4px 4px 0 0;
		font-weight: bold;
		text-transform: uppercase;
	}
		
	ul#menu li a:hover {
		background-color: #7A991A;
	}
		
 /* Animated box */
	#animated_div {
		width:108px;
		height:30px;
		background:#92B901;
		color:#ffffff;
		position:relative;
		/* left:30px;
		margin-right: auto; */
		font-weight:bold;
		font-size:20px;
		padding:10px;
		animation:animated_div 7s infinite;
		-moz-animation:animated_div 7s infinite;
		-webkit-animation:animated_div 7s infinite;
		-o-animation:animated_div 7s infinite;
		border-radius:5px;
		-webkit-border-radius:5px;
		animation-iteration-count: infinite;
	}
		
	@keyframes animated_div{
		0%		{transform: rotate(0deg);left:1%;}
		25%		{transform: rotate(20deg);left:1%;}
		50%		{transform: rotate(0deg);left:1%;}
		55%		{transform: rotate(0deg);left:82%;}
		70%		{transform: rotate(0deg);left:82%;background:#1ec7e6;}
		100%	{transform: rotate(-360deg);left:82%;}
	}
		
	@-webkit-keyframes animated_div {
		0%		{-webkit-transform: rotate(0deg);left:1%;}
		25%		{-webkit-transform: rotate(20deg);left:1%;}
		50%		{-webkit-transform: rotate(0deg);left:82%;}
		55%		{-webkit-transform: rotate(0deg);left:82%;}
		70%		{-webkit-transform: rotate(0deg);left:82%;background:#1ec7e6;}
		100%	{-webkit-transform: rotate(-360deg);left:1%;}
	}
		
	@-moz-keyframes animated_div {
		0%   {-moz-transform: rotate(0deg);left:1%;}
		25%  {-moz-transform: rotate(20deg);left:1%;}
		50%  {-moz-transform: rotate(0deg);left:82%;}
		55%  {-moz-transform: rotate(0deg);left:82%;}
		70%  {-moz-transform: rotate(0deg);left:82%;background:#1ec7e6;}
		100% {-moz-transform: rotate(-360deg);left:1%;}
	}
		
	@-o-keyframes animated_div {
		0%   {transform: rotate(0deg);left:1%;}
		25%  {transform: rotate(20deg);left:1%;}
		50%  {transform: rotate(0deg);left:82%;}
		55%  {transform: rotate(0deg);left:82%;}
		70%  {transform: rotate(0deg);left:82%;background:#1ec7e6;}
		100% {transform: rotate(-360deg);left:1%;}
	}
</style>

</head>

<body>

    <a href="http://www.th-wildau.de"><img src="http://www.th-wildau.de/fileadmin/dokumente/aktuelles/Service_Arbeitsmaterialien/bilder/TH-Wildau-Logo_200px-breit.png" width="100" height="40" align="right" alt="TH Wildau Logo"/></a>
	<a href="http://webrtc.th-wildau.de"><img src="http://webrtc.th-wildau.de/log/icons/logo.png" width="74" height="89" align="left" alt="MyWebRTC"/></a>
</br>
    <p align="center"><b>Project of the Master course "Advanced Communication Solutions"</b></p>

<p>
	
<script>var queryparams = {};</script>

<script src="../extra/adapter.js" type="text/javascript"></script>

<script src="../clientXHRSignalingChannel.js"
        type="text/javascript"></script>

<script>
var signalingChannel, key, id,
    haveLocalMedia = false,
    weWaited = false,
    myVideoStream, myVideo, 
    yourVideoStream, yourVideo,
    doNothing = function() {},
    pc, dc, data = {},
    constraints = {mandatory: {
                    OfferToReceiveAudio: true,
                    OfferToReceiveVideo: true}};
window.onbeforeunload = function go_out() {
   			$.ajax({
        			async: false,
				url: "http://194.95.44.28/service/db_logout.php",
				cache: false,
			});
		return \'You are leaving your profile!\';
};

window.onload = function () {

  if (queryparams && queryparams[\'key\']) {
    document.getElementById("key").value = queryparams[\'key\'];
    connect();
  }

  myVideo = document.getElementById("myVideo");
  yourVideo = document.getElementById("yourVideo");
  
  getMedia();

};


function connect() {
  var errorCB, scHandlers, handleMsg;

  key = document.getElementById("key").value;

  handleMsg = function (msg) {
    var msgE = document.getElementById("inmessages");
    msgE.innerHTML = JSON.stringify(msg) + "<br/>" +
                     msgE.innerHTML;

    if (msg.type === "offer") {
      pc.setRemoteDescription(new RTCSessionDescription(msg));
      answer();
    } else if (msg.type === "answer") {
      pc.setRemoteDescription(new RTCSessionDescription(msg));
    } else if (msg.type === "candidate") {
      pc.addIceCandidate(
        new RTCIceCandidate({sdpMLineIndex:msg.mlineindex,
                             candidate:msg.candidate}));
    }
  };

  scHandlers = {
    \'onWaiting\' : function () {
      setStatus("Waiting");
      weWaited = true;
    },
    \'onConnected\': function () {
      setStatus("Connected");
      createPC();
    },
    \'onMessage\': handleMsg
  };

  signalingChannel = createSignalingChannel(key, scHandlers);
  errorCB = function (msg) {
    document.getElementById("response").innerHTML = msg;
  };

  signalingChannel.connect(errorCB);
}


function send(msg) {
  var handler = function (res) {
    document.getElementById("response").innerHTML = res;
    return;
  },

  msg = msg || document.getElementById("message").value;

  msgE = document.getElementById("outmessages");
  msgE.innerHTML = JSON.stringify(msg) + "<br/>" +
                   msgE.innerHTML;

  signalingChannel.send(msg, handler);
}


function getMedia() {
  getUserMedia({"audio":true, "video":true},
               gotUserMedia, didntGetUserMedia);
}

function gotUserMedia(stream) {
  myVideoStream = stream;
  haveLocalMedia = true;

  attachMediaStream(myVideo, myVideoStream);
  attachMediaIfReady();
}

function didntGetUserMedia() {
  console.log("couldn\'t get video");
}


function createPC() {
  var stunuri = true,
turnuri = false,
myfalse = function(v) {
return ((v==="0")||(v==="false")||(!v)); },
config = new Array();
if (queryparams) {
if (\'stunuri\' in queryparams) {
stunuri = !myfalse(queryparams[\'stunuri\']);
}
if (\'turnuri\' in queryparams) {
turnuri = !myfalse(queryparams[\'turnuri\']);
};
};
if (stunuri) {
config.push({"url":"stun:stun.l.google.com:19302"});
}
if (turnuri) {
if (stunuri) {
config.push({"url":"turn:user@turn.webrtcbook.com",
"credential":"test"});
} else {
config.push({"url":"turn:user@turn-only.webrtcbook.com",
"credential":"test"});
}
}
console.log("config = " + JSON.stringify(config));
pc = new RTCPeerConnection({iceServers:config});
  pc.onicecandidate = onIceCandidate;
  pc.onaddstream = onRemoteStreamAdded;
  pc.onremovestream = onRemoteStreamRemoved;
  pc.ondatachannel = onDataChannelAdded;

  attachMediaIfReady();
}


function onIceCandidate(e) {
  if (e.candidate) {
    send({type:  \'candidate\',
          mlineindex:  e.candidate.sdpMLineIndex,
          candidate:  e.candidate.candidate});
  }
}


function onRemoteStreamAdded(e) {
  yourVideoStream = e.stream;
  attachMediaStream(yourVideo, yourVideoStream);
  setStatus("On call");
}

function onRemoteStreamRemoved(e) {}


function onDataChannelAdded(e) {
dc = e.channel;
setupDataHandlers();
sendChat("You are connected! Congratulations!");
}


function setupDataHandlers() {
data.send = function(msg) {
msg = JSON.stringify(msg);
console.log("sending " + msg + " over data channel");
dc.send(msg);
}
dc.onmessage = function(e) {
var msg = JSON.parse(e.data),
cb = document.getElementById("chatbox"),
rtt = document.getElementById("rtt");
if (msg.rtt) {
console.log("received rtt of \'" + msg.rtt + "\'");
rtt.value = msg.rtt;
msg = msg.rtt;
} else if (msg.chat) {
console.log("received chat of \'" + msg.chat + "\'");
cb.value += "<- " + msg.chat + "\n";
rtt.value = "";
cb.scrollTop = cb.scrollHeight;
msg = msg.chat;
} else {
console.log("received " + msg + "on data channel");
}
};
}


function sendRtt() {
var msg = document.getElementById("chat").value;
data.send({\'rtt\':msg});
}


function sendChat(msg) {
var cb = document.getElementById("chatbox"),
c = document.getElementById("chat");


msg = msg || c.value;
console.log("sendChat(" + msg + ")");
cb.value += "-> " + msg + "\n";
data.send({\'chat\':msg});
c.value = \'\';
cb.scrollTop = cb.scrollHeight;
}


function attachMediaIfReady() {
  if (pc && haveLocalMedia) {attachMedia();}
}


function attachMedia() {
  pc.addStream(myVideoStream);
  setStatus("Ready for call");

  if (queryparams && queryparams[\'call\'] && !weWaited) {
    call();
  }

}


function call() {
	dc = pc.createDataChannel(\'chat\');
	setupDataHandlers();
  pc.createOffer(gotDescription, doNothing, constraints);
}


function answer() {
  pc.createAnswer(gotDescription, doNothing, constraints);
}


function gotDescription(localDesc) {
  pc.setLocalDescription(localDesc);
  send(localDesc);
}


function setStatus(str) {
  var statuslineE = document.getElementById("statusline"),
      statusE = document.getElementById("status"),
      sendE = document.getElementById("send"),
      connectE = document.getElementById("connect"),
      callE = document.getElementById("call"),
      scMessageE = document.getElementById("scMessage");

  switch (str) {
    case \'Waiting\':
      statuslineE.style.display = "inline";
      statusE.innerHTML =
        "Waiting for peer signaling connection";
      sendE.style.display = "none";
      connectE.style.display = "none";
      break;
    case \'Connected\':
      statuslineE.style.display = "inline";
      statusE.innerHTML =
        "Peer signaling connected, waiting for local media";
      sendE.style.display = "inline";
      connectE.style.display = "none";
      scMessageE.style.display = "inline-block";
      break;
    case \'Ready for call\':
      statusE.innerHTML = "Ready for call";
      callE.style.display = "inline";
      break;
    case \'On call\':
      statusE.innerHTML = "On call";
      callE.style.display = "none";
      break;
    default:
  }
}

</script>
	
    		</br>
        
<table id="table" align="center">
	<tr bgcolor="#FFFFFF">
    <td></td>	
	<td  colspan="2" align="right">
      <ul id="menu">
			<li><a href="http://webrtc.th-wildau.de/service/db_logout.php">Logout</a></li>
        	<li><a href="http://webrtc.th-wildau.de/log/file-transfer.html">File Transfer</a></li>
        	<li><a href = "http://webrtc.th-wildau.de/log/faq.html">FAQ</a></li>
			<li><a href = "http://webrtc.th-wildau.de/log/imprint.html">Imprint</a></li>
		</ul>
    </td> 
    </tr>
	<tr class="tr">
    	<td colspan="2">
        <div id="setup">
   		<p>Key:
			<input type="text" name="key" id="key" onkeyup="if (event.keyCode == 13) {connect(); return false;}"/>
    		<button id="connect" onClick="connect()">Connect</button>
 			<span id="statusline" style="display:none">Status:
      		<span id="status">Disconnected</span>
    		</span>
    		<button id="call" style="display:none" onclick = "call()">Call</button>
  		</p>
		</div>

		<div id="scMessage" style="float:right;display:none">
 		<p style="display:none">Signaling channel message:
   			<input style="display:none" type="text" width="100%" name="message" id="message" onkeyup="if (event.keyCode == 13) {send(); return false;}"/>
   			<button style="display:none" id="send" style="display:none" onclick="send()">Send </button>
  		</p>

  		<p style="display:none">Response:  
           	<span style="display:none" id="response"></span>	
        </p>
       
		</div>
        </td>
        
        <td class="td" rowspan="3" width="100%" valign="top" bgcolor="#FFFFFF">
		<span class="color_h3">
       	<h3 align="center">Contacts</h3>
	   	</span>
       	<iframe src="http://194.95.44.28/log/ajax.php" width="300" height="430" frameborder="0" align="top" scrolling="auto">
		<p>Your browser does not support iframes.</p>
		</iframe>
		</td>
    </tr>

    <tr class="tr">
    	<td style="vertical-align:top;width:50%">
  		<div>
    		<video id="myVideo" autoplay="autoplay" controls poster="http://webrtc.th-wildau.de/log/icons/logo.png"/>
  		</div>
  		
        <p style="display:none"><b>Outgoing Messages</b>
    	
        <br/>
        	<textarea id="outmessages" rows="100" style="width:100%"></textarea>
		</p>
        </td>
        
		<td style="width:50%;vertical-align:top">
		<div>
			<video id="yourVideo" autoplay="autoplay" controls poster="http://webrtc.th-wildau.de/log/icons/logo.png" />
        </div>
	
    	<p style="display:none"><b>Incoming Messages</b>

		<br/>
		
        	<textarea id="inmessages" rows="100" style="width:100%"></textarea>
		</p>
		</div>
		</td>
	</tr>
    
    <tr class="tr" style="width:50%;vertical-align:central">
    	<td colspan="2">   
        </br>
        <b>Chat:</b>
			<textarea id="chatbox" rows="10" style="width:99%" readonly></textarea>
		<p style="width:100%;display:none">				
       	<b>Real-time:</b>
			<textarea id="rtt" rows="2" style="width:100%"></textarea>
		</p>
            
		<p style="width:100%"><b> Type your message:</b>
			<input type="text" style="width:99%" rows="2" name="chat" id="chat" onkeyup="sendRtt(); if (event.keyCode == 13) {
					sendChat(); return false;}"/>
		</p>
		</div>
        </td>
    </tr>
	<tr rowspan="2">
    	<td colspan="3" bgcolor="white">
        <br/>
		<br/>
        <br/>
		<div id="animated_div">myWebRTC</div>
        <br/>
        <br/>
        <br/>
		<br/>
        </td>
   </tr>
</table>   
</body>
</html>';
                    $filename = 'C:\nodejs\basic_data\static\profile\\' . $GLOBALS['name'] . $GLOBALS['surname'] . '.html';
                    if (file_exists($filename)) {
                        exit('This profil was already existed!');
                    } else {
                        if (file_put_contents($filename, $content)) {
                            header("Location:http://194.95.44.28:81/profile/" . $GLOBALS['name'] . $GLOBALS['surname'] . ".html");
                        } else {
                            exit('No Web-Page was existed!');
                        }
                    }
                }
            }

        }

        $object = new DatabankService();
        $object->GetValues();
        ?>
    </body>
</html>