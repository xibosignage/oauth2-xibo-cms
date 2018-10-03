<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2018 Spring Signage Ltd
 * (simple.php)
 */
require '../vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

# Monolog logger requires dev composer dependencies
$handlers = [
    new \Monolog\Handler\StreamHandler(STDERR, Logger::INFO)
];
$log = new Monolog\Logger('API LIBRARY', $handlers);

# Create a Xibo provider
$provider = new \Xibo\OAuth2\Client\Provider\Xibo([
    'clientId' => 'ZbFqaYQ3lyIY2EUw4d5soTgQv7eOv6G4dDMFeQZS',    // The client ID assigned to you by the provider
    'clientSecret' => 'oYe1bpapJcngVxQrMRxA9Vpiqe0mISc2OgIVLDbGYzN3k4ajXjeBnbk5Wl74dCgap8qOcCvJMolBjy9lFDsY1sQPgyBZ9i3lKpOr7863YpAVJBBqU86wE0KzPh3wSt7P9XgdLTHtyxniJerqL6AtI1nvzyXd6W1IJE2Feqr8LRiu1dthIo6j9ejuL1go7whkS2HOhpegZVIVAXlHhUq9sisYgO0qI9zmCxYE0wbUe9FTSvc1LrG7LJl9Alu0Ny',   // The client password assigned to you by the provider
    'redirectUri' => '',
    'baseUrl' => 'http://localhost'
], ['logger' => $log]);

# Required for guzzle calls in this file.

if (!isset($argv[1])) {
    $token = $provider->getAccessToken('client_credentials')->getToken();
    echo 'Token for next time: ' . $token;
}
else
    $token = $argv[1];


# Create Xibo Entity Provider with logger
$entityProvider = new \Xibo\OAuth2\Client\Provider\XiboEntityProvider($provider, [
'logger' => $log
]);

# Each wrapper is using request function which calls getAccessToken

# LAYOUT & REGION & Playlist & Library

 //   $layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->get(['layoutId'=>230, 'embed'=>'regions,playlists,widgets']);

	//$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('phpunit layout', 'phpunit layout', '', 9);
    //$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->getById(436);
    //$layout->retire();
    //$status = $layout->getStatus();
    //print_r($status->status);
	//$layout->addTag('tag1');
	//$layout->removeTag('tag1');
    //$template = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->createTemplate(245, 1, 'phpunit template', null, null);
	//$layout->delete();

	#$layout->edit('Edited phpunit layout', 'Edit', null, 0, $layout->backgroundColor, $layout->backgroundImageId, $layout->backgroundzIndex, $layout->resolutionId);
	#$layoutCopy = $layout->copy('phpunit layout copy', 'copied layout', 0);
	#$layout->createRegion(200, 300, 500, 20);
	#$region = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($layout->layoutId, 400, 200, 20, 10);
    #$region2 = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($layout->layoutId, 400, 200, 20, 10);
    #$region3 = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($layout->layoutId, 400, 200, 20, 10);
    #$layout->positionAll([$region->regionId, $region2->regionId, $region3->regionId], [600, 200, 200], [500, 250, 800], [100, 230, 230], [500, 200, 500]);
    #$position = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->positionAll($layout->layoutId, [$region->regionId, $region2->regionId, $region3->regionId], [600, 200, 200], [500, 250, 800], [100, 230, 230], [500, 200, 500]);
/*
	$media = (new \Xibo\OAuth2\Client\Entity\XiboLibrary($entityProvider))->create('API image', 'files\53.jpg');
	$media2 = (new \Xibo\OAuth2\Client\Entity\XiboLibrary($entityProvider))->create('API image Replacement', 'files\20.png', $media->mediaId, 1, 1);
	$playlist = (new \Xibo\OAuth2\Client\Entity\XiboPlaylist($entityProvider))->assign([$media2->mediaId], 10, $region->playlists[0]['playlistId']);
	$region->edit(200, 500, 30, 100, $region->zIndex, $region->loop);
	$region->delete();
	$media2->deleteAssigned();
	$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->getById($layout->layoutId);
	$layout->delete();
*/

# Library search and download
/*
    $library = (new Xibo\OAuth2\Client\Entity\XiboLibrary($entityProvider))->get(['media'=> 'Image', 'start' => 0, 'length' => 1000]);

    foreach ($library as $item) {
        $media = (new Xibo\OAuth2\Client\Entity\XiboLibrary($entityProvider))->download($item->mediaId, $item->mediaType, 'files/' , $item->fileName);
    }
*/


# Campaign

    //$campaign = (new \Xibo\OAuth2\Client\Entity\XiboCampaign($entityProvider))->create('test campaign');
    //$campaign = (new \Xibo\OAuth2\Client\Entity\XiboCampaign($entityProvider))->getById(456);
    //$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('phpunit layout', 'phpunit layout', '', 9);
    //$campaign->assignLayout([435, 436, 425], [1, 3, 2]);
    //$campaign->delete();
    //$layout->delete();


# RESOLUTION 
/*
	$res = (new \Xibo\OAuth2\Client\Entity\XiboResolution($entityProvider))->create('API test resolution', 2069, 1069);
	$res->edit('edited resolution name', 200, 300);
	$res = (new \Xibo\OAuth2\Client\Entity\XiboResolution($entityProvider))->getById($res->resolutionId);
	$res->delete();
*/

# Widgets
/*
	$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('phpunit layout', 'phpunit layout', '', 9);
	$region = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($layout->layoutId, 400, 200, 20, 10);
    $media = (new \Xibo\OAuth2\Client\Entity\XiboLibrary($entityProvider))->create('API audio', 'files\SampleAudio.mp3');
    $clock = (new \Xibo\OAuth2\Client\Entity\XiboClock($entityProvider))->create('Api Analogue clock', 20, 1, 1, 1, NULL, NULL, NULL, NULL, $region->playlists[0]['playlistId']);
    $text = (new \Xibo\OAuth2\Client\Entity\XiboText($entityProvider))->create('Text item', 10, 1, 'marqueeRight', 5, null, null, 'TEST API TEXT', null, $region->playlists[0]['playlistId']);
    $audio = (new \Xibo\OAuth2\Client\Entity\XiboAudio($entityProvider))->assignToWidget($media->mediaId, 50, 1, $clock->widgetId);
    $layout->delete();
*/

# DataSets dataSet View
/*
    $dataSet = (new Xibo\OAuth2\Client\Entity\XiboDataSet($entityProvider))->create('test dataSet', 'test description');
    $copy = (new Xibo\OAuth2\Client\Entity\XiboDataSet($entityProvider))->copy($dataSet->dataSetId, 'new name', 'new description');

    $dataSet = (new \Xibo\OAuth2\Client\Entity\XiboDataSet($entityProvider))->getById(3);
    $dataSetColumn = $dataSet->getByColumnId(6);
    $dataSetRow = $dataSet->getData();

    $dataSet = (new \Xibo\OAuth2\Client\Entity\XiboDataSet($entityProvider))->get(['dataSetId' => 3, 'embed' => 'columns']);
    $columns = $dataSet[0]->getColumns();
    $layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('phpunit layout', 'phpunit layout', '', 9);
    $region = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($layout->layoutId, 400, 200, 20, 10);
    $dataSetView = (new \Xibo\OAuth2\Client\Entity\XiboDataSetView($entityProvider))->create($dataSet[0]->dataSetId, $region->playlists[0]['playlistId']);
    $dataSetViewEdited = (new \Xibo\OAuth2\Client\Entity\XiboDataSetView($entityProvider))->edit('dataSet View', 20, 1, [$columns[0]->dataSetColumnId,$columns[1]->dataSetColumnId], 20, 5, 1, null, null, null, null, null, null, null, null, null, $dataSetView->widgetId);
    $layout->delete();
*/
# Dayparts
/*
    $daypartNew = (new \Xibo\OAuth2\Client\Entity\XiboDaypart($entityProvider))->create('phpunit daypart', 'API', '03:00', '06:00', ['Mon' , 'Tue'], ['03:00' , '04:00'], ['05:00' , '06:00']);
    $daypartNew->edit('phpunit daypart Edited', 'APII', '06:00', '10:00');
    $dayparts = (new \Xibo\OAuth2\Client\Entity\XiboDaypart($entityProvider))->get(['name' => $daypartNew->name]);
    $daypartsById = (new \Xibo\OAuth2\Client\Entity\XiboDaypart($entityProvider))->getById($daypartNew->dayPartId);
    $daypartsById->delete();
*/

# Displays
/*
    $display = (new \Xibo\OAuth2\Client\Entity\XiboDisplay($entityProvider))->getById(9);
    $display->edit('Edited Name', 'PHP description', null, 1, 1, $display->license);
    $display->screenshot();
    $display->wol();
    $display->delete();
*/

# Schedule
/*
    $displayGroup = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->create('phpunit displaygroup', 'phpunit displaygroup', 0, '');
    $layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('phpunit layout', 'phpunit layout', '', 9);
    $region = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($layout->layoutId, 400, 200, 20, 10);
    $clock = (new \Xibo\OAuth2\Client\Entity\XiboClock($entityProvider))->create('Api Analogue clock', 20, 1, 1, 1, NULL, NULL, NULL, NULL, $region->playlists[0]['playlistId']);
    $event = (new \Xibo\OAuth2\Client\Entity\XiboSchedule($entityProvider))->createEventLayout(
        null,
        null,
        $layout->campaignId,
        [$displayGroup->displayGroupId],
        4,
        NULL,
        NULL,
        NULL,
        0,
        0
        );
    $scheduleGID = (new \Xibo\OAuth2\Client\Entity\XiboSchedule($entityProvider))->getById(['displayGroupIds' => [4]], 44);
    //print_r($scheduleGID[0]->id);
    $scheduleG = (new \Xibo\OAuth2\Client\Entity\XiboSchedule($entityProvider))->get(['displayGroupIds' => [$displayGroup->displayGroupId]]);
    //print_r($scheduleG[0]->id);
    //print_r($scheduleG[0]->event);
    //print_r($scheduleG[0]->event['eventId']);
    //print_r($scheduleG[0]->event['displayGroups']);
    //print_r($scheduleG[0]->event['displayGroups'][0]['displayGroupId']);
    $scheduleGE = (new \Xibo\OAuth2\Client\Entity\XiboSchedule($entityProvider))->getEvents($displayGroup->displayGroupId, '2018-03-13 00:40:00');
    $event->delete();
    $layout->delete();
    $displayGroup->delete();
*/
    
# Notification View
/*
    $layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('phpunit layout', 'phpunit layout', '', 9);
    $region = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($layout->layoutId, 400, 200, 20, 10);
    $notificationView = (new \Xibo\OAuth2\Client\Entity\XiboNotificationView($entityProvider))->create('test create notification widget', 10, 1, 0, 'No notifications found', null, null, 1, null, $region->playlists[0]['playlistId']);
    $notificationView = (new \Xibo\OAuth2\Client\Entity\XiboNotificationView($entityProvider))->edit('test edit notification widget', 20, 1, 30, 'No notifications found', null, null, 0, null, $notificationView->widgetId);
    $notificationView->delete();
    $layout->delete();
*/

# Display Groups
/*
$displayGroup = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->create('phpunit displaygroup', 'phpunit displaygroup', 0, '');
print_r($displayGroup->displayGroup);
$displayGroup->edit('edited name', 'edited description', 0, '');
$displayGroup = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->getById($displayGroup->displayGroupId);
print_r($displayGroup->displayGroup);
$displayGroup->assignDisplay(9);
$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('phpunit layout', 'phpunit layout', '', 9);
$displayGroup->assignLayout($layout->layoutId);
$displayGroup->collectNow();
$displayGroup->clear();
$displayGroup->changeLayout($layout->layoutId, 10, 1, 'replace');
$displayGroup->delete();
$layout->delete();
*/


# Stats
/*
$stats = (new \Xibo\OAuth2\Client\Entity\XiboStats($entityProvider))->get(['fromDt' => '2018-04-11 09:00:00', 'toDt' => '2019-04-12 09:00:00', 'type' => 'media']);
$log->info(json_encode($stats, JSON_PRETTY_PRINT));
*/

# Guzzle GET, PUT, POST
/*
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

    echo 'Body: ' . $response->getBody() . PHP_EOL;

$response = $guzzle->request('DELETE', 'http://192.168.0.26/api/library/1223', [
    'headers' => [
        'Authorization' => 'Bearer ' . $token,
        'Content-type' => 'application/x-www-form-urlencoded'
    ],
    'form_params' => [
        'forceDelete' => 1
    ]
]);

*/
