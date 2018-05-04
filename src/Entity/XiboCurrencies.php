<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboCurrencies.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboCurrencies extends XiboWidget
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
    public $base;
    public $items;
    public $effect;
    public $speed;
    public $backgroundColor;
    public $noRecordsMessage;
    public $dateFormat;
    public $reverseConversion;
    public $updateInterval;
    public $templateId;
    public $durationIsPerPage;
    public $javaScript;
    public $overrideTemplate;
    public $mainTemplate;
    public $itemTemplate;
    public $styleSheet;
    public $widgetOriginalWidth;
    public $widgetOriginalHeight;
    public $maxItemsPerPage;

    /**
     * Create
     * @param $templateId
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $base
     * @param $items
     * @param $reverseConversion
     * @param $effect
     * @param $speed
     * @param $backgroundColor
     * @param $noRecordsMessage
     * @param $dateFormat
     * @param $updateInterval
     * @param $durationIsPerPage
     * @param $playlistId
     * @return XiboCurrencies
     */
    public function create($templateId, $name, $duration, $useDuration, $base, $items, $reverseConversion, $effect, $speed, $backgroundColor, $noRecordsMessage, $dateFormat, $updateInterval, $durationIsPerPage, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->overrideTemplate = 0;
        $this->templateId = $templateId;
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->base = $base;
        $this->items = $items;
        $this->reverseConversion = $reverseConversion;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->noRecordsMessage = $noRecordsMessage;
        $this->dateFormat = $dateFormat;
        $this->updateInterval = $updateInterval;
        $this->durationIsPerPage = $durationIsPerPage;
        $this->playlistId = $playlistId;
        $this->getLogger()->info('Creating Currencies widget and assigning it to playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/currencies/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $templateId
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $base
     * @param $items
     * @param $reverseConversion
     * @param $effect
     * @param $speed
     * @param $backgroundColor
     * @param $noRecordsMessage
     * @param $dateFormat
     * @param $updateInterval
     * @param $durationIsPerPage
     * @param $widgetId
     * @return XiboCurrencies
     */
    public function edit($templateId, $name, $duration, $useDuration, $base, $items, $reverseConversion, $effect, $speed, $backgroundColor, $noRecordsMessage, $dateFormat, $updateInterval, $durationIsPerPage, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->overrideTemplate = 0;
        $this->templateId = $templateId;
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->base = $base;
        $this->items = $items;
        $this->reverseConversion = $reverseConversion;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->noRecordsMessage = $noRecordsMessage;
        $this->dateFormat = $dateFormat;
        $this->updateInterval = $updateInterval;
        $this->durationIsPerPage = $durationIsPerPage;
        $this->widgetId = $widgetId;
        $this->getLogger()->info('Editing widget ID' . $widgetId);
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
