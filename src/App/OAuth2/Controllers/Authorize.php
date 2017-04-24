<?php

namespace App\OAuth2\Controllers;

use Silex\Application;

class Authorize
{
    // Connects the routes in Silex
    public static function addRoutes($routing)
    {
        $routing->get('/authorize', array(new self(), 'authorize'))->bind('authorize');
    }

    /**
     * The user is directed here by the client in order to authorize the client app
     * to access his/her data
     */
    public function authorize(Application $app)
    {
        // get the oauth server (configured in src/OAuth2Demo/Server/Server.php)
        $server = $app['oauth_server'];

         // get the oauth response (configured in src/OAuth2Demo/Server/Server.php)
        $response = $app['oauth_response'];

        // validate the authorize request.  if it is invalid, redirect back to the client with the errors in tow
        if (!$server->validateAuthorizeRequest($app['request_stack']->getCurrentRequest() , $response)) {
            return $server->getResponse();
        }
        // call the oauth server and return the response
        return $server->handleAuthorizeRequest($app['request_stack']->getCurrentRequest() , $response, true);
    }
}
