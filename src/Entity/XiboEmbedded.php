<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboEmbedded.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboEmbedded extends XiboEntity
{
	/**
     * Create
     * @param $name
     * @param $duration
     * @param $transparency
     * @param $scaleContent
     * @param $embedHtml
     * @param $embedScript
     * @param $embedStyle
     */
    public function create($name, $duration, $transparency, $scaleContent, $embedHtml, $embedScript, $embedStyle, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->transparency = $transparency;
        $this->scaleContent = $scaleContent;
        $this->embedHtml = $embedHtml;
        $this->embedScript = $embedScript;
        $this->embedStyle = $embedStyle;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/embedded/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }
}
