<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2018 Spring Signage Ltd
 * (XiboNotificationView.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboNotificationView extends XiboWidget
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
    public $name;
    public $age;
    public $noDataMessage;
    public $effect;
    public $speed;
    public $durationIsPerItem;
    public $embedStyle;

    /**
     * Create
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $age
     * @param $noDataMessage
     * @param $effect;
     * @param $speed;
     * @param $durationIsPerItem
     * @param $embedStyle
     * @param $playlistId
     * @return XiboNotificationView
     */
    public function create($name, $duration, $useDuration, $age, $noDataMessage, $effect, $speed, $durationIsPerItem, $embedStyle = null, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->age = $age;
        $this->noDataMessage = $noDataMessage;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->durationIsPerItem = $durationIsPerItem;
        $this->embedStyle = $embedStyle;
        $this->playlistId = $playlistId;
        $this->getLogger()->info('Creating Notification widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/notificationview/' . $playlistId, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $age
     * @param $noDataMessage
     * @param $effect;
     * @param $speed;
     * @param $durationIsPerItem
     * @param $embedStyle
     * @param $widgetId
     * @return XiboNotificationView
     */
    public function edit($name, $duration, $useDuration, $age, $noDataMessage, $effect, $speed, $durationIsPerItem, $embedStyle = null, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->age = $age;
        $this->noDataMessage = $noDataMessage;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->durationIsPerItem = $durationIsPerItem;
        $this->embedStyle = $embedStyle;
        $this->widgetId = $widgetId;
        $this->getLogger()->info('Editing widget ID ' . $widgetId);
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
