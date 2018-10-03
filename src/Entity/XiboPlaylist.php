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

/**
 * Class XiboPlaylist
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboPlaylist extends XiboEntity
{
    /** @var int The Playlist ID */
    public $playlistId;

    /** @var int The Widget ID */
    public $widgetId;

    /** @var int The Owner ID */
    public $ownerId;

    /** @var int The Playlist ID */
    public $widgetOptions;

    /** @var int The display order */
    public $displayOrder;

    /** @var int The duration */
    public $duration;

    /**
     * Return a list of playlists.
     *
     * @param array $params can be filtered by: playlistId, widgetId
     * @return array|XiboPlaylist
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting list of Playlists');
        $entries = [];
        $response = $this->doGet('/playlist/widget', $params);
        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }
    /**
     * Assign media to playlist
     * @param array[int] $media Array of Media IDs to assign
     * @param int $duration Optional duration for all Media in this assignment to use on the widget
     * @param int $playlistId The Playlist ID to assign to
     * @return XiboPlaylist
     */
    public function assign($media, $duration, $playlistId)
    {
        $this->playlistId = $playlistId;
        $this->media = $media;
        $this->getLogger()->info('Assigning Media To Playlist ' . $this->playlistId);
        $response = $this->doPost('/playlist/library/assign/' . $playlistId, [
        	'media' => $media,
            'duration' => $duration
        	]);

        // Parse response
        //  set properties
        $this->playlistId = $response['playlistId'];

        $this->getLogger()->debug('Creating child object Widgets and linking it to parent Playlist');
        // Set widgets property (with XiboWidget objects)
        foreach ($response['widgets'] as $widget) {
            $this->widgets[] = (new XiboWidget($this->getEntityProvider()))->hydrate($widget);
        }

        return $this;
    }
}