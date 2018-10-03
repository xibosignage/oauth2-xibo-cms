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

class XiboCommand extends XiboEntity
{
	private $url = '/command';

    /** @var int The command ID */
	public $commandId;

    /** @var string The command name */
	public $command;

    /** @var string Unique code for this command */
	public $code;

    /** @var string Command description */
	public $description;

	/**
     * Get the list commands.
     *
	 * @param array $params filter the results by, commandId, command or code
	 * @return array|XiboCommand
	 */
	public function get(array $params = [])
	{
		$entries = [];
		$this->getLogger()->info('Getting a list of display commands');
		$response = $this->doGet($this->url, $params);
		foreach ($response as $item) {
			$entries[] = clone $this->hydrate($item);
		}
	
		return $entries;
	}


	/**
     * Get the command by commandId.
     *
	 * @param int $id The Command ID to find
	 * @return XiboCommand
	 * @throws XiboApiException
	 */
	public function getById($id)
	{
		$this->getLogger()->info('Getting display command ID ' . $id);
		$response = $this->doGet($this->url, [
			'commandId' => $id
		]);
		if (count($response) <= 0)
		throw new XiboApiException('Expecting a single record, found ' . count($response));

		return clone $this->hydrate($response[0]);
	}


	/**
	 * Create a new command.
     *
	 * @param string $name Name of the command
	 * @param string $description Command description
	 * @param string $code Unique code for this command
	 * @return XiboCommand
	 */
	public function create($name, $description, $code)
	{
		$this->userId = $this->getEntityProvider()->getMe()->getId();
		$this->command = $name;
		$this->description = $description;
		$this->code = $code;
		$this->getLogger()->info('Creating new display command ' . $name);
		$response = $this->doPost('/command', $this->toArray());

		return $this->hydrate($response);
	}


	/**
	 * Edit the Command.
     *
     * @param string $name Name of the command
     * @param string $description Command description
     * @param string $code Unique code for this command
	 * @return XiboCommand
	 */
	public function edit($name, $description, $code)
	{
		$this->command = $name;
		$this->description = $description;
		$this->code = $code;
		$this->getLogger()->info('Editing display command ID ' . $this->commandId);
		$response = $this->doPut('/command/' . $this->commandId, $this->toArray());
	
		return $this->hydrate($response);
	}

	/**
	 * Delete the command.
     *
	 * @return bool
	 */
	public function delete()
	{
		$this->getLogger()->info('Deleting display command ID ' . $this->commandId);
		$this->doDelete('/command/' . $this->commandId);
	
		return true;
	}
}
