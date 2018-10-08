<?php 
require_once(__DIR__.'/common.php'); 
require_once(__DIR__.'/vendor/autoload.php');
require_once(__DIR__.'/GoogleClient.php');
?>
<!DOCTYPE html>
<html lang="en">
  <?php require_once(__DIR__.'/header.php'); ?>
  <body>
	<?php require_once(__DIR__.'/navbar.php'); ?>
	<div class="container" style="margin-top:80px">
	  	<h1 class="text-center">Upload Drive Files</h1>
		<?php 
		try{
			$directory_path = __DIR__.'/uploads/';
			$response = \GoogleClient::getClient();
			if($response['success'] == 1){
				$client = $response['client'];
				if(isset($_POST['submit'])){
					$file = $_FILES['file'];
					if (!is_dir($directory_path)) {
						if(!mkdir($directory_path, 0777, true)){
							echo '<p>Unable to create folder, Please create folder uploads and try again</p>';
							die;
						}
					} else {
						$target_file_name = time().'_'.$file['name'];
						$target_file = $directory_path.$target_file_name;
						if(move_uploaded_file($file["tmp_name"], $target_file)){
							$service = new \Google_Service_Drive($client);
							$file = new \Google_Service_Drive_DriveFile();
						    $file->setName($target_file_name);
						    $file->setDescription('A test file');
						    $file->setMimeType($file['type']);

						    $data = file_get_contents($target_file);

						    $createdFile = $service->files->create($file, array(
					          'data' => $data,
					          'mimeType' => $file['type'],
					          'uploadType' => 'multipart'
					        ));

							if($createdFile != false){
								echo '<p>File '.$name.' uploaded successfully</p>';
								die;
							} else {
								echo '<p>Unable to upload file to drive, Please try again later</p>';
								die;	
							}
						} else { 
							echo '<p>Unable to upload the file, Please try again later</p>';
							die;
						}
					}
				}
				?>
				<form action="" method="post" enctype="multipart/form-data">
		        <div class="form-group">
			        <label for="auth-code">File</label>
			        <input type="file" name="file" id="file" />
		        </div>
		        <input type="submit" name="submit" value="submit" class="btn btn-primary" /><br>
		        </form>
		        <?php
			} else {
				echo $response['html'];
				die;
			}

		} catch(\Exception $e){
			echo '<p>Exception occured:'.$e->getMessage().'</p>';
			echo '<p>If issue is in Google api, remove data in token.json and refresh this page and try again.</p>';
			die;
		}
		?>
	</div>
	<?php require_once(__DIR__.'/footer.php'); ?>
  </body>
</html>