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

class XiboCurrencies extends XiboWidget
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

    /** @var string The Base currency */
    public $base;

    /** @var string Items wanted */
    public $items;

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

    /** @var int Flag Select 1 if you’d like your base currency to be used as the comparison currency you’ve entered */
    public $reverseConversion;

    /** @var int Update interval in minutes, should be kept as high as possible, if data change once per hour, this should be set to 60 */
    public $updateInterval;

    /** @var string Use pre-configured templates, available options: currencies1, currencies2 */
    public $templateId;

    /** @var int A flag (0, 1), The duration specified is per page/item, otherwise the widget duration is divided between the number of pages/items */
    public $durationIsPerPage;

    /** @var string flag (0, 1) set to 0 and use templateId or set to 1 and provide whole template in the next parameters */
    public $overrideTemplate;

    /** @var string Main template*/
    public $mainTemplate;

    /** @var string Template for each item, replaces [itemsTemplate] in main template */
    public $itemTemplate;

    /** @var string Optional StyleSheet  */
    public $styleSheet;

    /** @var string Optional JavaScript */
    public $javaScript;

    /** @var int This is the intended Width of the template and is used to scale the Widget within it’s region when the template is applied*/
    public $widgetOriginalWidth;

    /** @var int This is the intended Height of the template and is used to scale the Widget within it’s region when the template is applied */
    public $widgetOriginalHeight;

    /** @var int This is the intended number of items on each page */
    public $maxItemsPerPage;

    /**
     * Create Currencies Widget.
     *
     * @param string $templateId The template ID, currencies1, currencies2
     * @param string $name Optional widget name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag  Select 1 only if you will provide duration parameter as well
     * @param string $base The base currency
     * @param string $items The comma separated string of wanted items
     * @param int $reverseConversion Flag Select 1 if you’d like your base currency to be used as the comparison currency you’ve entered
     * @param string $effect Effect that will be used to transitions between items, available options: fade, fadeout, scrollVert, scollHorz, flipVert, flipHorz, shuffle, tileSlide, tileBlind
     * @param int $speed The transition speed of the selected effect in milliseconds (1000 = normal)
     * @param string $backgroundColor A HEX color to use as the background color of this widget
     * @param string $noRecordsMessage A message to display when there are no records returned by the search query
     * @param string $dateFormat The format to apply to all dates returned by he widget
     * @param int $updateInterval Update interval in minutes, should be kept as high as possible, if data change once per hour, this should be set to 60
     * @param int $durationIsPerPage Flag The duration specified is per page/item, otherwise the widget duration is divided between the number of pages/items
     * @param int $playlistId The Playlist ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboCurrencies
     */
    public function create($templateId, $name, $duration, $useDuration, $base, $items, $reverseConversion, $effect, $speed, $backgroundColor, $noRecordsMessage, $dateFormat, $updateInterval, $durationIsPerPage, $playlistId, $enableStat = 'Off')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->overrideTemplate = 0;
        $this->templateId = $templateId;
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->base = $base;
        $this->items = $items;
        $this->reverseConversion = $reverseConversion;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->noRecordsMessage = $noRecordsMessage;
        $this->dateFormat = $dateFormat;
        $this->updateInterval = $updateInterval;
        $this->durationIsPerPage = $durationIsPerPage;
        $this->playlistId = $playlistId;
        $this->enableStat = $enableStat;

        $this->getLogger()->info('Creating Currencies widget and assigning it to playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/currencies/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit the Currencies Widget.
     *
     * @param string $templateId The template ID, currencies1, currencies2
     * @param string $name Optional widget name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag  Select 1 only if you will provide duration parameter as well
     * @param string $base The base currency
     * @param string $items The comma separated string of wanted items
     * @param int $reverseConversion Flag Select 1 if you’d like your base currency to be used as the comparison currency you’ve entered
     * @param string $effect Effect that will be used to transitions between items, available options: fade, fadeout, scrollVert, scollHorz, flipVert, flipHorz, shuffle, tileSlide, tileBlind
     * @param int $speed The transition speed of the selected effect in milliseconds (1000 = normal)
     * @param string $backgroundColor A HEX color to use as the background color of this widget
     * @param string $noRecordsMessage A message to display when there are no records returned by the search query
     * @param string $dateFormat The format to apply to all dates returned by he widget
     * @param int $updateInterval Update interval in minutes, should be kept as high as possible, if data change once per hour, this should be set to 60
     * @param int $durationIsPerPage Flag The duration specified is per page/item, otherwise the widget duration is divided between the number of pages/items
     * @param int $widgetId The Widget ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboCurrencies
     */
    public function edit($templateId, $name, $duration, $useDuration, $base, $items, $reverseConversion, $effect, $speed, $backgroundColor, $noRecordsMessage, $dateFormat, $updateInterval, $durationIsPerPage, $widgetId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->overrideTemplate = 0;
        $this->templateId = $templateId;
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->base = $base;
        $this->items = $items;
        $this->reverseConversion = $reverseConversion;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->noRecordsMessage = $noRecordsMessage;
        $this->dateFormat = $dateFormat;
        $this->updateInterval = $updateInterval;
        $this->durationIsPerPage = $durationIsPerPage;
        $this->widgetId = $widgetId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing widget ID' . $widgetId);
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
