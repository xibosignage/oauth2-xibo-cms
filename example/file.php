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
    'clientId' => 'xM69rqgYM24IwOSvGcqV9UgngvnA1BlUz6LFF3kH',    // The client ID assigned to you by the provider
    'clientSecret' => 'p8IRW5FmOYHbZjuOQJk43cugkxIwwxYaZv7K4FnMePkTfidbC6kmlV0u7PVHXtwSpxdMJG5cAO75Ii54zfh6kkQa31c5pxzCNsiCckSNoJECiA04iYDwKMrLpgVz3IH73c0zuzbEX5HopzmnyWBbXMZx9m58ncfG8vn4SiwlYrdTf4bwAQDrD1ueDpJimEHRgRXfMiPfGcA45NaF0GBFmjr5q0y3Iaj75YdXgiD9qzv2x1BFaX5HZGPjaOpl4h',   // The client password assigned to you by the provider
    'redirectUri' => '',
    'baseUrl' => 'http://192.168.0.26'
]);

if (!isset($argv[1])) {
    $token = $provider->getAccessToken('client_credentials')->getToken();
    echo 'Token for next time: ' . $token;
}
else
    $token = $argv[1];

try {
    // Prepare a file upload
    $guzzle = $provider->getHttpClient();
    $response = $guzzle->request('POST', 'http://192.168.0.26/api/library', [
        'headers' => [
            'Authorization' => 'Bearer ' . $token
        ],
        'multipart' => [
            [
                'name' => 'name',
                'contents' => 'API upload 2'
            ],
            [
                'name' => 'files',
                'contents' => fopen('files\example.jpg', 'r')
            ]
        ]
    ]);


    // Get both
    echo 'Body: ' . $response->getBody() . PHP_EOL;
}
catch (\GuzzleHttp\Exception\RequestException $e) {
    echo 'Client Exception: ' . $e->getMessage() . PHP_EOL;

    if ($e->hasResponse()) {
        echo $e->getResponse()->getBody() . PHP_EOL;
    }
}