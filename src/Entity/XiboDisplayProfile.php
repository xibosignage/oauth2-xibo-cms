<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDisplayProfile.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboDisplayProfile extends XiboEntity
{
	private $url = '/displayprofile';
	public $displayProfileId;
	public $name;
	public $type;
	public $isDefault;
	public $userId;



	/**
	 * @param array $params
	 * @return array|XiboDisplayProfile
	 */
	public function get(array $params = [])
	{
		$entries = [];
		$response = $this->doGet($this->url, $params);
		foreach ($response as $item) {
			$entries[] = clone $this->hydrate($item);
		}
		
		return $entries;
	}

	/**
	 * @param $id
	 * @return XiboDisplayProfile
	 * @throws XiboApiException
	 */
	public function getById($id)
	{
		$response = $this->doGet($this->url, [
			'displayProfileId' => $id
		]);
		
		if (count($response) <= 0)
		throw new XiboApiException('Expecting a single record, found ' . count($response));
		
		return clone $this->hydrate($response[0]);
}


	/**
	* Create
	* @param $name
	* @param $type
	* @param $isDefault
	*/
	public function create($name, $type, $isDefault)
	{
		$this->userId = $this->getEntityProvider()->getMe()->getId();
		$this->name = $name;
		$this->type = $type;
		$this->isDefault = $isDefault;
		$response = $this->doPost('/displayprofile', $this->toArray());
		
		return $this->hydrate($response);
	}

	/**
	 * Edit
	 * @param $name
	 * @param $type
	 * @param $isDefault
	 * @return XiboDisplayProfile
	 */
	public function edit($name, $type, $isDefault)
	{
		$this->name = $name;
		$this->type = $type;
		$this->isDefault = $isDefault;
		$response = $this->doPut('/displayprofile/' . $this->displayProfileId, $this->toArray());
		
		return $this->hydrate($response);
	}

	/**
	 * Delete
	 * @return bool
	 */
	public function delete()
	{
		$this->doDelete('/displayprofile/' . $this->displayProfileId);
		
		return true;
	}
}
