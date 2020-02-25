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

class XiboWeather extends XiboWidget
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

    /** @var int Flag Use the location configured on the Display? */
    public $useDisplayLocation;

    /** @var double The longitude for this Weather widget, only pass if useDisplayLocation set to 0 */
    public $longitude;

    /** @var double The latitude for this Weather widget, only pass if useDisplayLocation set to 0 */
    public $latitude;

    /** @var string Use pre-configured Templates */
    public $templateId;

    /** @var string Units you would like to use */
    public $units;

    /** @var int Update interval in minutes, should be kept as high as possible, if data change once per hour, this should be set to 60 */
    public $updateInterval;

    /** @var string Language you’d like to use */
    public $lang;

    /** @var int Flag whether to show only Daytime conditions or not */
    public $dayConditionsOnly;

    /**
     * Create Weather Widget.
     *
     * @param string $name Optional widget name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $useDisplayLocation Flag Use the location configured on the Display?
     * @param double $longitude The longitude for this Google Traffic widget, only pass if useDisplayLocation set to 0
     * @param double $latitude he latitude for this Google Traffic widget, only pass if useDisplayLocation set to 0
     * @param string $templateId Use pre-configured templates, available options: weather-module0-5day, weather-module0-singleday, weather-module0-singleday2, weather-module1l, weather-module1p, weather-module2l, weather-module2p, weather-module3l, weather-module3p, weather-module4l, weather-module4p, weather-module5l, weather-module6v, weather-module6h
     * @param string $units Units you would like to use, available options: auto, ca, si, uk2, us
     * @param int $updateInterval Update interval in minutes, should be kept as high as possible, if data change once per hour, this should be set to 60
     * @param string $lang Language you’d like to use, supported languages ar, az, be, bs, cs, de, en, el, es, fr, hr, hu, id, it, is, kw, nb, nl, pl, pt, ru, sk, sr, sv, tet, tr, uk, x-pig-latin, zh, zh-tw
     * @param int $dayConditionsOnly Flag (0, 1) Would you like to only show the Daytime weather conditions
     * @param int $playlistId Playlist ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboWeather
     */
    public function create($name, $duration, $useDuration, $useDisplayLocation, $longitude, $latitude, $templateId, $units, $updateInterval, $lang, $dayConditionsOnly, $playlistId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->useDisplayLocation = $useDisplayLocation;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->templateId = $templateId;
        $this->units = $units;
        $this->updateInterval = $updateInterval;
        $this->lang = $lang;
        $this->dayConditionsOnly = $dayConditionsOnly;
        $this->playlistId = $playlistId;
        $this->enableStat = $enableStat;

        $this->getLogger()->info('Creating Weather widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/forecastIo/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit Weather widget.
     *
     * @param string $name Optional widget name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $useDisplayLocation Flag Use the location configured on the Display?
     * @param double $longitude The longitude for this Google Traffic widget, only pass if useDisplayLocation set to 0
     * @param double $latitude he latitude for this Google Traffic widget, only pass if useDisplayLocation set to 0
     * @param string $templateId Use pre-configured templates, available options: weather-module0-5day, weather-module0-singleday, weather-module0-singleday2, weather-module1l, weather-module1p, weather-module2l, weather-module2p, weather-module3l, weather-module3p, weather-module4l, weather-module4p, weather-module5l, weather-module6v, weather-module6h
     * @param string $units Units you would like to use, available options: auto, ca, si, uk2, us
     * @param int $updateInterval Update interval in minutes, should be kept as high as possible, if data change once per hour, this should be set to 60
     * @param string $lang Language you’d like to use, supported languages ar, az, be, bs, cs, de, en, el, es, fr, hr, hu, id, it, is, kw, nb, nl, pl, pt, ru, sk, sr, sv, tet, tr, uk, x-pig-latin, zh, zh-tw
     * @param int $dayConditionsOnly Flag (0, 1) Would you like to only show the Daytime weather conditions
     * @param int $widgetId Widget ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboWeather
     */
    public function edit($name, $duration, $useDuration, $useDisplayLocation, $longitude, $latitude, $templateId, $units, $updateInterval, $lang, $dayConditionsOnly, $widgetId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->useDisplayLocation = $useDisplayLocation;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->templateId = $templateId;
        $this->units = $units;
        $this->updateInterval = $updateInterval;
        $this->lang = $lang;
        $this->dayConditionsOnly = $dayConditionsOnly;
        $this->widgetId = $widgetId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing Weather widget ID ' . $widgetId);
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