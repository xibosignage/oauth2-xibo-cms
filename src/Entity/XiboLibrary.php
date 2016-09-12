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
	public $error;


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
		
		return clone $this->hydrate($response[0]);
	}

public function create($name, $fileLocation)
        {
                $response = $this->doPost('/library',['multipart' => [
            [
                'name' => 'name',
                'contents' => $name
            ],
            [
                'name' => 'files',
                'contents' => fopen($fileLocation, 'r')
            ]
        ]]);
        // Response will have the format:
        /*{
            "files":[{
                "name": "Name",
                "size": 52770969,
                "type": "video/mp4",
                "mediaId": 2344,
                "storedas": "2344.mp4",
                "error": ""
            }]
        }*/
        if (!isset($response['files']) || count($response['files']) != 1)
            throw new XiboApiException('Invalid return from library add');
        if (!empty($response['files'][0]['error']))
            throw new XiboApiException($response['files'][0]['error']);
        // Modify some of the return
        unset($response['files'][0]['url']);
        $response['files'][0]['storedAs'] = $response['files'][0]['storedas'];
        $media = new XiboLibrary($this->getEntityProvider());
        return $media->hydrate($response['files'][0]);
        }

	/**
	 * Edit
	 * @param $mediaName
	 * @param $mediaDuration
	 * @param $mediaRetired // there is a wrong description in API doc 'flag indicating if this layout is retired'
	 * @param $mediaTags
	 * @param $mediaUpdate
	 * @return XiboLibrary
	 */
	public function edit($mediaName, $mediaDuration, $mediaRetired, $mediaTags, $mediaUpdate)
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
