<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboFinance.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboFinance extends XiboWidget
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
    public $item;
    public $effect;
    public $speed;
    public $backgroundColor;
    public $noRecordsMessage;
    public $dateFormat;
    public $updateInterval;
    public $templateId;
    public $durationIsPerItem;
    public $javaScript;
    public $overrideTemplate;
    public $template;
    public $styleSheet;
    public $yql;
    public $resultIdentifier;

    /**
     * Create
     * @param $templateId
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $item
     * @param $effect
     * @param $speed
     * @param $backgroundColor
     * @param $noRecordsMessage
     * @param $dateFormat
     * @param $updateInterval
     * @param $durationIsPerItem
     * @param $playlistId
     * @return XiboFinance
     */
    public function create($templateId, $name, $duration, $useDuration, $item, $effect, $speed, $backgroundColor, $noRecordsMessage, $dateFormat, $updateInterval, $durationIsPerItem, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->overrideTemplate = 0;
        $this->templateId = $templateId;
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->item = $item;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->noRecordsMessage = $noRecordsMessage;
        $this->dateFormat = $dateFormat;
        $this->updateInterval = $updateInterval;
        $this->durationIsPerItem = $durationIsPerItem;
        $this->playlistId = $playlistId;
        $this->getLogger()->info('Creating Finance widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/finance/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $templateId
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $item
     * @param $effect
     * @param $speed
     * @param $backgroundColor
     * @param $noRecordsMessage
     * @param $dateFormat
     * @param $updateInterval
     * @param $durationIsPerItem
     * @param $playlistId
     * @return XiboFinance
     */
    public function edit($templateId, $name, $duration, $useDuration, $item, $effect, $speed, $backgroundColor, $noRecordsMessage, $dateFormat, $updateInterval, $durationIsPerItem, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->overrideTemplate = 0;
        $this->templateId = $templateId;
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->item = $item;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->noRecordsMessage = $noRecordsMessage;
        $this->dateFormat = $dateFormat;
        $this->updateInterval = $updateInterval;
        $this->durationIsPerItem = $durationIsPerItem;
        $this->widgetId = $widgetId;
        $this->getLogger()->info('Editing Finance widget ID ' . $widgetId);
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
