<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboClock.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboClock extends XiboEntity
{
	/**
     * Create
     * @param $name
     * @param $duration
     * @param $theme
     * @param $clockTypeId
     * @param $offset
     * @param $format
     * @param $showSeconds
     * @param $clockFace
     */
    public function create($name, $duration, $theme, $clockTypeId, $offset, $format, $showSeconds, $clockFace, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->theme = $theme;
        $this->clockTypeId = $clockTypeId;
        $this->offset = $offset;
        $this->format = $format;
        $this->showSeconds = $showSeconds;
        $this->clockFace = $clockFace;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/clock/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }
}

