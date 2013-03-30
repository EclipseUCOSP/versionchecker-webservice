<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title>Version-Checker Webservice</title>
        <meta name="author" content="Eclipse Foundation, Inc." />
       <link rel="stylesheet" type="text/css"
             href="css/layout.css"
             media="screen" />
        <link rel="stylesheet" type="text/css"
             href="css/visual.css"
             media="screen" />
    </head>
    <body>
       <div id="header">
            <a href="http://www.eclipse.org/"><img
                src="img/header_logo.gif"
                width="163" height="67" border="0" alt="Eclipse Logo"
                class="logo" />
            </a>
        </div>
        <div id="midcolumn">
        <h2>
            Version-Checker Webservice
        </h2>
        <p>
            This webservice is created to fill the gap between version-cheker plugin and version-checker database. It provides JSON data to be consumed by front-end UI. It is not a human readable webpage. In order to test it you can follow the instructions.
        </p>
        <h3>
            How the webservice works:
        </h3>
        
            <p>It is built to communicate with the Eclipse Version-Checker plugin. It takes an input POST request of the form similar to below:</p>
        
        <pre><code>
{
    {
    "component":"org.eclipse.jdt.junit",
    "version":"3.7.100.v20120523-1543"
    },
    {
    "component":"org.eclipse.rcp"
    }
}

        </code></pre>
        
        <h3>
            
            Python Script for Testing:
            
        </h3>

        
        <p>If you wish to test it you can install a third party library <a href="http://docs.python-requests.org/en/latest/">Requests</a> and run a simple Python script to request POST and recive JSON response from the webservice:</p>

      
         <pre><code>

import requests
import json

data = [{"component":"org.eclipse.jdt.junit","version":"3.7.100.v20120523-1543"},{ "component":"org.eclipse.rcp","version":"4.3.0.v20130318-0959"}]

r = requests.post("<SCRIPT LANGUAGE="JavaScript">document.write(location.href)</SCRIPT>", data=json.dumps(data))
print r.json()
        </code></pre>

        <h4>
            The Possible Results of JSON response:
        </h4>
        <p>
            There are different states for the returned results:
            <ul>
            <li>Unavailable</li>
            <p>This is means that there is no entry for the requested component, the returned response is an empty json object {}.</p>
            <li>Available</li>
            <p>If one entry is found on the database. </p>

            <li>Alternative</li>
            <p>If more than one entry is found on the database with different version components, the returned json will be the latest build. </p>
            </ul>
            
        </p>
        </div>
    </body>
</html>

