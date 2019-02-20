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

class XiboTicker extends XiboWidget
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

    /** @var string optional widget Name */
    public $name;

    /** @var int The Source to use for the Ticker */
    public $sourceId;

    /** @var int The link for the rss feed */
    public $uri;

    /** @var string Effect that will be used to transitions between items */
    public $effect;

    /** @var int The transition speed of the selected effect in milliseconds */
    public $speed;

    /** @var string A HEX color to use as the background color of this widget */
    public $backgroundColor;

    /** @var string A message to display when no data is returned from the source */
    public $noDataMessage;

    /** @var string The date format to apply to all dates returned by the ticker */
    public $dateFormat;

    /** @var int  Update interval in minutes */
    public $updateInterval;

    /** @var string Template you’d like to apply */
    public $templateId;

    /** @var int Flag The duration specified is per item, otherwise it is per feed */
    public $durationIsPerItem;

    /** @var int When in single mode, how many items per page should be shown */
    public $itemsPerPage;

    /** @var int Flag override template checkbox */
    public $overrideTemplate;

    /** @var string Template for each item, replaces [itemsTemplate] in main template, Pass only with overrideTemplate set to 1 */
    public $template;

    /** @var string Copyright information to display as the last item in this feed. can be styled with the #copyright CSS selector */
    public $copyright;

    /** @var int The number of RSS items you want to display */
    public $numItems;

    /** @var string Take the items form the beginning or the end of the list, available options: start, end */
    public $takeItemsFrom;

    /** @var int Flag Should items be shown side by side */
    public $itemsSideBySide;

    /** @var int Upper low limit for this dataSet, 0 for nor limit */
    public $upperLimit;

    /** @var int  Lower low limit for this dataSet, 0 for nor limit */
    public $lowerLimit;

    /** @var string  A comma separated list of attributes that should not be stripped from the feed */
    public $allowedAttributes;

    /** @var string A comma separated list of attributes that should be stripped from the feed */
    public $stripTags;

    /** @var int Should the date sort applied to the feed be disabled? */
    public $disableDateSort;

    /** @var string Which direction does the text in the feed use? Available options: ltr, rtl */
    public $textDirection;

    /** @var int Create ticker Widget using provided dataSetId of an existing dataSet */
    public $dataSetId;

    /** @var string SQL clause for filter this dataSet */
    public $filter;

    /** @var string SQL clause for how this dataSet should be ordered */
    public $ordering;

    /** @var int Flag  Use advanced order clause */
    public $useOrderingClause;

    /** @var int Use advanced filter clause */
    public $useFilteringClause;

    /** @var string Optional StyleSheet */
    public $css;

    /** @var string Optional JaveScript */
    public $javaScript;

    /** @var int Flag whether to randomise the feed items */
    public $randomiseItems;


    /**
     * Create new Ticker widget.
     *
     * @param int $sourceId The Ticker source ID, 1 for rss feed, 2 for dataset
     * @param string $uri The Feed URI, For sourceId=1, the link for the rss feed
     * @param int $duration The Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $dataSetId The DataSet ID for sourceId=2
     * @param int $playlistId The playlist ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboTicker
     */
    public function create($sourceId, $uri, $dataSetId, $duration, $useDuration, $playlistId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->sourceId = $sourceId;
        $this->uri = $uri;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->dataSetId = $dataSetId;
        $this->playlistId = $playlistId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Creating Ticker widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/ticker/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit Ticker with RSS FEED source.
     *
     * @param string $name Optional Widget name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $updateInterval Update interval in minutes
     * @param string $effect Effect that will be used to transitions between items, available options: fade, fadeout, scrollVert, scollHorz, flipVert, flipHorz, shuffle, tileSlide, tileBlind, marqueeUp, marqueeDown, marqueeRight, marqueeLeft
     * @param int $speed The transition speed of the selected effect in milliseconds (1000 = normal) or the Marquee speed in a low to high scale (normal = 1)
     * @param string $copyright Copyright information to display as the last item in this feed. can be styled with the #copyright CSS selector
     * @param int $numItems The number of RSS items you want to display
     * @param string $takeItemsFrom Take the items form the beginning or the end of the list, available options: start, end
     * @param int $durationIsPerItem Flag The duration specified is per item, otherwise it is per feed
     * @param int $itemsSideBySide Should items be shown side by side
     * @param int $itemsPerPage When in single mode, how many items per page should be shown
     * @param string $dateFormat The date format to apply to all dates returned by the ticker
     * @param string $allowedAttributes  A comma separated list of attributes that should not be stripped from the feed
     * @param string $stripTags A comma separated list of attributes that should be stripped from the feed
     * @param string $backgroundColor A HEX color to use as the background color of this widget
     * @param int $disableDateSort Flag Should the date sort applied to the feed be disabled?
     * @param string $textDirection Which direction does the text in the feed use? Available options: ltr, rtl
     * @param string $noDataMessage A message to display when no data is returned from the source
     * @param string $templateId Template you’d like to apply, options available: title-only, prominent-title-with-desc-and-name-separator, media-rss-with-title, media-rss-wth-left-hand-text, media-rss-image-only
     * @param int $overrideTemplate override template checkbox
     * @param string $template Template for each item, replaces [itemsTemplate] in main template
     * @param string $css Optional StyleSheet CSS
     * @param string $javaScript Optional CSS
     * @param int $randomiseItems Flag whether to randomise the feed
     * @param int $widgetId The Widget ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboTicker
     */
    public function editFeed($name, $duration, $useDuration, $updateInterval, $effect, $speed, $copyright = '', $numItems, $takeItemsFrom, $durationIsPerItem, $itemsSideBySide, $itemsPerPage, $dateFormat, $allowedAttributes, $stripTags, $backgroundColor, $disableDateSort, $textDirection, $noDataMessage, $templateId, $overrideTemplate, $template, $css = '', $javaScript = '', $randomiseItems, $widgetId, $enableStat = '')
    {
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->updateInterval = $updateInterval;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->copyright = $copyright;
        $this->numItems = $numItems;
        $this->takeItemsFrom = $takeItemsFrom;
        $this->durationIsPerItem = $durationIsPerItem;
        $this->itemsSideBySide = $itemsSideBySide;
        $this->itemsPerPage = $itemsPerPage;
        $this->dateFormat = $dateFormat;
        $this->allowedAttributes = $allowedAttributes;
        $this->stripTags = $stripTags;
        $this->backgroundColor = $backgroundColor;
        $this->disableDateSort = $disableDateSort;
        $this->textDirection = $textDirection;
        $this->noDataMessage = $noDataMessage;
        $this->templateId = $templateId;
        $this->overrideTemplate = $overrideTemplate;
        $this->template = $template;
        $this->css = $css;
        $this->javaScript = $javaScript;
        $this->randomiseItems = $randomiseItems;
        $this->enableStat = $enableStat;

        $this->getLogger()->info('Editing widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

        return $this->hydrate($response);

    }

    /**
     * Edit Ticker widget with dataSet source
     *
     * @param string $name Optional Widget name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $updateInterval Update interval in minutes
     * @param string $effect Effect that will be used to transitions between items, available options: fade, fadeout, scrollVert, scollHorz, flipVert, flipHorz, shuffle, tileSlide, tileBlind, marqueeUp, marqueeDown, marqueeRight, marqueeLeft
     * @param int $speed The transition speed of the selected effect in milliseconds (1000 = normal) or the Marquee speed in a low to high scale (normal = 1)
     * @param int $durationIsPerItem Flag The duration specified is per item, otherwise it is per feed
     * @param int $itemsSideBySide Should items be shown side by side
     * @param int $itemsPerPage When in single mode, how many items per page should be shown
     * @param int $upperLimit Upper low limit for this dataSet, 0 for nor limit
     * @param int $lowerLimit lower low limit for this dataSet, 0 for nor limit
     * @param string $dateFormat The date format to apply to all dates returned by the ticker
     * @param string $backgroundColor A HEX color to use as the background color of this widget
     * @param string $noDataMessage A message to display when no data is returned from the source
     * @param string $template Template for each item, replaces [itemsTemplate] in main template
     * @param string $css Optional StyleSheet CSS
     * @param string $filter SQL clause for filter this dataSet
     * @param string $ordering SQL clause for how this dataSet should be ordered
     * @param int $useOrderingClause Use advanced order clause - set to 1 if ordering is provided
     * @param int $useFilteringClause Use advanced filter clause - set to 1 if filter is provided
     * @param int $widgetId The Widget ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     *
     * @return XiboTicker
     */

    public function editDataSet($name, $duration, $useDuration, $updateInterval, $effect, $speed, $durationIsPerItem, $itemsSideBySide, $itemsPerPage, $upperLimit, $lowerLimit, $dateFormat, $backgroundColor, $noDataMessage, $template, $css = '', $filter, $ordering, $useOrderingClause, $useFilteringClause, $widgetId, $enableStat = '')
    {
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->updateInterval = $updateInterval;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->durationIsPerItem = $durationIsPerItem;
        $this->itemsSideBySide = $itemsSideBySide;
        $this->itemsPerPage = $itemsPerPage;
        $this->upperLimit = $upperLimit;
        $this->lowerLimit = $lowerLimit;
        $this->dateFormat = $dateFormat;
        $this->backgroundColor = $backgroundColor;
        $this->noDataMessage = $noDataMessage;
        $this->template = $template;
        $this->css = $css;
        $this->filter = $filter;
        $this->ordering = $ordering;
        $this->useOrderingClause = $useOrderingClause;
        $this->useFilteringClause = $useFilteringClause;

        $this->getLogger()->info('Editing widget ID ' . $widgetId);
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
