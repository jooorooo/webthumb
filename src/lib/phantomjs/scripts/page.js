"use strict";
var args = require('./args').create(),
	defaults = require('./config').create(),
	system = require('system'),
	webPage = require('webpage'),
	startTime = (new Date()).getTime(),
	requestCount = 0,
	forceRenderTimeout,
	dynamicRenderTimeout,
	page = webPage.create(),
	//config data
	width = args.get('width') || defaults.width,
	height = args.get('height') || defaults.height,
	timeout = (args.get('timeout') || defaults.timeout) * 1000,
	renderWait = args.get('renderWait') || defaults.renderWait,
	background = args.get('background') || defaults.background,
	ua = args.get('ua') || defaults.ua,
	output = args.get('output') || defaults.output,
	fileType = args.get('fileType') || defaults.fileType,
	fileQuality = args.get('fileQuality') || defaults.fileQuality,
	url = args.get('url') || defaults.url;
	
if(args.has('help')) {
	console.log("Usage:");
	console.log("  phantomjs page.js [options]");
	console.log(" ");
	console.log("Options:");
	console.log("  --help		Display this help message");
	console.log("  --width		Page width (Default: 1280)");
	console.log("  --height		Page height (Default: 1024)");
	console.log("  --timeout		Script timeout (Default: 300 sec)");
	console.log("  --renderWait		Render wite time (Default: 200 ms)");
	console.log("  --background		Background color fill (Default #fff)");
	console.log("  --ua            	User agent (Default: Chrome/73.0.3683.103)");
	console.log("  --output		File output with path");
	console.log("  --fileType		File type (Default: png)");
	console.log("  --fileQuality		File quality (Default: 100)");
	console.log("  --url			Page url");
	phantom.exit(0);
}
	
window.setTimeout(function() {
	console.log(JSON.stringify({status:"error",message:"Script timeout after " + (timeout/1000) + " seconds", code: 1}));
	phantom.exit(1);
}, timeout);
	
if(!url) 
{
	console.log(JSON.stringify({status:"error",message:"Missing required parameter URL", code: 2}));
	phantom.exit(2);
}
	
page.viewportSize = 
{
	width: width,
	height: height
};

page.settings.userAgent = ua;

// Silence confirmation messages and errors
page.onConfirm = page.onPrompt = function noOp() {};
page.onError = function(err) 
{
	console.log(JSON.stringify({status:"error",message:"Page error: " + err, code: 3}));
	phantom.exit(3);
};

page.onResourceRequested = function(request) 
{
	requestCount += 1;
	clearTimeout(dynamicRenderTimeout);
};

page.onResourceReceived = function(response) 
{
	if (!response.stage || response.stage === 'end') {
		requestCount -= 1;
		if (requestCount === 0) {
			dynamicRenderTimeout = setTimeout(renderAndExit, renderWait);
		}
	}
};

page.open(url, function(status) 
{
	if (status !== 'success') {
		console.log(JSON.stringify({status:"error",message:'Unable to load url: ' + url, code: 4}));
		phantom.exit(4);
	} else {
		forceRenderTimeout = setTimeout(renderAndExit, renderWait);
	}
});

function renderAndExit() 
{
	page.clipRect = {top: 0, left: 0, width: width, height: height};

	var renderOpts = {
		fileQuality: fileQuality
	};

	if(fileType) {
		renderOpts.fileType = fileType;
	}

	page.render(output, renderOpts);
	page.close();
	
	console.log(JSON.stringify({status:"success",code: 0, time: ((new Date()).getTime() - startTime)/1000, output: output}));
	phantom.exit();
}