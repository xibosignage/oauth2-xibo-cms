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
	public $deleteOldRevisions;
	public $duration;
	public $error;
	public $fileName;
	public $fileSize;
	public $md5;
	public $mediaId;
	public $mediaType;
	public $name;
	public $ownerId;
	public $parentId;
    public $retired;
	public $storedAs;
    public $tags;
    public $updateInLayouts;

	/**
	 * @param array $params
	 * @return array|XiboLibrary
	 */
	public function get(array $params = [])
	{
		$this->getLogger()->info('Getting list of Media files');
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
		$this->getLogger()->info('Getting media ID ' . $id);
		$response = $this->doGet($this->url, [
		'mediaId' => $id
		]);
		if (count($response) <= 0)
		throw new XiboApiException('Expecting a single record, found ' . count($response));
		
		return clone $this->hydrate($response[0]);
	}

	public function create($name, $fileLocation, $oldMediaId = null, $updateInLayouts = null, $deleteOldRevisions = null)
    {
    	$this->getLogger()->debug('Preparing media payload ');
            $payload = [
            [
                'name' => 'name',
                'contents' => $name
            ],
            [
                'name' => 'files',
                'contents' => fopen($fileLocation, 'r')
            ]
        ];
        $this->getLogger()->info('Uploading new media file ' . $name);
        if ($oldMediaId != null) {
        	$this->getLogger()->debug('Replacing old media ID ' .$oldMediaId);
            $payload[] = [
                'name' => 'oldMediaId',
                'contents' => $oldMediaId
            ];
            $this->getLogger()->debug('Updating Media in all layouts it is assigned to');
            $payload[] = [
                'name' => 'updateInLayouts',
                'contents' => $updateInLayouts
            ];
            $this->getLogger()->debug('Deleting Old Revisions');
            $payload[] = [
                'name' => 'deleteOldRevisions',
                'contents' => $deleteOldRevisions
            ];
    	}
            $response = $this->doPost('/library', ['multipart' => $payload]);
            $this->getLogger()->debug('Uploaded media ' . $response['files'][0]['name'] . ' Media ID ' . $response['files'][0]['mediaId'] . ' Stored as ' . $response['files'][0]['storedas']);
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
        
    public function revise($fileLocation)
    {
    	$this->getLogger()->info('Revising file');
        return $this->create($this->name, $fileLocation, $this->mediaId, $this->updateInLayouts, $this->deleteOldRevisions);
    }

	/**
	 * Edit
	 * @param $name
	 * @param $duration
	 * @param $retired
	 * @param $tags
	 * @param $updateInLayouts
	 * @return XiboLibrary
	 */
	public function edit($name, $duration, $retired, $tags, $updateInLayouts)
	{
		$this->name = $name;
		$this->duration = $duration;
		$this->retired = $retired;
		$this->tags = $tags;
		$this->updateInLayouts = $updateInLayouts;
		$this->getLogger()->info('Editing Media ID ' . $this->mediaId);
		$response = $this->doPut('/library/' . $this->mediaId, $this->toArray());
		
		return $this->hydrate($response);
	}

	/**
	 * Delete
	 * @return bool
	 */
	public function delete()
	{
		$this->getLogger()->info('Deleting media ID ' . $this->mediaId);
		$this->doDelete('/library/' . $this->mediaId);
		
		return true;
	}

	/**
     * Delete assigned media
     * @return bool
     */
    public function deleteAssigned()
    {
		$this->getLogger()->info('Force deleting assigned media ID ' . $this->mediaId);
        $this->doDelete('/library/' . $this->mediaId, [
            'forceDelete' => 1
            ]);
        
        return true;
    }

	/**
	 * Add tag
	 * @param $tags
	 * @return XiboLibrary
	 */
	public function AddTag($tags)
	{
		$this->tag = $tags;
		$response = $this->doPost('/library/' . $this->mediaId . '/tag', [
			'tag' => [$tags]
			]);
		
		$tags = $this->hydrate($response);
		foreach ($response['tags'] as $item) {
			$tag = new XiboLibrary($this->getEntityProvider());
			$tag->hydrate($item);
			$tags->tags[] = $tag;
		}
		return $this;
	}
}
