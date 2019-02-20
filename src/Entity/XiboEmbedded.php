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

class XiboEmbedded extends XiboWidget
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

    /** @var string Optional widget name */
    public $name;

    /** @var int Flag should the HTML be shown with transparent background? - Not available on Windows players */
    public $transparency;

    /** @var int Flag should the embedded content be scaled along with the layout */
    public $scaleContent;

    /** @var string The HTML to embed */
    public $embedHtml;

    /** @var string HEAD content to embed, including script tags */
    public $embedScript;

    /** @var string Custom Style Sheets (CSS) */
    public $embedStyle;

    /**
     * Create a new Embedded HTML widget.
     *
     * @param string $name Widget Name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $transparency Flag should the HTML be shown with transparent background? - Not available on Windows players
     * @param int $scaleContent Flag should the embedded content be scaled along with the layout
     * @param string $embedHtml The HTML to embed
     * @param string $embedScript HEAD content to embed, including script tags
     * @param string $embedStyle Custom Style Sheets (CSS)
     * @param int $playlistId The playlist ID to which this widget should be assigned
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboEmbedded
     */
    public function create($name, $duration, $useDuration, $transparency, $scaleContent, $embedHtml, $embedScript, $embedStyle, $playlistId, $enableStat = '')
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
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Creating Embed HTML widget' . $name . ' in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/embedded/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit existing HTML widget.
     *
     * @param string $name Widget Name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $transparency Flag should the HTML be shown with transparent background? - Not available on Windows players
     * @param int $scaleContent Flag should the embedded content be scaled along with the layout
     * @param string $embedHtml The HTML to embed
     * @param string $embedScript HEAD content to embed, including script tags
     * @param string $embedStyle Custom Style Sheets (CSS)
     * @param int $widgetId The Widget ID to edit
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboEmbedded
     */
    public function edit($name, $duration, $useDuration, $transparency, $scaleContent, $embedHtml, $embedScript, $embedStyle, $widgetId, $enableStat = '')
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
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing Embed HTML widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Delete the widget.
     */
    public function delete()
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Deleting widget ID ' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
