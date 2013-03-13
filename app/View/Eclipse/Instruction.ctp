<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title>Version-Checker Webservice</title>
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
                width="163" height="67" border="0" alt="Eclipse Logo"
                class="logo" />
            </a>
        </div>
        <div id="midcolumn">
        <h2>
            Version-Checker Webserive
        </h2>
        <h3>
            How the webservice works:
        </h3>
        <font color="rgb(72,61,139)">
            <p>The webservice is built to communicate with the Eclipse Version-Checker plugin. It takes an input POST request of the form similar to below:</p>
        </font>
        <pre><code>
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

        </code></pre>
        
        <h3>
            <font color="rgb(72,61,139)">
            Python script for testing:
            </font>
        </h3>

        
        <p>If you wish to test it you can install a third party library <a href="http://docs.python-requests.org/en/latest/">Requests</a> and run a simple Python script to request POST and recive JSON response from the webservice:</p>

      
         <pre><code>

import requests
import json

data = [{"component":"part1", "version":"1"},{"component":"part2", "version":"1"}]

r = requests.post("<SCRIPT LANGUAGE="JavaScript">document.write(location.href)</SCRIPT>", data=json.dumps(data))
print r.json()
        </code></pre>
        </div>
    </body>
</html>

