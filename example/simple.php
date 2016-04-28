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
    'clientId' => 'p8kh8tq2mknOqMFx7qcgl7FGtFGDlDAlDOxb6TP1',    // The client ID assigned to you by the provider
    'clientSecret' => 'KjHPCQHm0ztqA4bcqP1dszYpLpcZqyAvaFlGbFZsq6HUn15ND8d8bZZhpFiPHWqMOQx5sXsAPgdtahICgtdhgFxxOAtlv59kl1GZZLe6dRNvOYQLQyXP9NtxfQkHgHj2wmJwhhhBwqvyPnp9pn13eevMCbDnqfyZJzMkUoG3fofxQPq6Kl9Mh5DtFtiEgXs2XE7zhKfGPOLWH1pUZxn3FLixOehSRUyuUB7SLDqnulPxlFMbV6L4EN4pAG5cRN',   // The client password assigned to you by the provider
    'redirectUri' => '',
    'baseUrl' => 'http://192.168.0.25'
]);

$entityProvider = new \Xibo\OAuth2\Client\Provider\XiboEntityProvider($provider);

$displayGroup = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->getById(20);

echo 'Display Group ID ' . $displayGroup->displayGroupId;

// Try creating a new one
$new = (new \Xibo\OAuth2\Client\Entity\XiboDisplayGroup($entityProvider))->getById(44)->delete();

var_export($new);