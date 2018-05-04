<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboAudio.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboAudio extends XiboWidget
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
    public $mute;
    public $loop;

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $mute
     * @param $loop
     * @param $widgetId
     * @return XiboAudio
     */
    public function edit($name, $useDuration, $duration, $mute, $loop, $widgetId)
    {

        $this->name = $name;
        $this->useDuration = $useDuration;
        $this->duration = $duration;
        $this->mute = $mute;
        $this->loop = $loop;
        $this->widgetId = $widgetId;
        $this->getLogger()->info('Editing widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
     * @param $mediaId
     * @param $volume
     * @param $loop
     * @param $widgetId
     * @return XiboWidget
     */
    public function assignToWidget($mediaId, $volume, $loop, $widgetId)
    {
        $this->mediaId = $mediaId;
        $this->volume = $volume;
        $this->loop = $loop;
        $this->widgetId = $widgetId;
        $this->getLogger()->info('Assigning audio file ID ' . $mediaId . ' To widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId . '/audio', $this->toArray());

        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
    * Delete
    */
    public function delete()
    {
        $this->getLogger()->info('Deleting widget ID ' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
