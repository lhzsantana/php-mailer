# Silex Simple REST Notifier

## How do I run it?
After download the last [release](https://github.com/lhzsantana/php-mailer), from the root folder of the project, run the following commands to install the php dependencies, import some data, and run a local php server.

You need php **5.5.9***, **Composer**, and **Python** installed. Also, you need to point the code to Redis in two files **/Python/notifier.py** and **/src/app.php** (replace the "redis" placeholder with the correct Redis installation), and change the smtp settings (server and FROM) on the Python file. You can use **docker run -p 6379:6379 lhzsantana/redis-standalone**.
    
    composer install 
    php -S 0:9001 -t web/
    python Python/notifier.py

Your api is now available at http://localhost:9001/api/v1.

## What you will get

![alt tag](https://github.com/lhzsantana/php-mailer/blob/master/architecture.jpg)

The api for notification will respond to

	GET  ->   http://localhost:9001/api/v1/notification
    GET  ->   http://localhost:9001/api/v1/notification/{id}
	DELETE -> http://localhost:9001/api/v1/notification/{id}
	POST ->   http://localhost:9001/api/v1/notification

The format for adding a notification request is represented below.
The response will be have an UUID and a list of failures (it can be empty).

    {
      "channels": [
        "PUSH_SUBSCRIBE", "EMAIL"
      ],
      "message": {
        "message": "m",
        "subject": "s"
      },
      "subscribers": [
        {
          "email": "x@y.com",
          "name": "X Y"
        }
      ]
    }
    
The api will subscriber will respond to:
	
    GET  ->   http://localhost:9001/api/v2/notification/subscriber/{id}
	DELETE -> http://localhost:9001/api/v2/notification/subscriber/{id}

These endpoints will be protected using OAuth2. If you are using Postman, you can generate an access token as defined below.

![alt tag](https://github.com/lhzsantana/php-mailer/blob/master/auth1.jpg)

Then you can add this access token to the request issued to endpoints presented before.

    http://localhost:9001/api/v1/notification?access_token=473aed78d33801ccd94bb691741a913e27f0ad97
    
## Docker

You can run the application in a Docker environment.

### PHP

    docker build . -t php-mailer:api
    docker run -p 9001:9001 php-mailer:api

### Python

    cd Python
    docker build . -t php-mailer:runner
    docker run php-mailer:runner

### Compose (you will need docker compose installed)

    docker-compose up





