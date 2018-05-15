<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboGoogleTraffic.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboGoogleTraffic extends XiboWidget
{
    public $widgetId;
    public $playlistId;
    public $ownerId;
    public $type;
    public $duration;
    public $displayOrder;
    public $useDuration;
    public $calculatedDuration;
    public $widgetOptions;
    public $mediaIds;
    public $audio;
    public $permissions;
    public $module;
    public $name;
    public $useDisplayLocation;
    public $longitude;
    public $latitude;
    public $zoom;

    /**
     * Create
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $useDisplayLocation
     * @param $longitude
     * @param $latitude
     * @param $zoom
     * @param $playlistId
     * @return XiboGoogleTraffic
     */
    public function create($name, $duration, $useDuration, $useDisplayLocation, $longitude, $latitude, $zoom, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->useDisplayLocation = $useDisplayLocation;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->zoom = $zoom;
        $this->playlistId = $playlistId;
        $this->getLogger()->info('Creating Google Traffic widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/googleTraffic/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDisplayLocation
     * @param $longitude
     * @param $latitude
     * @param $zoom
     * @param $widgetId
     * @return XiboGoogleTraffic
     */
    public function edit($name, $duration, $useDisplayLocation, $longitude, $latitude, $zoom, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDisplayLocation = $useDisplayLocation;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->zoom = $zoom;
        $this->widgetId = $widgetId;
        $this->getLogger()->info('Editing Google Traffic widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
    * Delete
    */
    public function delete()
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Deleting widget ID ' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
