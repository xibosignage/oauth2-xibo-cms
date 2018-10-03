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

class XiboFinance extends XiboWidget
{
    /** @var int the Widget ID */
    public $widgetId;

    /** @var int the Playlist ID */
    public $playlistId;

    /** @var int the Owner ID */
    public $ownerId;

    /** @var string The widget Type */
    public $type;

    /** @var int the Widget duration */
    public $duration;

    /** @var int the Widget display order */
    public $displayOrder;

    /** @var int Flag indicating whether to use custom duration  */
    public $useDuration;

    /** @var string Widget name */
    public $name;

    /** @var string Items wanted, can be comma separated (example EURUSD, GBPUSD) */
    public $item;

    /** @var string Effect that will be used to transitions between items, available options: fade, fadeout, scrollVert, scollHorz, flipVert, flipHorz, shuffle, tileSlide, tileBlind*/
    public $effect;

    /** @var int The transition speed of the selected effect in milliseconds (1000 = normal) */
    public $speed;

    /** @var string A HEX color to use as the background color of this widget */
    public $backgroundColor;

    /** @var string A message to display when there are no records returned by the search query */
    public $noRecordsMessage;

    /** @var string The format to apply to all dates returned by he widget */
    public $dateFormat;

    /** @var int Update interval in minutes, should be kept as high as possible, if data change once per hour, this should be set to 60 */
    public $updateInterval;

    /** @var string Use pre-configured templates, available options: currency-simple, stock-simple */
    public $templateId;

    /** @var int Flag The duration specified is per item, otherwise the widget duration is divided between the number of items */
    public $durationIsPerItem;

    /** @var int Flag  set to 0 and use templateId or set to 1 and provide whole template in the next parameters */
    public $overrideTemplate;

    /** @var string Optional JavaScript */
    public $javaScript;

    /** @var string Main template */
    public $template;

    /** @var string Custom Style Sheets (CSS) */
    public $styleSheet;

    /** @var string YQL query to use for data */
    public $yql;

    /** @var string name of the result identifier returned by the YQL */
    public $resultIdentifier;

    /**
     * Create Finance Widget.
     *
     * @param string $templateId Use pre-configured templates, available options: currency-simple, stock-simple
     * @param string $name Widget Name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag Select 1 only if you will provide duration parameter as well
     * @param string $item Items wanted, can be comma separated (example EURUSD, GBPUSD)
     * @param string $effect Effect that will be used to transitions between items, available options: fade, fadeout, scrollVert, scollHorz, flipVert, flipHorz, shuffle, tileSlide, tileBlind, marqueeUp, marqueeDown, marqueeRight, marqueeLeft
     * @param int $speed The transition speed of the selected effect in milliseconds (1000 = normal) or the Marquee speed in a low to high scale (normal = 1)
     * @param string $backgroundColor A  HEX color to use as the background color of this widget
     * @param string $noRecordsMessage A message to display when there are no records returned by the search query
     * @param string $dateFormat The format to apply to all dates returned by he widget
     * @param int $updateInterval Update interval in minutes, should be kept as high as possible, if data change once per hour, this should be set to 60
     * @param int $durationIsPerItem Flag The duration specified is per item, otherwise the widget duration is divided between the number of items
     * @param int $overrideTemplate flag (0, 1) set to 0 and use templateId or set to 1 and provide whole template in the next parameters
     * @param string $yql The YQL query to use for data, pass only with overrideTemplate set to 1
     * @param string $resultIdentifier The name of the result identifier returned by the YQL, pass only with overrideTemplate set to 1
     * @param int $playlistId Playlist ID
     * @return XiboFinance
     */
    public function create($templateId, $name, $duration, $useDuration, $item, $effect, $speed, $backgroundColor, $noRecordsMessage, $dateFormat, $updateInterval, $durationIsPerItem, $overrideTemplate, $yql, $resultIdentifier, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->overrideTemplate = 0;
        $this->templateId = $templateId;
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->item = $item;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->noRecordsMessage = $noRecordsMessage;
        $this->dateFormat = $dateFormat;
        $this->updateInterval = $updateInterval;
        $this->durationIsPerItem = $durationIsPerItem;
        $this->overrideTemplate = $overrideTemplate;
        $this->yql = $yql;
        $this->resultIdentifier = $resultIdentifier;
        $this->playlistId = $playlistId;
        $this->getLogger()->info('Creating Finance widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/finance/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit the Finance widget.
     *
     * @param string $templateId Use pre-configured templates, available options: currency-simple, stock-simple
     * @param string $name Widget Name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag Select 1 only if you will provide duration parameter as well
     * @param string $item Items wanted, can be comma separated (example EURUSD, GBPUSD)
     * @param string $effect Effect that will be used to transitions between items, available options: fade, fadeout, scrollVert, scollHorz, flipVert, flipHorz, shuffle, tileSlide, tileBlind, marqueeUp, marqueeDown, marqueeRight, marqueeLeft
     * @param int $speed The transition speed of the selected effect in milliseconds (1000 = normal) or the Marquee speed in a low to high scale (normal = 1)
     * @param string $backgroundColor A  HEX color to use as the background color of this widget
     * @param string $noRecordsMessage A message to display when there are no records returned by the search query
     * @param string $dateFormat The format to apply to all dates returned by he widget
     * @param int $updateInterval Update interval in minutes, should be kept as high as possible, if data change once per hour, this should be set to 60
     * @param int $durationIsPerItem Flag The duration specified is per item, otherwise the widget duration is divided between the number of items
     * @param int $overrideTemplate flag (0, 1) set to 0 and use templateId or set to 1 and provide whole template in the next parameters
     * @param string $yql The YQL query to use for data, pass only with overrideTemplate set to 1
     * @param string $resultIdentifier The name of the result identifier returned by the YQL, pass only with overrideTemplate set to 1
     * @param int $widgetId The Widget ID to edit
     * @return XiboFinance
     */
    public function edit($templateId, $name, $duration, $useDuration, $item, $effect, $speed, $backgroundColor, $noRecordsMessage, $dateFormat, $updateInterval, $durationIsPerItem, $overrideTemplate, $yql, $resultIdentifier, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->overrideTemplate = 0;
        $this->templateId = $templateId;
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->item = $item;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->noRecordsMessage = $noRecordsMessage;
        $this->dateFormat = $dateFormat;
        $this->updateInterval = $updateInterval;
        $this->durationIsPerItem = $durationIsPerItem;
        $this->overrideTemplate = $overrideTemplate;
        $this->yql = $yql;
        $this->resultIdentifier = $resultIdentifier;
        $this->widgetId = $widgetId;
        $this->getLogger()->info('Editing Finance widget ID ' . $widgetId);
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
