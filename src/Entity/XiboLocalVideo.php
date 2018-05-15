<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboLocalVideo.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboLocalVideo extends XiboWidget
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
    public $uri;
    public $scaleTypeId;
    public $mute;

    /**
     * Create
     * @param $uri
     * @param $duration
     * @param $useDuration
     * @param $scaleTypeId
     * @param $mute
     * @param $playlistId
     * @return XiboLocalVideo
     */
    public function create($uri, $duration, $useDuration, $scaleTypeId, $mute, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->uri = $uri;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->scaleTypeId = $scaleTypeId;
        $this->mute = $mute;
        $this->playlistId = $playlistId;
        $this->getLogger()->info('Creating Local Video widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/localVideo/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }
    /**
     * Edit
     * @param $uri
     * @param $duration
     * @param $useDuration
     * @param $scaleTypeId
     * @param $mute
     * @param $widgetId
     * @return XiboLocalVideo
     */
    public function edit($uri, $duration, $useDuration, $scaleTypeId, $mute, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->uri = $uri;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->scaleTypeId = $scaleTypeId;
        $this->mute = $mute;
        $this->widgetId = $widgetId;
        $this->getLogger()->info('Editing Local Video widget ID ' . $widgetId);
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
