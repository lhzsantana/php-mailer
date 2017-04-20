# Silex Simple REST Notifier

#### How do I run it?
After download the last [release](https://github.com/lhzsantana/php-mailer), from the root folder of the project, run the following commands to install the php dependencies, import some data, and run a local php server.

You need php **5.5.9***, **Composer**, and **Python** installed. Also, you need to point the code to Redis in two files **/Python/notifier.py** and **/src/app.php** (replace 192.168.99.100:6379 with the correct Redis installation). You can use **docker run -p 6379:6379 lhzsantana/redis-standalone**.
    
    composer install 
    php -S 0:9001 -t web/
    python Python/notifier.py

Your api is now available at http://localhost:9001/api/v1.

#### What you will get
The api for notification will respond to

	GET  ->   http://localhost:9001/api/v1/notification
    GET  ->   http://localhost:9001/api/v1/notification/{id}
	POST ->   http://localhost:9001/api/v1/notification
	DELETE -> http://localhost:9001/api/v1/notification/{id}

The api will subscriber will respond to
	
    GET  ->   http://localhost:9001/api/v1/notification/subscriber/{id}
	DELETE -> http://localhost:9001/api/v1/notification/subscriber/{id}







