<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboWebpage.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboWebpage extends XiboWidget
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
    public $transparency;
    public $uri;
    public $scaling;
    public $offsetLeft;
    public $offsetTop;
    public $pageWidth;
    public $pageHeight;
    public $modeId;

    /**
     * Create
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $transparency
     * @param $uri
     * @param $scaling
     * @param $offsetLeft
     * @param $offsetTop
     * @param $pageWidth
     * @param $pageHeight
     * @param $modeId
     * @param $playlistId
     * @return XiboWebpage
     */
    public function create($name, $duration, $useDuration, $transparency, $uri, $scaling, $offsetLeft, $offsetTop, $pageWidth, $pageHeight, $modeId, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->transparency = $transparency;
        $this->uri = $uri;
        $this->scaling = $scaling;
        $this->offsetLeft = $offsetLeft;
        $this->offsetTop = $offsetTop;
        $this->pageWidth = $pageWidth;
        $this->pageHeight = $pageHeight;
        $this->modeId = $modeId;
        $this->playlistId = $playlistId;
        $this->getLogger()->info('Creating a new Webpage widget in playlist ID ' .$playlistId);
        $response = $this->doPost('/playlist/widget/webpage/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $transparency
     * @param $uri
     * @param $scaling
     * @param $offsetLeft
     * @param $offsetTop
     * @param $pageWidth
     * @param $pageHeight
     * @param $modeId
     * @param $widgetId
     * @return XiboWebpage
     */
    public function edit($name, $duration, $useDuration, $transparency, $uri, $scaling, $offsetLeft, $offsetTop, $pageWidth, $pageHeight, $modeId, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->transparency = $transparency;
        $this->uri = $uri;
        $this->scaling = $scaling;
        $this->offsetLeft = $offsetLeft;
        $this->offsetTop = $offsetTop;
        $this->pageWidth = $pageWidth;
        $this->pageHeight = $pageHeight;
        $this->modeId = $modeId;
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

