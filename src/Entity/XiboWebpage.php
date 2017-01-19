<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboWebpage.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboWebpage extends XiboEntity
{
	/**
     * Create
     * @param $name
     * @param $duration
     * @param $transparency
     * @param $uri
     * @param $scaling
     * @param $offsetLeft
     * @param $offsetTop
     * @param $pageWidth
     * @param $pageHeight
     * @param $modeId
     */
    public function create($name, $duration, $transparency, $uri, $scaling, $offsetLeft, $offsetTop, $pageWidth, $pageHeight, $modeId, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->transparency = $transparency;
        $this->uri = $uri;
        $this->scaling = $scaling;
        $this->offsetLeft = $offsetLeft;
        $this->offsetTop = $offsetTop;
        $this->pageWidth = $pageWidth;
        $this->pageHeight = $pageHeight;
        $this->modeId = $modeId;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/webpage/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }
}

