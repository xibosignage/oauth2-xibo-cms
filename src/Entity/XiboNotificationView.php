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

class XiboNotificationView extends XiboWidget
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

    /** @var int The maximum notification age in minutes, 0 for all */
    public $age;

    /** @var string Message to show when no notifications are available */
    public $noDataMessage;

    /** @var string Effect that will be used to transitions between items */
    public $effect;

    /** @var int The transition speed of the selected effect in milliseconds */
    public $speed;

    /** @var int Flag The duration specified is per page/item, otherwise the widget duration is divided between the number of pages/items */
    public $durationIsPerItem;

    /** @var string Custom Style Sheets (CSS) */
    public $embedStyle;

    /**
     * Create NotificationView widget.
     *
     * @param string $name Optional widget name
     * @param int $duration Widget duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $age The maximum notification age in minutes, 0 for all
     * @param string $noDataMessage Message to show when no notifications are available
     * @param string $effect Effect that will be used to transitions between items, available options: fade, fadeout, scrollVert, scollHorz, flipVert, flipHorz, shuffle, tileSlide, tileBlind
     * @param int $speed The transition speed of the selected effect in milliseconds
     * @param int $durationIsPerItem Flag The duration specified is per page/item, otherwise the widget duration is divided between the number of pages/items
     * @param string $embedStyle Custom Style Sheets (CSS)
     * @param int $playlistId Playlist ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboNotificationView
     */
    public function create($name, $duration, $useDuration, $age, $noDataMessage, $effect, $speed, $durationIsPerItem, $embedStyle = null, $playlistId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->age = $age;
        $this->noDataMessage = $noDataMessage;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->durationIsPerItem = $durationIsPerItem;
        $this->embedStyle = $embedStyle;
        $this->playlistId = $playlistId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Creating Notification widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/notificationview/' . $playlistId, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit Existing NotificationView Widget.
     *
     * @param string $name Optional widget name
     * @param int $duration Widget duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $age The maximum notification age in minutes, 0 for all
     * @param string $noDataMessage Message to show when no notifications are available
     * @param string $effect Effect that will be used to transitions between items, available options: fade, fadeout, scrollVert, scollHorz, flipVert, flipHorz, shuffle, tileSlide, tileBlind
     * @param int $speed The transition speed of the selected effect in milliseconds
     * @param int $durationIsPerItem Flag The duration specified is per page/item, otherwise the widget duration is divided between the number of pages/items
     * @param string $embedStyle Custom Style Sheets (CSS)
     * @param int $widgetId Widget ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboNotificationView
     */
    public function edit($name, $duration, $useDuration, $age, $noDataMessage, $effect, $speed, $durationIsPerItem, $embedStyle = null, $widgetId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->age = $age;
        $this->noDataMessage = $noDataMessage;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->durationIsPerItem = $durationIsPerItem;
        $this->embedStyle = $embedStyle;
        $this->widgetId = $widgetId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing widget ID ' . $widgetId);
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
