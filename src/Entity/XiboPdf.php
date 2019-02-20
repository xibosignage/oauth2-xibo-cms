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

class XiboPdf extends XiboWidget
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

    /** @var string Optional Widget Name */
    public $name;

    /**
     * Edit the PDF widget.
     *
     * @param string $name Optional widget name
     * @param int $duration Widget duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param int $widgetId Widget ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboPdf
     */
    public function edit($name, $duration, $useDuration, $widgetId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->widgetId = $widgetId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing PDF widget ID ' . $widgetId);
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
