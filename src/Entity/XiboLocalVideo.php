<?php
/**
 * Copyright (C) 2018 Xibo Signage Ltd
 *
 * Xibo - Digital Signage - http://www.xibo.org.uk
 *
 * This file is part of Xibo.
 *
 * Xibo is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Xibo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Xibo.  If not, see <http://www.gnu.org/licenses/>.
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboLocalVideo extends XiboWidget
{
    /** @var int The Widget ID */
    public $widgetId;

    /** @var int The Playlist ID */
    public $playlistId;

    /** @var int The Owner ID */
    public $ownerId;

    /** @var string The Widget Type */
    public $type;

    /** @var int The Widget Duration */
    public $duration;

    /** @var int The Display Order of the Widget */
    public $displayOrder;

    /** @var int Flag indicating whether to use custom duration */
    public $useDuration;

    /** @var string A local file path or URL to the video. This can be RTSP stream. */
    public $uri;

    /** @var string How should the video be scaled, available options: aspect, stretch */
    public $scaleTypeId;

    /** @var int Flag Should the video be muted? */
    public $mute;

    /**
     * Create localVideo widget.
     *
     * @param string $uri A local file path or URL to the video. This can be RTSP stream
     * @param int $duration Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param string $scaleTypeId How should the video be scaled, available options: aspect, stretch
     * @param int $mute Flag Should the video be muted?
     * @param int $playlistId Playlist ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboLocalVideo
     */
    public function create($uri, $duration, $useDuration, $scaleTypeId, $mute, $playlistId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->uri = $uri;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->scaleTypeId = $scaleTypeId;
        $this->mute = $mute;
        $this->playlistId = $playlistId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Creating Local Video widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/localVideo/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }
    /**
     * Edit localVideo widget.
     *
     * @param string $uri A local file path or URL to the video. This can be RTSP stream
     * @param int $duration Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param string $scaleTypeId How should the video be scaled, available options: aspect, stretch
     * @param int $mute Flag Should the video be muted?
     * @param int $widgetId The Widget ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboLocalVideo
     */
    public function edit($uri, $duration, $useDuration, $scaleTypeId, $mute, $widgetId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->uri = $uri;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->scaleTypeId = $scaleTypeId;
        $this->mute = $mute;
        $this->widgetId = $widgetId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing Local Video widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
    * Delete the widget.
     *
    */
    public function delete()
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Deleting widget ID ' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
