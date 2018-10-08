<?php 
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/GoogleClient.php';
require_once __DIR__ . '/common.php';

try{
	$directory_path = __DIR__.'/json/';

	$token_file = 'token.json';
	$token_path = $directory_path.$token_file;

	$authCode = isset($_GET['code']) ? $_GET['code'] : '';
	if(!empty($authCode)){
		$client = new \Google_Client();
	    $client->setApplicationName('Google Drive Egriffon');
	    $client->setScopes(\Google_Service_Drive::DRIVE);
	    $client->setAuthConfig(__DIR__.'/json/credentials.json');
	    $client->setAccessType('online');

		// Exchange authorization code for an access token.
	    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

	    $tokenPath = __DIR__.'/json/token.json';

	    // Check to see if there was an error.
	    if (array_key_exists('error', $accessToken)) {
	        echo implode(', ', $accessToken);
	        die;
	    }

	    if (!is_dir($directory_path)) {
			if(!mkdir($directory_path, 0777, true)){
				echo '<p>Unable to create folder, Please create folder json and try again</p>';
				die;
			}
		} else {
			if(!file_exists($token_path)){
				$create_file = shell_exec('sudo nano '.$token_path);
				
				if($create_file == false){
					echo '<p>Unable to create file, Please create empty file token.json in json folder and try again</p>';
					die;
				}
			} else {
				$write_file = file_put_contents($token_path, json_encode($accessToken));
				if($write_file == false){
					echo '<p>Unable to write to the file, give permission to write and try again</p>';
					die;
				} else {
					$redirect_url = $GLOBALS['baseUrl'].'index.php';
					echo '<script>window.location="'.$redirect_url.'";</script>';
					echo '<p>Credentials saved to file: '.$tokenPath.'</p>';
					echo '<p><a href="'.$redirect_url.'">Click Here</a> to go to files list</p>';
					die;
				}
			}
		}
	} else {
		echo 'Auth Code is empty, Try again';
	}
} catch(\Exception $e){
	echo '<p>Exception occured:'.$e->getMessage().'</p>';
	echo '<p>If issue is in Google api, remove data in token.json and refresh this page and try again.</p>';
}