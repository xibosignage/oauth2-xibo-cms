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

class XiboClock extends XiboWidget
{
    /** @var int The widget ID */
    public $widgetId;

    /** @var int The playlist ID */
    public $playlistId;

    /** @var int the userId of the owner */
    public $ownerId;

    /** @var string The type of the widget */
    public $type;

    /** @var int The widget duration*/
    public $duration;

    /** @var int flag (0, 1) set to 1 if the duration parameter is passed as well */
    public $useDuration;

    /** @var int The widget displayOrder */
    public $displayOrder;

    /** @var string The widget name */
    public $name;

    /**
     * Create a new Clock Widget.
     *
     * @param string $name widget name
     * @param int $duration widget duration
     * @param int $useDuration flag (0, 1) set to 1 if the duration parameter is passed as well
     * @param int $themeId For Analogue clock, light and dark themes
     * @param int $clockTypeId Type of the clock 1- Analogue, 2- Digital, 3- Flip Clock
     * @param string $offset The offset in minutes that should be applied to the current time, if a counter is selected then date/time to run from in format Y-m-d H:i:s
     * @param string $format For digital clock, format in which the time should be displayed
     * @param int $showSeconds For Flip Clock, should the clock show seconds or not
     * @param string $clockFace For Flip Clock, supported options are: TwelveHourClock, TwentyFourHourClock, DailyCounter, HourlyCounter, MinuteCounter
     * @param int $playlistId Playlist ID to which the clock widget should be added
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboClock
     */
    public function create($name, $duration, $useDuration, $themeId, $clockTypeId, $offset, $format, $showSeconds, $clockFace, $playlistId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->themeId = $themeId;
        $this->clockTypeId = $clockTypeId;
        $this->offset = $offset;
        $this->format = $format;
        $this->showSeconds = $showSeconds;
        $this->clockFace = $clockFace;
        $this->playlistId = $playlistId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Creating Clock widget and assigning it to playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/clock/' . $playlistId, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit the Clock widget.
     *
     * @param string $name widget name
     * @param int $duration widget duration
     * @param int $useDuration flag (0, 1) set to 1 if the duration parameter is passed as well
     * @param int $themeId For Analogue clock, light and dark themes
     * @param int $clockTypeId Type of the clock 1- Analogue, 2- Digital, 3- Flip Clock
     * @param string $offset The offset in minutes that should be applied to the current time, if a counter is selected then date/time to run from in format Y-m-d H:i:s
     * @param string $format For digital clock, format in which the time should be displayed
     * @param int $showSeconds For Flip Clock, should the clock show seconds or not
     * @param string $clockFace For Flip Clock, supported options are: TwelveHourClock, TwentyFourHourClock, DailyCounter, HourlyCounter, MinuteCounter
     * @param int $widgetId Widget Id to Edit
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboClock
     */
    public function edit($name, $duration, $useDuration, $themeId, $clockTypeId, $offset, $format, $showSeconds, $clockFace, $widgetId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->themeId = $themeId;
        $this->clockTypeId = $clockTypeId;
        $this->offset = $offset;
        $this->format = $format;
        $this->showSeconds = $showSeconds;
        $this->clockFace = $clockFace;
        $this->widgetId = $widgetId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
    * Delete the widget
    */
    public function delete()
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Deleting widget ID ' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
