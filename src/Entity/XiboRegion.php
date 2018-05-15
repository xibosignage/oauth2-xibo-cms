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
    public $loop;
    public $transitionType;
    public $transitionDuration;
    public $transitionDirection;
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
        $this->getLogger()->info('Creating Region in Layout Id ' . $this->layoutId);
        // Array response from CMS
        $response = $this->doPost('/region/' . $this->layoutId, $this->toArray());
        // Hydrate the Region object
        $this->getLogger()->debug('Passing the response to Hydrate');
        $region = $this->hydrate($response);

        $this->getLogger()->debug('Creating child object Playlist and linking it to parent Region');
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
     * @param $zIndex
     * @param $loop
     * @param string $transitionType
     * @param $transitionDuration
     * @param string $transitionDirection
     * @return XiboRegion
     */

    public function edit($width, $height, $top, $left, $zIndex, $loop, $transitionType = '', $transitionDuration = null, $transitionDirection = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->width = $width;
        $this->height = $height;
        $this->top = $top;
        $this->left = $left; 
        $this->zIndex = $zIndex;
        $this->loop = $loop;

        $this->getLogger()->info('Editing Region ID ' . $this->regionId . ' In Layout Id ' . $this->layoutId);
        $response = $this->doPut('/region/' . $this->regionId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Delete Region
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting Region ID ' . $this->regionId  . ' From Layout Id ' . $this->layoutId);
        $this->doDelete('/region/' . $this->regionId);
        
        return true;
    }

}