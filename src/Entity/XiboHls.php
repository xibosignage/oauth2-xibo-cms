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

class XiboHls extends XiboWidget
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

    /** @var string Optional Widget name */
    public $name;

    /** @var string URL to HLS video stream */
    public $uri;

    /** @var int Flag Should the video be muted? */
    public $mute;

    /** @var int Flag This causes some android devices to switch to a hardware accelerated web view */
    public $transparency;

    /**
     * Create The HLS widget.
     *
     * @param string $name Optional widget name
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $duration Widget Duration
     * @param string $uri URL to HLS video stream
     * @param int $mute Flag Should the video be muted?
     * @param int $transparency Flag This causes some android devices to switch to a hardware accelerated web view
     * @param int $playlistId The playlist ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboHls
     */
    public function create($name, $useDuration, $duration, $uri, $mute, $transparency, $playlistId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->useDuration = $useDuration;
        $this->duration = $duration;
        $this->uri = $uri;
        $this->mute = $mute;
        $this->transparency = $transparency;
        $this->playlistId = $playlistId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Creating HLS widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/hls/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit the HLS widget.
     *
     * @param string $name Optional widget name
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $duration Widget Duration
     * @param string $uri URL to HLS video stream
     * @param int $mute Flag Should the video be muted?
     * @param int $transparency Flag This causes some android devices to switch to a hardware accelerated web view
     * @param int $widgetId the Widget ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboHls
     */
    public function edit($name, $useDuration, $duration, $uri, $mute, $transparency, $widgetId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->useDuration = $useDuration;
        $this->duration = $duration;
        $this->uri = $uri;
        $this->mute = $mute;
        $this->transparency = $transparency;
        $this->widgetId = $widgetId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing HLS widget ID ' . $widgetId);
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
