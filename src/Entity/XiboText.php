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

class XiboText extends XiboWidget
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

    /** @var string Optional widget Name */
    public $name;

    /** @var string Effect that will be used to transitions between items */
    public $effect;

    /** @var int The transition speed of the selected effect in milliseconds */
    public $speed;

    /** @var string A HEX color to use as the background color of this widget */
    public $backgroundColor;

    /** @var string The selector to use for stacking marquee items in a line when scrolling left/right */
    public $marqueeInlineSelector;

    /** @var string The text to display */
    public $text;

    /** @var string Optional JavaScript */
    public $javaScript;

    /**
     * Create new Text Widget.
     *
     * @param string $name Optional widget name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param string $effect Effect that will be used to transitions between items, available options: fade, fadeout, scrollVert, scollHorz, flipVert, flipHorz, shuffle, tileSlide, tileBlind, marqueeUp, marqueeDown, marqueeRight, marqueeLeft
     * @param int $speed The transition speed of the selected effect in milliseconds (1000 = normal) or the Marquee speed in a low to high scale (normal = 1)
     * @param string $backgroundColor A HEX color to use as the background color of this widget
     * @param string $marqueeInlineSelector The selector to use for stacking marquee items in a line when scrolling left/right
     * @param string $text The Text to display in the widget
     * @param string $javaScript Optional JavaScript
     * @param int $playlistId Playlist ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboText
     */
    public function create($name, $duration, $useDuration, $effect, $speed, $backgroundColor, $marqueeInlineSelector, $text, $javaScript, $playlistId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->marqueeInlineSelector = $marqueeInlineSelector;
        $this->text = $text;
        $this->javaScript = $javaScript; 
        $this->playlistId = $playlistId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Creating a new Text widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/text/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit existing Text Widget.
     *
     * @param string $name Optional widget name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param string $effect Effect that will be used to transitions between items, available options: fade, fadeout, scrollVert, scollHorz, flipVert, flipHorz, shuffle, tileSlide, tileBlind, marqueeUp, marqueeDown, marqueeRight, marqueeLeft
     * @param int $speed The transition speed of the selected effect in milliseconds (1000 = normal) or the Marquee speed in a low to high scale (normal = 1)
     * @param string $backgroundColor A HEX color to use as the background color of this widget
     * @param string $marqueeInlineSelector The selector to use for stacking marquee items in a line when scrolling left/right
     * @param string $text The Text to display in the widget
     * @param string $javaScript Optional JavaScript
     * @param int $widgetId the Widget ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboText
     */
    public function edit($name, $duration, $useDuration, $effect, $speed, $backgroundColor, $marqueeInlineSelector, $text, $javaScript, $widgetId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->marqueeInlineSelector = $marqueeInlineSelector;
        $this->text = $text;
        $this->javaScript = $javaScript; 
        $this->widgetId = $widgetId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

        return $this->hydrate($response);
    }
    
    /**
     * Delete the widget.
     *
     * @return bool
     */
    public function delete()
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Deleting widget ID ' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
