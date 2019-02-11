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

    /** @var int The Owner ID */
    public $ownerId;

    /** @var string Playlist name */
    public $name;

    /** @var int The region ID */
    public $regionId;

    /** @var int Flag indicating whether the playlist is dynamic */
    public $isDynamic;

    /** @var string For dynamic playlist the filter by media Name */
    public $filterMediaName;

    /** @var string For dynamic playlist the filter by media Tags  */
    public $filterMediaTags;

    /** @var string Date showing when playlist was created */
    public $createdDt;

    /** @var string Date showing when playlist was modified */
    public $modifiedDt;

    /** @var int Playlist duration */
    public $duration;

    /** @var array Array of tags */
    public $tags;

    /** @var array of widgets in the playlist */
    public $widgets;

    /** @var array of permissions to the playlist */
    public $permissions;

    /** @var string Owner name */
    public $owner;

    public $groupsWithPermissions;

    /**
     * Return a list of playlists.
     *
     * @param array $params can be filtered by: playlistId, name, userId, tags, mediaLike, embeddable parameter embed=widgets
     * @return array|XiboPlaylist
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting list of Playlists');
        if (isset($params['embed']))
            $embed = ($params['embed'] != null) ? explode(',', $params['embed']) : [];

        $hydratedWidgets = [];
        $entries = [];

        $response = $this->doGet('/playlist', $params);
        foreach ($response as $item) {
            /** @var XiboPlaylist $playlist */
            $playlist = new XiboPlaylist($this->getEntityProvider());
            $playlist->hydrate($item);
            if (in_array('widgets', $embed) === true) {
                foreach ($playlist->widgets as $widget) {
                    /** @var XiboWidget $widgetObject */
                    $widgetObject = new XiboWidget($this->getEntityProvider());
                    $widgetObject->hydrate($widget);
                    $hydratedWidgets[] = $widgetObject;
                }
                $playlist->widgets = $hydratedWidgets;
            }
            $entries[] = clone $playlist;
        }

        return $entries;
    }

    /**
     * Assign media to playlist.
     *
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

    /**
     * Create a new Playlist
     *
     * @param string $name Name of the playlist
     * @param string $tags A Comma separated list of tags
     * @param int $isDynamic Flag indicating whether the playlist is dynamic
     * @param string $filterMediaName For dynamic playlist the filter by media Name
     * @param string $filterMediaTags For dynamic playlist the filter by media Tag
     * @return XiboPlaylist
     */
    public function add($name, $tags= '', $isDynamic = 0, $filterMediaName = '', $filterMediaTags = '')
    {
        $hydratedWidgets = [];
        $this->name = $name;
        $this->tags = $tags;
        $this->isDynamic = $isDynamic;
        $this->filterMediaName = $filterMediaName;
        $this->filterMediaTags = $filterMediaTags;

        $this->getLogger()->info('Creating Playlist ' . $this->name);
        $response = $this->doPost('/playlist', $this->toArray());

        /** @var XiboPlaylist $playlist */
        $playlist = new XiboPlaylist($this->getEntityProvider());
        $playlist->hydrate($response);
        if (isset($playlist->widgets)) {
            foreach ($playlist->widgets as $widget) {
                /** @var XiboWidget $widgetObject */
                $widgetObject = new XiboWidget($this->getEntityProvider());
                $widgetObject->hydrate($widget);
                $hydratedWidgets[] = $widgetObject;
            }
            $playlist->widgets = $hydratedWidgets;
        }

        return $playlist;
    }

    /**
     * Edit an existing Playlist
     *
     * @param int $playlistId Playlist ID to edit
     * @param string $name Name of the playlist
     * @param string $tags A Comma separated list of tags
     * @param int $isDynamic Flag indicating whether the playlist is dynamic
     * @param string $filterMediaName For dynamic playlist the filter by media Name
     * @param string $filterMediaTags For dynamic playlist the filter by media Tag
     * @return XiboPlaylist
     */
    public function edit($playlistId, $name, $tags= '', $isDynamic = 0, $filterMediaName = '', $filterMediaTags = '')
    {
        $hydratedWidgets = [];
        $this->playlistId = $playlistId;
        $this->name = $name;
        $this->tags = $tags;
        $this->isDynamic = $isDynamic;
        $this->filterMediaName = $filterMediaName;
        $this->filterMediaTags = $filterMediaTags;

        $this->getLogger()->info('Editing Playlist ID' . $playlistId);
        $response = $this->doPut('/playlist/' . $playlistId, $this->toArray());

        /** @var XiboPlaylist $playlist */
        $playlist = new XiboPlaylist($this->getEntityProvider());
        $playlist->hydrate($response);
        if (isset($playlist->widgets)) {
            foreach ($playlist->widgets as $widget) {
                /** @var XiboWidget $widgetObject */
                $widgetObject = new XiboWidget($this->getEntityProvider());
                $widgetObject->hydrate($widget);
                $hydratedWidgets[] = $widgetObject;
            }
            $playlist->widgets = $hydratedWidgets;
        }

        return $playlist;
    }
}