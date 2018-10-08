<?php 

require_once __DIR__ . '/vendor/autoload.php';

class GoogleClient
{
	public static function getClient()
	{
		$client = new \Google_Client();
	    $client->setApplicationName('Google Drive Egriffon');
	    $client->setScopes(\Google_Service_Drive::DRIVE);
	    $client->setAuthConfig(__DIR__.'/json/credentials.json');
	    $client->setAccessType('offline');
	    $client->setIncludeGrantedScopes(true);
	    
	    // Load previously authorized credentials from a file.
	    $token_path = __DIR__.'/json/token.json';
	    if (file_exists($token_path)) {
	        $accessToken = json_decode(file_get_contents($token_path), true);
	        
		    if(isset($accessToken['access_token'])){
			    $client->setAccessToken($accessToken);

			    // Refresh the token if it's expired.
			    if ($client->isAccessTokenExpired()) {
			        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
			        //file_put_contents($token_path, json_encode($client->getAccessToken()));
			    }
		        // print_r($client->getAccessToken());die;
			    $response = ['success' => 1, 'client' => $client];
			    return $response;
		    }
	    }
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();

        $html = "Open the following link in your browser<br>";
        $html .= '<a href="'.$authUrl.'">Click Here</a>';
	    $response = ['success' => 0, 'html' => $html];
	    return $response;
	}
}