"use strict";
function argumentsParse()
{
	var system = require('system')
		self = this;
	this.parameters = {};

	system.args.forEach(function (arg, i) {
		if(i > 0 && arg.substr(0, 2) === '--') {
			var argValue = arg.split('='),
				key = argValue.shift();
				
			self.parameters[key.substr(2)] = argValue.join('=');
		}
	});
}

argumentsParse.prototype.get = function(key)
{
	return this.parameters[key];
};

argumentsParse.prototype.has = function(key)
{
	return key in this.parameters;
};

argumentsParse.prototype.set = function(key, value)
{
	this.parameters[key] = value;
	return this;
};

argumentsParse.prototype.all = function()
{
	return this.parameters;
};

exports.create = function()
{
    return new argumentsParse();
};