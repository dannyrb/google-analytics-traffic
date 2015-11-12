<?php

	// MUST be using composer
  require_once '/home/csengineer13/dannyrb.com/traffic/vendor/autoload.php';
  session_start();	 	

	$client = new Google_Client();	 	
	$client->setApplicationName("API Project"); // Name of proj in GoogleDeveloperConsole

	// Generated in GoogleDeveloperConsole --> Credentials --> Service Accounts
	$client->setAuthConfig('/home/csengineer13/secure-creds/API Project-c0a14bc34a03.json');
	$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);

	// Grab token if it's set
	if (isset($_SESSION['service_token'])) {
	 	$client->setAccessToken($_SESSION['service_token']);
	}

	// Refresh if expired
	if ($client->isAccessTokenExpired()) {
		$client->refreshTokenWithAssertion();
	}

	// Pin to Session
	$_SESSION['service_token'] = $client->getAccessToken();

	$myToken = $client->getAccessToken();
?>
<html>
    <head>
        <title>Traffic for Dannyrb.com</title>
        <style>
        body { text-align: center; }
        .chart {
          width: 90%;
          margin: 0 auto;
        }
        </style>
    </head>
    <body>
        <!-- Google Analytics -->
        <script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-42182376-5', 'auto');
        ga('send', 'pageview');</script> 
        
        <!-- Load Google's Embed API Library -->
        <script>(function(w,d,s,g,js,fs){
        g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
        js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
        js.src='https://apis.google.com/js/platform.js';
        fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
        }(window,document,'script'));</script>
        
        <h1>Traffic For The Last 30 Days</h1>

        <div id="chart-1-container" class="chart"></div>
        <div id="chart-2-container" class="chart"></div>

        <script>

        gapi.analytics.ready(function() {

          /**
           * Authorize the user with an access token obtained server side.
           */
          gapi.analytics.auth.authorize({
            'serverAuth': {
              'access_token': '<?php print_r($myToken["access_token"]); ?>'
            }
          });


          /**
           * Creates a new DataChart instance showing sessions over the past 30 days.
           * It will be rendered inside an element with the id "chart-1-container".
           */
          var dataChart1 = new gapi.analytics.googleCharts.DataChart({
            query: {
              'ids': 'ga:110849216', // The Demos & Tools website view.
              'start-date': '30daysAgo',
              'end-date': 'yesterday',
              'metrics': 'ga:sessions,ga:users',
              'dimensions': 'ga:date'
            },
            chart: {
              'container': 'chart-1-container',
              'type': 'LINE',
              'options': {
                'width': '100%'
              }
            }
          });
          dataChart1.execute();


          /**
           * Creates a new DataChart instance showing top 5 most popular demos/tools
           * amongst returning users only.
           * It will be rendered inside an element with the id "chart-3-container".
           */
          var dataChart2 = new gapi.analytics.googleCharts.DataChart({
            query: {
              'ids': 'ga:110849216', // The Demos & Tools website view.
              'start-date': '30daysAgo',
              'end-date': 'yesterday',
              'metrics': 'ga:pageviews',
              'dimensions': 'ga:pagePathLevel2',
              'sort': '-ga:pageviews',
              'filters': 'ga:pagePathLevel1!=/',
              'max-results': 10
            },
            chart: {
              'container': 'chart-2-container',
              'type': 'PIE',
              'options': {
                'width': '100%',
                'pieHole': 4/9,
              }
            }
          });
          dataChart2.execute();

        });
        </script>
    </body>
</html>