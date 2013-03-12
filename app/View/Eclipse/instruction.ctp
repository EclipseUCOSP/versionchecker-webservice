<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Version-Checker Webservice</title>
<<<<<<< HEAD
<h1>
	<font size="16">How the webservice works:</font>
</h1>

<body>
The webservice takes an input POST request with the form as below:

<code>
	<font color="rgb(72,61,139)">
=======
<meta name="author" content="Eclipse Foundation, Inc." />
<link rel="stylesheet" type="text/css"
    href="/Styles/layout.css"
    media="screen" />
<link rel="stylesheet" type="text/css"
    href="/Styles/visual.css"
    media="screen" />
</head>
<body>
	<div id="header">
        <a href="http://www.eclipse.org/"><img
            src="/Styles/header_logo.gif"
            width="163" height="68" border="0" alt="Eclipse Logo"
            class="logo" />
        </a>
    </div>
<div id="midcolumn">
<h3>
How the webservice works:
</h3>

<p>The webservice is built to communicate with the Eclipse Version-Checker plugin. It takes an input POST request of the form similar to below:</p>

<pre><code>
>>>>>>> 91240491c6445335e6d28a3671c92fa94804992f
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
<<<<<<< HEAD
	</font>
</code>
=======
</code></pre>


<h3>Python script for testing:</h3>
>>>>>>> 91240491c6445335e6d28a3671c92fa94804992f

<p>If you wish to test it you can install a third party library <a href="http://docs.python-requests.org/en/latest/">Requests</a> and run a simple Python script to request POST and recive JSON response from the webservice:</p>

<<<<<<< HEAD
Python scrript for testing:
<code>
	<font color="rgb(72,61,139)">
=======
<pre><code>
>>>>>>> 91240491c6445335e6d28a3671c92fa94804992f
import requests
import json

data = [{"component":"part1", "version":"1"},{"component":"part2", "version":"1"}]


r = requests.post("http://build.eclipse.org/cbi/vc/", data=json.dumps(data))
print r.json()
<<<<<<< HEAD
	</font>
</code>
</body>
=======
</code></pre>
</div>
</body>
</html>
>>>>>>> 91240491c6445335e6d28a3671c92fa94804992f
