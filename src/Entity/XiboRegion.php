<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboRegion.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboRegion extends XiboEntity 
{
	private $url = '/region';
	public $regionId;
  	public $layoutId;
	public $ownerId;
	public $name;
	public $width;
	public $height;
	public $top;
	public $left;
	public $zIndex;

    public $playlists;

	/**
     * Create Region
     * @param $layoutId
     * @param $width
     * @param $height
     * @param $top
     * @param $left
     * @return XiboRegion
     */

    public function create($layoutId, $width, $height, $top, $left)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->width = $width;
        $this->height = $height;
        $this->top = $top;
        $this->left = $left; 
        $this->layoutId = $layoutId;

        // Array response from CMS
        $response = $this->doPost('/region/' . $this->layoutId, $this->toArray());

        // Hydrate the Region object
        $region = $this->hydrate($response);

        // Response Array from the CMS will contain a playlists entry, which holds the attributes for 
        // each Playlist.
        foreach ($response['playlists'] as $item) {
            $playlist = new XiboPlaylist($this->getEntityProvider());
            
            // Hydrate the Playlist object with the items from region->playlists
            $playlist->hydrate($item);

            // Add to parent object
            $region->playlists[] = $playlist;
        }
        
        return $region;
    }

    /**
     * Edit Region
     * @param $width
     * @param $height
     * @param $top
     * @param $left
     * @param $zindex
     * @param $loop
     * @return XiboRegion
     */

    public function edit($width, $height, $top, $left, $zindex, $loop)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->width = $width;
        $this->height = $height;
        $this->top = $top;
        $this->left = $left; 
        $this->zIndex = $zindex;
        $this->loop = $loop;

        $response = $this->doPut('/region/' . $this->regionId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Delete Region
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/region/' . $this->regionId);
        
        return true;
    }

}