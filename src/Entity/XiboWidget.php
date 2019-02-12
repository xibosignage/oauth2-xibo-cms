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
 * Class XiboWidget
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboWidget extends XiboEntity
{
    /** @var int The Playlist ID */
    public $playlistId;

    /** @var int The Widget ID */
    public $widgetId;

    /** @var int The Owner ID */
    public $ownerId;

    /** @var string Widget Type */
    public $type;

    /** @var int Widget duration */
    public $duration;

    /** @var int Widget Display Order */
    public $displayOrder;

    /** @var int Flag indicating whether to use custom duration */
    public $useDuration;

    public $widgetOptions;
    public $mediaIds;
    public $audio;
    public $permissions;

    /**
     * Return a list of playlists.
     *
     * @param array $params can be filtered by: playlistId, widgetId
     * @return array|XiboWidget
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting list of Widgets in Playlist');
        $entries = [];
        $response = $this->doGet('/playlist/widget', $params);
        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Get by Id
     * @param int $widgetId Widget ID
     * @return $this|XiboWidget
     * @throws XiboApiException
     */
    public function getById($widgetId)
    {
        $this->getLogger()->info('Getting widget ID ' . $widgetId);
        $response = $this->doGet('/playlist/widget', [
            'widgetId' => $widgetId
        ]);

        $resonse = clone $this->hydrate($response[0]);
        if ($response[0]['type'] != $this->type)
            throw new XiboApiException('Invalid widget type');

        return $this;
    }

    /**
     * Delete the widget.
     *
     * @param int $widgetId the Widget ID
     *
     * @return boolean
     */
    public function deleteWidget($widgetId)
    {
        $this->widgetId = $widgetId;
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Deleting widget ID ' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}