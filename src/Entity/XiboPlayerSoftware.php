<?php
/**
* Copyright (C) 2019 Xibo Signage Ltd
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

class XiboPlayerSoftware extends XiboEntity
{
    /**
     * @var int Version ID
     */
    public $versionId;

    /**
     * @var string Player type
     */
    public $type;

    /**
     * @var string Version Number
     */
    public $version;

    /**
     * @var int Code Number
     */
    public $code;

    /**
     * @var int The Media ID
     */
    public $mediaId;

    /**
     * @var string Player version to show
     */
    public $playerShowVersion;


    /**
     * Get an array of Player Versions.
     *
     * @param array $params can be filtered by: playerType, playerCode, playerVersion, versionId, mediaId
     * @return array|XiboPlayerSoftware
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting list of Player Versions ');
        $entries = [];
        $response = $this->doGet('/playersoftware', $params);
        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Get the Player Version by version ID.
     *
     * @param int $id versionId to search for
     * @return XiboPlayerSoftware
     * @throws XiboApiException
     */
    public function getByVersionId($id)
    {
        $this->getLogger()->info('Getting Player Version by version ID ' . $id);
        $response = $this->doGet('/playersoftware', [
            'versionId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Get the Player Version by media ID.
     *
     * @param int $id versionId to search for
     * @return XiboPlayerSoftware
     * @throws XiboApiException
     */
    public function getByMediaId($id)
    {
        $this->getLogger()->info('Getting Player Version by media ID ' . $id);
        $response = $this->doGet('/playersoftware', [
            'mediaId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Edit an existing Player Version.
     *
     * @param int $versionId The Version ID of the installer file to edit
     * @param string $playerShowVersion Player version to show
     * @param string $version Version of the installer file
     * @param int $code Code of the installer file
     * @return XiboPlayerSoftware
     */
    public function edit($versionId, $playerShowVersion, $version, $code)
    {
        $this->playerShowVersion = $playerShowVersion;
        $this->version = $version;
        $this->code = $code;

        $this->getLogger()->info('Editing Player Software version ID ' . $versionId);
        $response = $this->doPut('/playersoftware/' . $versionId, $this->toArray());
        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
     * Delete the Player Version
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting Player Version version ID ' . $this->versionId);
        $this->doDelete('/playersoftware/' . $this->versionId);

        return true;
    }
}