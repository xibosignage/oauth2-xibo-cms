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
class XiboLibrary extends XiboEntity
{
	private $url = '/library';

    /** @var int Media ID */
	public $mediaId;

    /** @var string Media name */
	public $media;

    /** @var string Media type */
	public $mediaType;

    /** @var int Id of an existing media file which should be replaced with the new upload */
    public $oldMediaId;

    /** @var int Flag, to either remove or leave the old file revisions (use with oldMediaId) */
	public $deleteOldRevisions;

    /** @var int Flag, set to 1 to update this media in all layouts (use with oldMediaId) */
    public $updateInLayouts;

    /** @var int media Duration */
	public $duration;

    /** @var string A number or less-than,greater-than,less-than-equal or great-than-equal followed by a | followed by a number */
	public $fileSize;

    /** @var string Media name */
	public $name;

    /** @var int Owner ID */
	public $ownerId;

    /** @var int Flag indicating if this media is retired */
    public $retired;

    /** @var string Media stored as */
	public $storedAs;

    /** @var string media file name */
    public $fileName;

    /** @var string The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit */
    public $enableStat;

    /** @var string Media tags */
    public $tags;

    /** @var int flag indicating whether to treat the tags filter as an exact match */
    public $exactTags;

    /** @var int User Group ID */
    public $ownerUserGroupId;

    /** @var string Date showing when media was created */
    public $createdDt;

    /** @var string Date showing when media was modified */
    public $modifiedDt;

	/**
     * Get an array of media.
     *
	 * @param array $params results can be filtered by: mediaId, media, type, ownerId, retired, tags, exactTags, duration, fileSize, ownerUserGroupId
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
     * Get media by ID.
     *
	 * @param int $id Media ID
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


    /**
     * Upload new media.
     * Upload new media file to the CMS library.
     *
     * @param string $name optional media name
     * @param string $fileLocation location of the file
     * @param int $oldMediaId Id of an existing media file which should be replaced with the new upload
     * @param int $updateInLayouts Flag  set to 1 to update this media in all layouts (use with oldMediaId)
     * @param int $deleteOldRevisions Flag to either remove or leave the old file revisions (use with oldMediaId)
     * @return XiboLibrary
     * @throws XiboApiException
     */
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

        if (isset($response['files'][0]['mediaId']))
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

    /**
     * Revise specified file.
     *
     * @param string $fileLocation location of the file
     * @return XiboLibrary
     * @throws XiboApiException
     */
        
    public function revise($fileLocation)
    {
    	$this->getLogger()->info('Revising file');
        return $this->create($this->name, $fileLocation, $this->mediaId, $this->updateInLayouts, $this->deleteOldRevisions);
    }

    /**
     * Edit existing media file.
     *
     * @param string $name optional media name
     * @param int $duration Media duration
     * @param int $retired Flag indicating if this media is retired
     * @param string $tags Comma separated list of Tags
     * @param int $updateInLayouts Flag indicating whether to update the duration in all Layouts the Media is assigned to
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboLibrary
     */
	public function edit($name, $duration, $retired, $tags, $updateInLayouts, $enableStat = 'Off')
	{
		$this->name = $name;
		$this->duration = $duration;
		$this->retired = $retired;
		$this->tags = $tags;
		$this->updateInLayouts = $updateInLayouts;
		$this->enableStat = $enableStat;
		$this->getLogger()->info('Editing Media ID ' . $this->mediaId);
		$response = $this->doPut('/library/' . $this->mediaId, $this->toArray());
		
		return $this->hydrate($response);
	}

	/**
	 * Delete the media.
     *
	 * @return bool
	 */
	public function delete()
	{
		$this->getLogger()->info('Deleting media ID ' . $this->mediaId);
		$this->doDelete('/library/' . $this->mediaId);
		
		return true;
	}

	/**
     * Delete assigned media.
     *
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
     * Add tag.
     * Adds specified tag to the specified media
     *
     * @param string $tag name of the tag to add
	 * @return XiboLibrary
	 */
	public function AddTag($tag)
    {
        $this->tag = $tag;
        $response = $this->doPost('/library/' . $this->mediaId . '/tag', [
            'tag' => [$tag]
        ]);

        $tags = $this->hydrate($response);
        foreach ($response['tags'] as $item) {
            $tag = new XiboLibrary($this->getEntityProvider());
            $tag->hydrate($item);
            $tags->tags[] = $tag;
        }
        return $this;
    }

    /**
     * Remove tag.
     *
     * Removes specified tag from the specified media
     * @param string $tag name of the taf to remove
     * @return XiboLibrary
     */
    public function removeTag($tag)
    {
        $this->tag = $tag;
        $this->getLogger()->info('Removing tag: ' . $tag . ' from layout ID ' . $this->mediaId);
        $this->doPost('/library/' . $this->mediaId . '/untag', [
            'tag' => [$tag]
        ]);

        return $this;
    }

    /**
     * Tidy the library.
     * Remove all unused files from the CMS library
     *
     * @param int $tidyGenericFiles Flag indicating whether to remove the generic files as well
     * @return bool
     */
    public function tidyLibrary($tidyGenericFiles = 0)
    {
        $this->getLogger()->info('Running tidy library');
        $this->doDelete('/library/tidy' , [
            'tidyGenericFiles' => $tidyGenericFiles
        ]);

        return true;
    }

    /**
     * Download Media from CMS library.
     *
     * @param int $mediaId The media ID to download
     * @param string $type The module type to download
     * @param string $filePath Path to the directory where files should be saved
     * @param string $fileName Name of the saved media file
     * @return boolean
     */
    public function download($mediaId, $type, $filePath, $fileName)
    {
        $this->mediaId = $mediaId;
        $this->mediaType = $type;

        $this->getLogger()->info('Downloading media ID ' . $mediaId . ' and saving it in ' . $filePath . ' as ' . $fileName);
        $response = $this->doGet('/library/download/' . $this->mediaId . '/' . $this->mediaType);

        // Check if the directory exists, if not create it
        $this->getLogger()->debug('Checking if the directory exists');
        if (!file_exists($filePath)) {
            $this->getLogger()->debug('Creating directory to save files to ' . $filePath);
            mkdir($filePath, 0777, true);
        }
        // Save file to the directory with the provided name
        $file = fopen($filePath . $fileName, 'w');
        fwrite($file, $response);
        fclose($file);


        return true;
    }

    /**
     * Get Media Usage for Displays
     *
     * @param int $mediaId Media ID
     * @return XiboLibrary
     */
    public function getUsage($mediaId)
    {
        $this->mediaId = $mediaId;

        $this->getLogger()->info('Getting media usage report ' . $mediaId . ' for Displays');
        $response = $this->doGet('/library/usage/' . $mediaId );

        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
     * Get Media Usage for layout
     *
     * @param int $mediaId Media ID
     * @return XiboLibrary
     */
    public function getUsageLayout($mediaId)
    {
        $this->mediaId = $mediaId;

        $this->getLogger()->info('Getting media usage report ' . $mediaId . ' for Layouts');
        $response = $this->doGet('/library/usage/layouts/' . $mediaId );

        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }
}
