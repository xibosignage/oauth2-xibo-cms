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

	/**
     * Create Region
     * @param $layoutId
     * @param $regionWidth
     * @param $regionHeight
     * @param $regionTop
     * @param $regionLeft
     * @return XiboRegion
     */

    public function create($regionLayoutId, $regionWidth, $regionHeight, $regionTop, $regionLeft)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->width = $regionWidth;
        $this->height = $regionHeight;
        $this->top = $regionTop;
        $this->left = $regionLeft; 
        $this->layoutId = $regionLayoutId;

        $response = $this->doPost('/region/' . $this->layoutId, $this->toArray());
        
        return $this->hydrate($response);

    }

    /**
     * Edit Region
     * @param $regionWidth
     * @param $regionHeight
     * @param $regionTop
     * @param $regionLeft
     * @param $regionzIndex
     * @param $regionLoop
     * @return XiboRegion
     */

    public function edit($regionWidth, $regionHeight, $regionTop, $regionLeft, $regionzIndex, $regionLoop)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->width = $regionWidth;
        $this->height = $regionHeight;
        $this->top = $regionTop;
        $this->left = $regionLeft; 
        $this->zIndex = $regionzIndex;
        $this->loop = $regionLoop;

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