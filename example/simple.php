<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2015 Spring Signage Ltd
 * (simple.php)
 */
require '../vendor/autoload.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a provider
$provider = new \Xibo\OAuth2\Client\Provider\Xibo([
    'clientId' => 'Uzp5Ro0Wz55WbFdyCdvTZdCf4iCSOpdGRswLdb94',    // The client ID assigned to you by the provider
    'clientSecret' => 'RM7yVOKgLgqSLXr4MkHJ2ZlJ0JJwlEKIiezdCTchyhThBroN3y8XXtotN7RjtQxp0MhdfSuYe9SkWMZkmbXnOVTgmXTj97V7q5SkXSTHt4Idtb3dgIvRciNvgVmM0OShIuvZNAIpxC2INjL9yzIdZtUtz6pZXD4LR98P0rZhsDMjOSwsbhfOrDXhGKaTipYfaST0juLFtDXl00Ey41OUNxuEqtV0ZIAAMagzIiIgaq9xxwOk0nzCm6ZbNeZejo',   // The client password assigned to you by the provider
    'redirectUri' => 'http://172.28.128.4/example/simple.php',
    'baseUrl' => 'http://172.28.128.3'
]);

// Our code will be passed around in the URL
if (!isset($_GET['code'])) {
    // Get the URL
    $authUrl = $provider->getAuthorizationUrl();

    $_SESSION['oauth2state'] = $provider->getState();

    // Go to the auth URL
    header('Location: ' . $authUrl);
} else if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    // The state we saved during the original request is different to the one we have received, therefore deny
    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Output the token - you would usually store this somewhere private.
    echo json_encode($token);

    // Get me :)
    $me = $provider->getResourceOwner($token);

    echo json_encode($me);
}