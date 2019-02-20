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

class XiboWebpage extends XiboWidget
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

    /** @var int Flag should the HTML be shown with a transparent background? */
    public $transparency;

    /** @var string String containing the location (URL) of the web page */
    public $uri;

    /** @var int For Manual position the percentage to scale the Web page (0-100) */
    public $scaling;

    /** @var int For Manual position, the starting point from the left in pixels */
    public $offsetLeft;

    /** @var int For Manual position, the starting point from the Top in pixels */
    public $offsetTop;

    /** @var int For Manual Position and Best Fit, The width of the page - if empty it will use region width */
    public $pageWidth;

    /** @var int For Manual Position and Best Fit, The height of the page - if empty it will use region height */
    public $pageHeight;

    /** @var int The mode option for Web page, 1- Open Natively, 2- Manual Position, 3- Best Ft */
    public $modeId;

    /**
     * Create new Webpage Widget.
     *
     * @param string $name Optional Widget
     * @param int $duration The Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $transparency Flag should the HTML be shown with a transparent background?
     * @param string $uri String containing the location (URL) of the web page
     * @param int $scaling For Manual position the percentage to scale the Web page (0-100)
     * @param int $offsetLeft For Manual position, the starting point from the left in pixels
     * @param int $offsetTop For Manual position, the starting point from the Top in pixels
     * @param int $pageWidth For Manual Position and Best Fit, The width of the page - if empty it will use region width
     * @param int $pageHeight For Manual Position and Best Fit, The height of the page - if empty it will use region height
     * @param int $modeId The mode option for Web page, 1- Open Natively, 2- Manual Position, 3- Best Ft
     * @param int $playlistId The Playlist ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboWebpage
     */
    public function create($name, $duration, $useDuration, $transparency, $uri, $scaling, $offsetLeft, $offsetTop, $pageWidth, $pageHeight, $modeId, $playlistId, $enableStat ='')
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
     * Edit existing Webpage Widget.
     *
     * @param string $name Optional Widget
     * @param int $duration The Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $transparency Flag should the HTML be shown with a transparent background?
     * @param string $uri String containing the location (URL) of the web page
     * @param int $scaling For Manual position the percentage to scale the Web page (0-100)
     * @param int $offsetLeft For Manual position, the starting point from the left in pixels
     * @param int $offsetTop For Manual position, the starting point from the Top in pixels
     * @param int $pageWidth For Manual Position and Best Fit, The width of the page - if empty it will use region width
     * @param int $pageHeight For Manual Position and Best Fit, The height of the page - if empty it will use region height
     * @param int $modeId The mode option for Web page, 1- Open Natively, 2- Manual Position, 3- Best Ft
     * @param int $widgetId The Widget ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboWebpage
     */
    public function edit($name, $duration, $useDuration, $transparency, $uri, $scaling, $offsetLeft, $offsetTop, $pageWidth, $pageHeight, $modeId, $widgetId, $enableStat = '')
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
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
    * Delete the widget.
     *
     * @return boolean
    */
    public function delete()
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Deleting widget ID ' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}

