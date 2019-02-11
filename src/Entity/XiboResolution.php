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

class XiboResolution extends XiboEntity
{
    public $url = '/resolution';

    /** @var int The Resolution ID */
    public $resolutionId;

    /** @var string The Resolution name */
    public $resolution;

    /** @var int The Display Width */
    public $width;

    /** @var int The Display Height */
    public $height;

    /** @var int Version */
    public $version = 2;

    /** @var int Flag indicating whether the resolution is enabled */
    public $enabled = 1;

    /**
     * Get a list of resolutions.
     *
     * @param array $params can be filtered by resolutionId, resolution, enabled
     * @return array|XiboResolution
     */
    public function get(array $params = [])
    {
        $entries = [];
        $response = $this->doGet($this->url, $params);
        $this->getLogger()->info('Getting list of resolutions');
        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Get Resolution by ID
     *
     * @param int $id Resolution ID
     * @return XiboResolution
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $this->getLogger()->info('Getting resolution ID ' . $id);
        $response = $this->doGet($this->url, [
            'resolutionId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create new Resolution.
     *
     * @param string $resolution Resolution name
     * @param int $width Resolution Width
     * @param int $height Resolution Height
     * @return XiboResolution
     */
    public function create($resolution, $width, $height)
    {
        $this->resolution = $resolution;
        $this->width = $width;
        $this->height = $height;
        $this->getLogger()->info('Creating a new Resolution '. $this->resolution);
        $response = $this->doPost('/resolution', $this->toArray());
        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
     * Edit existing resolution.
     *
     * @param string $resolution Resolution name
     * @param int $width Resolution Width
     * @param int $height Resolution Height
     * @return XiboResolution
     */
    public function edit($resolution, $width, $height)
    {
        $this->resolution = $resolution;
        $this->width = $width;
        $this->height = $height;
        $this->getLogger()->info('Editing resolution ID ' . $this->resolutionId);
        $response = $this->doPut('/resolution/' . $this->resolutionId, $this->toArray());
        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
     * Delete the resolution.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting resolution ID ' . $this->resolutionId);
        $this->doDelete('/resolution/' . $this->resolutionId);

        return true;
    }
}
