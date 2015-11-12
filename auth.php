<?php
include('includes/clientlogin.php');
include('includes/sql.php');
include('includes/file.php');


// Construct an HTTP POST request
$clientlogin_url = "https://www.google.com/accounts/ClientLogin";
$clientlogin_post = array(
    "accountType" => "HOSTED_OR_GOOGLE",
    "Email" => "phedlund@gmail.com",
    "Passwd" => "Moloko6+7",
    "service" => "fusiontables",
    "source" => "your application name"
);

// Initialize the curl object
$curl = curl_init($clientlogin_url);

// Set some options (some for SHTTP)
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $clientlogin_post);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// Execute
$response = curl_exec($curl);

// Get the Auth string and save it
preg_match("/Auth=([a-z0-9_\-]+)/i", $response, $matches);
$auth = $matches[1];

echo "The auth string is: " . $auth;

$ftclient = new FTClientLogin($auth);


//show all tables
echo $ftclient->query(SQLBuilder::showTables());
echo "<br />";

//describe a table
echo $ftclient->query(SQLBuilder::describeTable(674831));
echo "<br />";

//insert into table (test, test2, 'another test') values (12, 3.3333, 'bob')
echo $ftclient->query(SQLBuilder::insert(674831, array('AgendaDate'=>'May 11, 2001', 'AgendaItem' => 'JPA Bridge', 'Presenter' => 'Hardy Whitten', 'URL' => 'http://www.google.com')));
?>