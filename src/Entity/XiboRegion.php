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

class XiboRegion extends XiboEntity 
{
	private $url = '/region';

    /** @var int The Region ID */
	public $regionId;

    /** @var int The Layout ID */
  	public $layoutId;

    /** @var int The Owner ID */
	public $ownerId;

    /** @var int Region width */
	public $width;

    /** @var int Region height */
	public $height;

    /** @var int Region top offset */
	public $top;

    /** @var int Region left offset */
	public $left;

    /** @var int Region z-index */
	public $zIndex;

    /** @var int Flag indicating whether this region should loop if there is only 1 media item in the timeline */
    public $loop;

    /** @var string The Transition Type */
    public $transitionType;

    /** @var int The transition duration in milliseconds if required by the transition type */
    public $transitionDuration;

    /** @var string The transition direction if required by the transition type. */
    public $transitionDirection;

    /** @var XiboPlaylist The region playlist */
    public $regionPlaylist;

	/**
     * Create Region.
     *
     * @param int $layoutId Layout ID to which add the region
     * @param int $width Width value for the region
     * @param int $height Height value for the region
     * @param int $top Top offset value for the region
     * @param int $left Left offset value for the region
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
        $this->getLogger()->debug('Passing the response to Hydrate');
        /** @var XiboRegion $region */
        $region = $this->hydrate($response);

        $this->getLogger()->debug('Creating child object Playlist and linking it to regionPlaylist');
        /** @var XiboPlaylist $playlist */
        $playlist = new XiboPlaylist($this->getEntityProvider());
        $playlist->hydrate($response['regionPlaylist']);
        $region->regionPlaylist = $playlist;
        
        return $region;
    }

    /**
     * Edit Region.
     *
     * @param int $width New width value for the region
     * @param int $height New height value for the region
     * @param int $top New top offset value for the region
     * @param int $left New left offset value for the region
     * @param int $zIndex zIndex layer
     * @param int $loop Flag (0,1) to loop the region or not
     * @param string $transitionType Transition Type must be a valid transition code returned by /transition
     * @param int $transitionDuration Transition Duration in milliseconds if required by the transition type
     * @param string $transitionDirection Transition Direction if required by the transition type
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
        $this->transitionType = $transitionType;
        $this->transitionDuration = $transitionDuration;
        $this->transitionDirection = $transitionDirection;

        $this->getLogger()->info('Editing Region ID ' . $this->regionId . ' In Layout Id ' . $this->layoutId);
        $response = $this->doPut('/region/' . $this->regionId, $this->toArray());
        /** @var XiboRegion $region */
        $region = $this->hydrate($response);

        $this->getLogger()->debug('Creating child object Playlist and linking it to regionPlaylist');
        /** @var XiboPlaylist $playlist */
        $playlist = new XiboPlaylist($this->getEntityProvider());
        $playlist->hydrate($response['regionPlaylist']);
        $region->regionPlaylist = $playlist;

        return $region;
    }

    /**
     * Delete Region.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting Region ID ' . $this->regionId  . ' From Layout Id ' . $this->layoutId);
        $this->doDelete('/region/' . $this->regionId);
        
        return true;
    }

    /**
     * Position regions.
     *
     * Changes region positions with specified values
     * @param int $layoutId Layout Id in which we want to position the regions
     * @param array $regionId array of regionIds to reposition
     * @param array $width array of width values for the regions
     * @param array $height array of height values for the regions
     * @param array $top array of top offset values for the regions
     * @param array $left array of left offset values for the regions
     * @return XiboRegion
     */

    public function positionAll($layoutId, $regionId = [], $width = [], $height = [], $top = [], $left = [])
    {
        $this->layoutId = $layoutId;
        $this->width = $width;
        $this->height = $height;
        $this->top = $top;
        $this->left = $left;

        for ($i=0; $i < count($regionId); $i++) {
            $regionJson = json_encode([
                [
                    'regionid' => $regionId[$i],
                    'width' => $width[$i],
                    'height' => $height[$i],
                    'top' => $top[$i],
                    'left' => $left[$i]
                ]
            ]);

            $this->getLogger()->debug('Positioning Region ID ' . $regionId[$i] . ' new dimensions are width ' . $width[$i] . ' height ' . $height[$i] . ' top ' . $top[$i] . ' left ' . $left[$i]);
            $response = $this->doPut('/region/position/all/' . $layoutId, [
                'regions' => $regionJson
            ]);
        }

        $this->getLogger()->info('Positioning Regions in Layout ID ' . $layoutId);
        /** @var XiboRegion $region */
        $region = $this->hydrate($response);

        $this->getLogger()->debug('Creating child object Playlist and linking it to regionPlaylist');
        /** @var XiboPlaylist $playlist */
        $playlist = new XiboPlaylist($this->getEntityProvider());
        $playlist->hydrate($response['regionPlaylist']);
        $region->regionPlaylist = $playlist;

        return $region;
    }

}