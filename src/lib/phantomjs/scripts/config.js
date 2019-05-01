"use strict";
function defaults()
{
	this.width = 1280;
	this.height = 1024;
	this.timeout = 60 * 5;
	this.renderWait = 200;
	this.background = '#fff';
	this.ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36';
	this.fileType = 'png';
	this.fileQuality = 100;
	this.output = (function() {
		var fs = require('fs'),
			cwd = fs.absolute(".");
		
		return cwd + '/web_thumb_' + Math.round(Math.random()*100000000) + '.png';
	})();
}

exports.create = function()
{
    return new defaults();
};