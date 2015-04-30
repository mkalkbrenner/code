// The myWebRTC application was developed during the summer semester 2014 
// and winter semester 2014-2015 (March 2014-March 2015) as a part of the
// Master course Business Computing in the Technical University of Applied Science TH Wildau.

// You may use this code for your own education. If you use it largely intact,
// or develop something from it, don't claim that your code came first. 
// You are using this code completely at your own risk.  

//  CREDITS: Johnston & Burnett WebRTCbook.com

var server = require("./server");
var requestHandlers = require("./serverXHRSignalingChannel");
var log = require("./log").log;
var port = process.argv[2] || 5001;


// returns 404
function fourohfour(info) {
var res = info.res;
log("Request handler fourohfour was called.");
res.writeHead(404, {"Content-Type": "text/plain"});
res.write("404 Page Not Found");
res.end();
}

var handle = {};
handle["/"] = fourohfour;
handle["/connect"] = requestHandlers.connect;
handle["/send"] = requestHandlers.send;
handle["/get"] = requestHandlers.get;

// path added
server.serveFilePath("./static");
server.start(handle, port);
log("index.js line 31");