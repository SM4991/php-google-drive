Description
----------------
This project provides option to view files added under google drive and upload files to google drive using OAuth 2.0 Via PHP

Installation
------------------------
1. Clone the folder
2. Run composer install
3. Edit file common.php and add your project base url


Steps to Create Project under google and use it to access Drive data
---------------------------------------------------------------------
1. Go to https://console.developers.google.com 
2. Create a new project in google developer console eg. Google-drive-test
3. Go to library and enable Google Drive API
4. Go to Credetials and Generate Oauth2.0 client id for Web Application
5. Add Authorized url as rg.http://host/google-drive/auth.php and save 
6. Download Client Credentials json file
7. Replace the json/credentials.json file content with your downloaded file's content
8. Go to Account settings of your google account and allow less secure apps to access your data
9. Run the project

