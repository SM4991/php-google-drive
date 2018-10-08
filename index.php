<?php require_once(__DIR__.'/common.php'); ?>
<!DOCTYPE html>
<html lang="en">
  <?php require_once(__DIR__.'/header.php'); ?>
  <body>
	<?php require_once(__DIR__.'/navbar.php'); ?>
	<div class="container" style="margin-top:80px">
	  	<h1 class="text-center">Drive</h1>
		<?php 
		require_once(__DIR__.'/vendor/autoload.php');
		require_once(__DIR__.'/GoogleClient.php');

		$response = \GoogleClient::getClient();

		try{
			if($response['success'] == 0){
			 	echo $response['html'];
			} else {
				$client = $response['client'];
				$service = new \Google_Service_Drive($client);
				$optParams = array(
				  'pageSize' => 100,
				  'fields' => 'files'
				);
				
				$results = $service->files->listFiles($optParams);
				if (count($results->getFiles()) == 0) {
				    echo  "No files found<br>";
				} else {
				    echo '<table class="table table-sm table-striped table-bordered" style="margin-top: 20px;">';
					echo '<thead>';
					echo '<tr><th>#</th><th>Name</th><th>ID</th></tr>';
					echo '</thead>';
					echo '<tbody>';
					$s = 1;
					foreach ($results->getFiles() as $file) {
						echo '<tr><td>'.$s.'</td><td>'.$file->getName().'</td><td>'.$file->getId().'</td></tr>';
						$s++;
					}
					die;
					echo '</tbody>';
					echo '</table>';
				}
			}
		} catch(\Exception $e){
			echo '<p>Exception occured:'.$e->getMessage().'</p>';
			echo '<p>If issue is in Google api, remove data in token.json and refresh this page and try again.</p>';
		}
		?>
	</div>
	<?php require_once(__DIR__.'/footer.php'); ?>
  </body>
</html>