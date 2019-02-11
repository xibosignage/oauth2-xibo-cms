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
$clientId20 = '98uQ4fn3I2MYaB6a9NQrnxpJoBwFFJV7ybjbH6gk';
$clientSecret20 = 'BVrsnxW7kKtx0OKv0N3sfkEyyztIpgMaQ3NdgpyJIECqnO3L8gWMErDzys8iP360CgqJeFep0fawquHhAGeACSdsgMT9RIDjTlEZaVkb0b2DnPN4dfpGx8oNQbIVq2lsmK9CD9aXCaRuY2ijb4Ei4HK6aDO6UneqSQGqTNVGCU37b18jPrRrwzHcwXHD0HUZXArWnB5b7lTR2g8iL3ertnAKbWKjVad4giUjBBQgSNPeb16iQ4Q9kFZkHxEBnT';
$clientId18 = 'xlWWgZbuydwhJxAoGtrUKRfXWlmpa83z7XTBl7gE';
$clientSecret18 = 'tSKxZGTTrHLt1ZyacE3mqGpt0yzUzk2mTcYkqAlcPifWGnMJgLYFj0HJJmX2cXbnsc4FK8DeiQOv4HvwtSWqL0YSb1GaHLJfBHDWLGsrQY4CAO3gYyRTPL1LccsSAq3ZrZLsNRqb6hpLsgbYWu8kEkYwWBztd4nROufyANNTPKyX7DLg31onTyHhPs7i0bKuF97V7y94Y7885heTc2q1Xl2OX3QiY6S0a1nPZFs5KzAIzp21nPpZ66xOl1hM8i';

$provider = new \Xibo\OAuth2\Client\Provider\Xibo([
    'clientId' => $clientId20,    // The client ID assigned to you by the provider
    'clientSecret' => $clientSecret20,   // The client password assigned to you by the provider
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

    //$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->get(['layoutId'=>39, 'embed'=>'regions,playlists,widgets']);

	#$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('phpunit layout2', 'phpunit layout', '', 1);
    #$layoutId = $layout->layoutId;
	//$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->getById(436);
    //$layout->retire();
    //$status = $layout->getStatus();
	//$layout->addTag('tag1');
	//$layout->removeTag('tag1');
    //$template = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->createTemplate(245, 1, 'phpunit template', null, null);
	//$layout->delete();
    #$layout->edit('Edited phpunit layout', 'Edit', null, 0, $layout->backgroundColor, $layout->backgroundImageId, $layout->backgroundzIndex, $layout->resolutionId);
	#$layoutCopy = $layout->copy('phpunit layout copy', 'copied layout', 0);
    #$layoutDraft = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->checkout($layout->layoutId);
	#$region = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($layoutDraft->layoutId, 400, 200, 20, 10);
    #$region2 = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($layout->layoutId, 400, 200, 20, 10);
    #$region3 = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->create($layout->layoutId, 400, 200, 20, 10);
    #$layout->positionAll([$region->regionId, $region2->regionId, $region3->regionId], [600, 200, 200], [500, 250, 800], [100, 230, 230], [500, 200, 500]);
    #$position = (new \Xibo\OAuth2\Client\Entity\XiboRegion($entityProvider))->positionAll($layout->layoutId, [$region->regionId, $region2->regionId, $region3->regionId], [600, 200, 200], [500, 250, 800], [100, 230, 230], [500, 200, 500]);
    #$playlist = (new \Xibo\OAuth2\Client\Entity\XiboPlaylist($entityProvider))->assign([409], 10, $region->playlists[0]['playlistId']);
    #$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->get(['layoutId'=>99, 'embed'=>'']);
    #$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->getById(99, 'regions,playlists,widgets');
    #print_r($layout->regions[0]->regionPlaylist->widgets[0]->widgetId);
    #$layout = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->create('phpunit layout', 'phpunit layout', '', 1);
    #$layout->edit('boop', 'booooop', '', 0, '', null, 1, 1);
    #$layoutDraft = (new \Xibo\OAuth2\Client\Entity\XiboLayout($entityProvider))->checkout($layout->layoutId);
    #$playlist = (new \Xibo\OAuth2\Client\Entity\XiboPlaylist($entityProvider))->assign([166], 10, $layoutDraft->regions[0]->regionPlaylist->playlistId);
    #$widgetList = (new \Xibo\OAuth2\Client\Entity\XiboWidget($entityProvider))->get(['playlistId' => 136]);
    #$playlist = (new \Xibo\OAuth2\Client\Entity\XiboPlaylist($entityProvider))->get(['playlistId' => 136, 'embed' => 'widgets']);
    #$playlist = (new \Xibo\OAuth2\Client\Entity\XiboPlaylist($entityProvider))->add('BoopDynamic', '', 1, '', 'moonmoon');
    #$layout->publish($layout->layoutId);
    #$layout->delete();

/*
	$media = (new \Xibo\OAuth2\Client\Entity\XiboLibrary($entityProvider))->create('API image', 'files\53.jpg');
	$media2 = (new \Xibo\OAuth2\Client\Entity\XiboLibrary($entityProvider))->create('API image Replacement', 'files\20.png', $media->mediaId, 1, 1);

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

# Users User Groups and Permissions
    #$users = (new \Xibo\OAuth2\Client\Entity\XiboUser($entityProvider))->get();
    #$user = (new \Xibo\OAuth2\Client\Entity\XiboUser($entityProvider))->getById(3);
    #$user = (new \Xibo\OAuth2\Client\Entity\XiboUser($entityProvider))->getMe();
    #$user = (new \Xibo\OAuth2\Client\Entity\XiboUser($entityProvider))->create('test UserName', 1, 29, 'password', 1, 1, 0);
    #$user = (new \Xibo\OAuth2\Client\Entity\XiboUser($entityProvider))->edit($user->userId,'test UserName Edited', 1, 29, 1, 1, 'newPassword', 'newPassword');

    #$permissions = (new \Xibo\OAuth2\Client\Entity\XiboPermissions($entityProvider))->setPermissions('campaign', 4, 5, 1,1,1);
    #$permissions = (new \Xibo\OAuth2\Client\Entity\XiboPermissions($entityProvider))->getPermissions('campaign', 4);

    #$group = (new \Xibo\OAuth2\Client\Entity\XiboUserGroup($entityProvider))->get();
    #$group = (new \Xibo\OAuth2\Client\Entity\XiboUserGroup($entityProvider))->getById(1);
    #$group = (new \Xibo\OAuth2\Client\Entity\XiboUserGroup($entityProvider))->create('Api created UserGroup', 0);
    #$group = (new \Xibo\OAuth2\Client\Entity\XiboUserGroup($entityProvider))->edit($group->groupId, 'Api edited UserGroup');
    #$group = (new \Xibo\OAuth2\Client\Entity\XiboUserGroup($entityProvider))->assignUser($group->groupId, [$user->userId]);
    #$group = (new \Xibo\OAuth2\Client\Entity\XiboUserGroup($entityProvider))->unassignUser($group->groupId, [$user->userId]);
    #$groupCopy = (new \Xibo\OAuth2\Client\Entity\XiboUserGroup($entityProvider))->copy($group->groupId, 'User Group Copy');
    #$user->delete();
    #$group->delete();
    #$groupCopy->delete();


# Player Software
    #$playerSoftware = (new \Xibo\OAuth2\Client\Entity\XiboPlayerSoftware($entityProvider))->get();
    #$playerSoftware = (new \Xibo\OAuth2\Client\Entity\XiboPlayerSoftware($entityProvider))->getByMediaId(511);
    #$playerSoftware = (new \Xibo\OAuth2\Client\Entity\XiboPlayerSoftware($entityProvider))->getByVersionId(39);
    #$playerSoftware = (new \Xibo\OAuth2\Client\Entity\XiboPlayerSoftware($entityProvider))->edit(39, 'Edited Name', '1.8', 108);
    #$playerSoftware->delete();

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
