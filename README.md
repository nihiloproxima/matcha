**PHP Project. Custom MVC framework**

Starting project :

Run theses commands :

`sh setup.sh`

Then don't forget to go to ```http://localhost/config/setup``` to create your tables.

You'll also need to fill api keys in ```Models/AdressModel.php``` and ```Models/UserModel``` to make location work properly,
aswell as putting your google credentials in ```client_credentials.json``` for OAuth authentication...

To generate activity, uncomment lines 49 and 50 in Api/server.js
