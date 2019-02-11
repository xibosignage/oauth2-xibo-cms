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

class XiboDisplayProfile extends XiboEntity
{
	private $url = '/displayprofile';

    /** @var int The Display Profile ID */
	public $displayProfileId;

    /** @var string The Display Profile name */
	public $name;

    /** @var string Display Profile type (windows|android|lg) */
	public $type;

    /** @var int Flag indicating whether this display profile is default for this type */
	public $isDefault;

    /** @var int The User ID */
	public $userId;

    /** @var int The Media ID of the Player Software installer */
    public $versionMediaId;

	/**
     * Return a list of displayProfiles.
     *
	 * @param array $params can be filtered by: displayprofileId, displayprofile, type and embeddable parameters: embed=config, commands, configWithDefault
	 * @return array|XiboDisplayProfile
	 */
	public function get(array $params = [])
	{
		$this->getLogger()->info('Getting list of Display Profiles');
		$entries = [];
		$response = $this->doGet($this->url, $params);
		foreach ($response as $item) {
			$entries[] = clone $this->hydrate($item);
		}
		
		return $entries;
	}

	/**
     * Get Display Profile by ID.
     *
	 * @param int $id The Display Profile ID
	 * @return XiboDisplayProfile
	 * @throws XiboApiException
	 */
	public function getById($id)
	{
		$this->getLogger()->info('Getting Display Profile ID ' . $id);
		$response = $this->doGet($this->url, [
			'displayProfileId' => $id
		]);
		
		if (count($response) <= 0)
		throw new XiboApiException('Expecting a single record, found ' . count($response));
		
		return clone $this->hydrate($response[0]);
}


    /**
     * Create Display Profile.
     *
     * @param string $name Display Profile name
     * @param string $type Display Profile type windws|android|lg
     * @param int $isDefault Flag indicating whether this is the default profile for the client type
     * @return XiboDisplayProfile
     */
	public function create($name, $type, $isDefault)
	{
		$this->userId = $this->getEntityProvider()->getMe()->getId();
		$this->name = $name;
		$this->type = $type;
		$this->isDefault = $isDefault;
		$this->getLogger()->info('Creating Display Profile ' . $name);
		$response = $this->doPost('/displayprofile', $this->toArray());
		
		return $this->hydrate($response);
	}

	/**
	 * Edit Display Profile.
     *
     * @param string $name Display Profile name
     * @param string $type Display Profile type windws|android|lg
     * @param int $isDefault Flag indicating whether this is the default profile for the client type
     * @param int $versionMediaId The Media ID of the Player Software installer
	 * @return XiboDisplayProfile
	 */
	public function edit($name, $type, $isDefault, $versionMediaId = 0)
	{
		$this->name = $name;
		$this->type = $type;
		$this->isDefault = $isDefault;
		$this->versionMediaId = $versionMediaId;
		$this->getLogger()->info('Editing Display profile ' . $this->displayProfileId);
		$response = $this->doPut('/displayprofile/' . $this->displayProfileId, $this->toArray());
		
		return $this->hydrate($response);
	}

	/**
	 * Delete Display Profile.
     *
	 * @return bool
	 */
	public function delete()
	{
		$this->getLogger()->info('Deleting Display profile ID ' . $this->displayProfileId);
		$this->doDelete('/displayprofile/' . $this->displayProfileId);
		
		return true;
	}

    /**
     * Copy Display Profile.
     *
     * @param int $displayProfileId The Display Profile ID to copy
     * @param string $name Display Profile name
     * @return XiboDisplayProfile
     */
    public function copy($displayProfileId, $name)
    {
        $this->name = $name;
        $this->displayProfileId = $displayProfileId;

        $this->getLogger()->info('Creating a Copy of Display Profile ' . $name);
        $response = $this->doPost('/displayprofile/' . $displayProfileId . '/copy', [
            'name' => $name
        ]);

        return $this->hydrate($response);
    }
}
