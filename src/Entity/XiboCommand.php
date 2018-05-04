<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboCommand.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboCommand extends XiboEntity
{
	private $url = '/command';

	public $commandId;
	public $command;
	public $code;
	public $description;
	public $userId;
	public $commandString;
	public $validationString;

	/**
	 * @param array $params
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
	 * @param $id
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
	 * Create
	 * @param $name
	 * @param $description
	 * @param $code
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
	 * Edit
	 * @param $name
	 * @param $description
	 * @param $code
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
	 * Delete
	 * @return bool
	 */
	public function delete()
	{
		$this->getLogger()->info('Deleting display command ID ' . $this->commandId);
		$this->doDelete('/command/' . $this->commandId);
	
		return true;
	}
}
