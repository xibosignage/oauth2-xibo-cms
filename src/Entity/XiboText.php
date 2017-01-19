<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboText.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboText extends XiboEntity
{
    /**
     * Create
     * @param $name
     * @param $duration
     * @param $effect
     * @param $speed
     * @param $backgroundColor
     * @param $marqueeInlineSelector
     * @param $text
     * @param $javaScript
     */
    public function create($name, $duration, $effect, $speed, $backgroundColor, $marqueeInlineSelector, $text, $javaScript, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->marqueeInlineSelector = $marqueeInlineSelector;
        $this->text = $text;
        $this->javaScript = $javaScript; 
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/text/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }
}

