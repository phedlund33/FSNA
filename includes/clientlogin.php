<?php

class ClientLogin {
  public static function getAuthToken($username, $password) {
	
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
	$token = $auth;
    /*
	$clientlogin_curl=curl_init();
    curl_setopt($clientlogin_curl,CURLOPT_URL,'https://www.google.com/accounts/ClientLogin');
    curl_setopt($clientlogin_curl, CURLOPT_POST, true); 
    curl_setopt ($clientlogin_curl, CURLOPT_POSTFIELDS,
            "Email=".$username."&Passwd=".$password."&service=fusiontables&accountType=GOOGLE");
    curl_setopt($clientlogin_curl,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($clientlogin_curl,CURLOPT_RETURNTRANSFER,1);
    $token = curl_exec($clientlogin_curl);
    curl_close($clientlogin_curl);
	echo "token=".$token;
    $token_array = explode("=", $token);
    $token = str_replace("\n", "", $token_array[3]);
	*/
	
	
    return $token;
  }
}



class FTClientLogin {
  function __construct($token) {
    $this->token = $token;
  }
  
  function query($query) {
    
    $fusiontables_curl=curl_init();
    if(preg_match("/^select|^show tables|^describe/i", $query)) { 
          $query =  "sql=".urlencode($query);
      curl_setopt($fusiontables_curl,CURLOPT_URL,"http://www.google.com/fusiontables/api/query?".$query);
      curl_setopt($fusiontables_curl,CURLOPT_HTTPHEADER, array("Authorization: GoogleLogin auth=".$this->token));
    
    } else {
          $query = "sql=".urlencode($query);
      curl_setopt($fusiontables_curl,CURLOPT_POST, true);
      curl_setopt($fusiontables_curl,CURLOPT_URL,"http://www.google.com/fusiontables/api/query");
      curl_setopt($fusiontables_curl,CURLOPT_HTTPHEADER, array( 
        "Content-length: " . strlen($query), 
        "Content-type: application/x-www-form-urlencoded", 
        "Authorization: GoogleLogin auth=".$this->token         
      )); 
      curl_setopt($fusiontables_curl,CURLOPT_POSTFIELDS,$query); 
    }
    
    curl_setopt($fusiontables_curl,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($fusiontables_curl,CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($fusiontables_curl);
    curl_close($fusiontables_curl);
    return $result;
  }
}

?>
