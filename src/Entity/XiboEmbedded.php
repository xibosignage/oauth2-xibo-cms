<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboEmbedded.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboEmbedded extends XiboWidget
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
    public $scaleContent;
    public $embedHtml;
    public $embedScript;
    public $embedStyle;

    /**
     * Create
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $transparency
     * @param $scaleContent
     * @param $embedHtml
     * @param $embedScript
     * @param $embedStyle
     * @param $playlistId
     * @return XiboEmbedded
     */
    public function create($name, $duration, $useDuration, $transparency, $scaleContent, $embedHtml, $embedScript, $embedStyle, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->transparency = $transparency;
        $this->scaleContent = $scaleContent;
        $this->embedHtml = $embedHtml;
        $this->embedScript = $embedScript;
        $this->embedStyle = $embedStyle;
        $this->playlistId = $playlistId;
        $this->getLogger()->info('Creating Embed HTML widget' . $name . ' in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/embedded/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $transparency
     * @param $scaleContent
     * @param $embedHtml
     * @param $embedScript
     * @param $embedStyle
     * @param $widgetId
     * @return XiboEmbedded
     */
    public function edit($name, $duration, $useDuration, $transparency, $scaleContent, $embedHtml, $embedScript, $embedStyle, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->transparency = $transparency;
        $this->scaleContent = $scaleContent;
        $this->embedHtml = $embedHtml;
        $this->embedScript = $embedScript;
        $this->embedStyle = $embedStyle;
        $this->widgetId = $widgetId;
        $this->getLogger()->info('Editing Embed HTML widget ID ' . $widgetId);
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
