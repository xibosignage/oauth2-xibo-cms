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

$entityProvider = new \Xibo\OAuth2\Client\Provider\XiboEntityProvider($provider);


 //  $token = $provider->getAccessToken('client_credentials');

//echo $token->getToken() . "\n";
//echo $token->getExpires() . "\n";
//echo ($token->hasExpired() ? 'expired' : 'not expired') . "\n";
//print_r($entityProvider->getMe());
//print_r($provider);
//print_r($entityProvider);
//$resourceOwner = $provider->getResourceOwner($token);
//print_r($resourceOwner);

//$displayGroup = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->getById(21);

//echo 'Display Group ID ' . $displayGroup->displayGroupId;

// Try creating a new one
//$new = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->getById(44)->delete();

//var_export($new);

// Try creating a campaign
//$new = (new \Xibo\OAuth2\Client\Entity\XiboCampaign($entityProvider))->create('test campaign');

//echo json_encode($new, JSON_PRETTY_PRINT);

//Try creating new resolution 
$new = (new \Xibo\OAuth2\Client\Entity\XiboResolution($entityProvider))->create('test resolution', 2069, 1069);
//print_r($provider->token);
//echo 'sleeping for 10s'. PHP_EOL;
sleep(10);
$new = (new \Xibo\OAuth2\Client\Entity\XiboResolution($entityProvider))->create('test resolution 2', 1069, 1069);
//print_r($provider->token);
//echo json_encode($new, JSON_PRETTY_PRINT);

//$res = (new \Xibo\OAuth2\Client\Entity\XiboResolution($entityProvider))->getById(9);
//print_r($res);
//echo 'Resolution ' . $res->resolutionId;
//$me = $entityProvider->getMe();
//echo $me;
//create new display group
//$newDG = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->create('phpunit test group', 'Api', 0, 0);
//echo json_encode($newDG, JSON_PRETTY_PRINT);
//var_export($new);

//$new = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('test layout', 'test description', '', 9);

//echo json_encode($new, JSON_PRETTY_PRINT);

//$new = (new \Xibo\OAuth2\Client\Entity\XiboDisplayProfile($entityProvider))->create('test profile', 'android', 0);

//echo json_encode($new, JSON_PRETTY_PRINT);

//$newLayout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('test layout', 'test description', '', 9);
//$newLayout2 = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('test layout2', 'test description', '', 9);

//$newRegion = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($newLayout->layoutId,200,300,75,125);

//$region = $newRegion->regionId;

//echo json_encode($newLayout, JSON_PRETTY_PRINT);


//$dataSetNew = (new \Xibo\OAuth2\Client\Entity\XiboDataSet($entityProvider))->create('dataset name', 'dataset desc');
//$columnNew = (new \Xibo\OAuth2\Client\Entity\XiboDataSetColumn($entityProvider))->create($dataSetNew->dataSetId, 'column name','', 2, 1, 1, '');
//$column = $columnNew->dataSetColumnId;
//$rowNew = (new \Xibo\OAuth2\Client\Entity\XiboDataSetRow($entityProvider))->create($dataSetNew->dataSetId, $columnNew->dataSetColumnId,'Cabbage');
//$row = $rowNew->rowId;
//echo json_encode($row, JSON_PRETTY_PRINT);

//$assign = $newDG->assignLayout($newLayout->layoutId);
//$assign= $newDG->assignDisplaygroup($newDG2->displayGroupId);








/*

    # Guzzle GET, PUT, POST

    $guzzle = $provider->getHttpClient();

    $response = $guzzle->request('GET', 'http://192.168.0.26/api/about', [
        'headers' => [
            'Authorization' => 'Bearer ' . $token
        ],
    ]);
    
    $response = $guzzle->request('PUT', 'http://192.168.0.26/api/layout/66', [
        'headers' => [
            'Authorization' => 'Bearer ' . $token , 'Content-type' => 'application/x-www-form-urlencoded'
        ],
        'body' => http_build_query(['name' => 'Guzzle Edit'])
    ]);

    $response = $guzzle->request('POST', 'http://192.168.0.26/api/resolution', [
    	'headers' => [
    	    'Authorization' => 'Bearer ' . $token
    	],
    	'form_params' => [
            'resolution' => 'test',
            'width' => 690,
            'height' => 500
    	]
    ]);


    //
    echo 'Body: ' . $response->getBody() . PHP_EOL;
*/