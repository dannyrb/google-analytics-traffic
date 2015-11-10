<?php

	// MUST be using composer
   	require_once '/home/csengineer13/dannyrb.com/traffic/vendor/autoload.php';
   	session_start();	 	

	$client = new Google_Client();	 	
	$client->setApplicationName("API Project");

	// Generated in GoogleDeveloperConsole --> Credentials --> Service Accounts
	$client->setAuthConfig('API Project-c0a14bc34a03.json');
	$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);


	// Set token, or fetch token
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


	// $service = new Google_Service_Analytics($client);

	// Adding Dimensions
	// $params = array('dimensions' => 'ga:userType');	
	// requesting the data	
	// $data = $service->data_ga->get("ga:110849216", "2015-10-01", "2015-11-08", "ga:users,ga:sessions", $params );	 
?>
<html>
    <head>
        <title>Test</title>
    </head>
    <body>
        <!-- Load Google's Embed API Library -->
        <script>
            (function(w,d,s,g,js,fs){
            g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
            js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
            js.src='https://apis.google.com/js/platform.js';
            fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
            }(window,document,'script'));
        </script>

        <div id="chart-1-container"></div>
        <div id="chart-2-container"></div>

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
              'dimensions': 'ga:pagePathLevel1',
              'sort': '-ga:pageviews',
              'filters': 'ga:pagePathLevel1!=/',
              'max-results': 7
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