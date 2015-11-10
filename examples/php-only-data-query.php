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

	$service = new Google_Service_Analytics($client);

	// Adding Dimensions
	$params = array('dimensions' => 'ga:userType');	
	// requesting the data	
	$data = $service->data_ga->get("ga:110849216", "2015-10-01", "2015-11-08", "ga:users,ga:sessions", $params );	 
?>
<html>
	<head>
		<title>PHP Only: Data Query</title>
	</head>
	<body>

	<h1>Results for:  2015-10 to 2015-11-08</h1>

		<table border="1">	 
			<tr>	 
				<?php	 
				//Printing column headers
				foreach($data->getColumnHeaders() as $header){
					 print "<td><b>".$header['name']."</b></td>"; 	 	
					}	 	
				?>	 	
			</tr>	 	
				<?php	 	
				//printing each row.
				foreach ($data->getRows() as $row) { 	 	
					print "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td></tr>"; 	 
				}	 
				?>	 	
			<tr>
				<td colspan="2">Rows Returned <?php print $data->getTotalResults();?> </td>
			</tr>	 
		</table>

	</body>	 
</html>