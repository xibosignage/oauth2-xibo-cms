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

class XiboAudio extends XiboWidget
{
    /** @var int The Widget ID */
    public $widgetId;

    /** @var int The playlist ID */
    public $playlistId;

    /** @var int user ID of the owner */
    public $ownerId;

    /** @var string The Widget Type */
    public $type;

    /** @var string Name for this widget */
    public $name;

    /** @var int The Widget Duration */
    public $duration;

    /** @var int Flag, set to 1 if the custom duration is passed */
    public $useDuration;

    /**
     * Edit Audio Widget.
     *
     * @param string $name Name of the widget
     * @param int $useDuration Flag (0, 1) Set to 1 if the duration parameter is passed as well
     * @param int $duration Duration of the widget
     * @param int $mute Flag (0, 1) Set the widget to mute
     * @param int $loop Flag (0, 1) Set the widget to loop
     * @param int $widgetId Id of the widget to edit
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboAudio
     */
    public function edit($name, $useDuration, $duration, $mute, $loop, $widgetId, $enableStat = '')
    {
        $this->name = $name;
        $this->useDuration = $useDuration;
        $this->duration = $duration;
        $this->mute = $mute;
        $this->loop = $loop;
        $this->enableStat = $enableStat;
        $this->widgetId = $widgetId;

        $this->getLogger()->info('Editing widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
     * @param int $mediaId Id of the audio file in CMS library to assign to the widget
     * @param int $volume Volume Percentage (0-100) for this audio to play at
     * @param int $loop Flag (0, 1) Should the audio loop if it finishes before the widget has finished
     * @param int $widgetId Id of the widget to which the audio file should be assigned
     * @return XiboWidget
     */
    public function assignToWidget($mediaId, $volume, $loop, $widgetId)
    {
        $this->mediaId = $mediaId;
        $this->volume = $volume;
        $this->loop = $loop;
        $this->widgetId = $widgetId;
        $this->getLogger()->info('Assigning audio file ID ' . $mediaId . ' To widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId . '/audio', $this->toArray());

        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
    * Delete the Widget
    */
    public function delete()
    {
        $this->getLogger()->info('Deleting widget ID ' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
