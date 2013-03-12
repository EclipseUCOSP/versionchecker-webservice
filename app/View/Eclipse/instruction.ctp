<?php
// app/View/Eclipse/instruction.ctp
$this->extend('/Common/view');
?>

<title>Version-Checker Webservice</title>
<h1>
	<font size="16">How the webservice works:</font>
</h1>

<body>
The webservice takes an input POST request with the form as below:

<code>
	<font color="rgb(72,61,139)">
{
	{
	"component":"org.eclipse.jdt.junit",
	"version":"3.7.100.v20120523-1543"
	},
	{
	"component":"org.junit",
	"version":"3.8.2.v3_8_2_v20120427-1100"
	}
}
	</font>
</code>

You can install a third party library <a href="http://docs.python-requests.org/en/latest/">Requests</a> and run a simple Python script to request POST and recive JSON response from the webservice:

Python scrript for testing:
<code>
	<font color="rgb(72,61,139)">
import requests
import json

jdata = [{"component":"part1", "version":"1"},{"component":"part2", "version":"1"}]


r = requests.post("http://build.eclipse.org/cbi/vc/", data=json.dumps(jdata))
print r.json()
	</font>
</code>
</body>