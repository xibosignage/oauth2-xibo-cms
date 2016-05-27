<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboLibrary.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;
class XiboLibrary extends XiboEntity
{
	private $url = '/library';
	public $mediaId;
	public $ownerId;
	public $parentId;
	public $name;
	public $mediaType;
	public $storedAs;
	public $fileName;


	/**
	 * @param array $params
	 * @return array|XiboLibrary
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
	 * @return XiboLibrary
	 * @throws XiboApiException
	 */
	public function getById($id)
	{
		$response = $this->doGet($this->url, [
		'mediaId' => $id
		]);
		if (count($response) <= 0)
		throw new XiboApiException('Expecting a single record, found ' . count($response));
		
		return $this->hydrate($response[0]);
	}

	/**
	 * Edit
	 * @param $mediaName
	 * @param $mediaDuration
	 * @param $lmediaRetired // there is a wrong thing in API doc 'flag indicating if this layout is retired'
	 * @param $mediaTags
	 * @param $mediaUpdate
	 * @return XiboLayout
	 */
	public function edit($mediaName, $mediaDuration, $lmediaRetired, $mediaTags, $mediaUpdate)
	{
		$this->name = $mediaName;
		$this->duration = $mediaDuration;
		$this->tags = $mediaTags;
		$this->retired = $mediaRetired;
		$this->updateInLayouts = $mediaUpdate;
		$response = $this->doPut('/library/' . $this->mediaId, $this->toArray());
		
		return $this->hydrate($response);
	}

	/**
	 * Delete
	 * @return bool
	 */
	public function delete()
	{
		$this->doDelete('/library/' . $this->mediaId);
		
		return true;
	}
}
