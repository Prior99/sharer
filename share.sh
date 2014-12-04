#!/usr/local/bin/node
var FS = require('fs');
var Request = require('request');

var baseURL = "https://cronosx.de/share/";

if(process.argv.length !== 3) {
	console.log("Usage: " + process.argv[1] + " filename");
	return;
}

var req = Request.post(baseURL + "upload.php", function(err, response, body) {
	if(err) {
		console.log("Unable to upload file!");
		console.log(err);
	}
	else {
		var answer = JSON.parse(body);
		console.log("File uploaded to " + baseURL + "download.php?id=" + answer[0].id);
	}
});

var form = req.form();
form.append('upload[]', FS.createReadStream(process.argv[2]));
