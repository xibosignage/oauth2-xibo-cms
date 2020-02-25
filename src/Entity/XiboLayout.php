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


use Xibo\OAuth2\Client\Exception\InvalidArgumentException;
use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboLayout extends XiboEntity
{
    private $url = '/layout';

    /** @var int the Layout ID */
    public $layoutId;

    /** @var int the Owner ID */
    public $ownerId;

    /** @var int the Campaign ID */
    public $campaignId;

    /** @var int the Parent ID */
    public $parentId;

    /** @var int Publised Status ID */
    public $publishedStatusId;

    /** @var string Publish Status Message*/
    public $publishedStatus;

    /** @var int A media ID to use as the background image for this Layout. */
    public $backgroundImageId;

    /** @var int Schema version ID */
    public $schemaVersion;

    /** @var string The Layout name */
    public $layout;

    /** @var string The layout description */
    public $description;

    /** @var string A HEX color to use as the background color of this Layout. */
    public $backgroundColor;

    /** @var string Date showing when layout was created */
    public $createdDt;

    /** @var string Date showing when layout was modified */
    public $modifiedDt;

    /** @var int Layout status */
    public $status;

    /** @var int Flag indicating whether the layout is retired */
    public $retired;

    /** @var int The Layer Number to use for the background. */
    public $backgroundzIndex;

    /** @var int Resolution ID for this layout */
    public $resolutionId;

    /** @var int Layout width */
    public $width;

    /** @var int Layout height */
    public $height;

    /** @var int Layout display Order */
    public $displayOrder;

    /** @var int Layout duration */
    public $duration;

    /** @var string Layout status message */
    public $statusMessage;

    /** @var int Flag indicating whether the Layout stat is enabled */
    public $enableStat;

    /** @var array|XiboRegion An Array of Layout regions */
    public $regions;

    /** @var array Array of Layout tags */
    public $tags;

    /** @var int The template ID */
    public $templateId;

    /** @var array Array of permissions to the layout */
    public $permissions;

    /** @var int flag indicating whether to treat the tags filter as an exact match */
    public $exactTags;

    /** @var int User Group ID */
    public $ownerUserGroupId;

    /** @var int Flag indicating whether to make new Copies of all Media Files assigned to the Layout being Copied */
    public $copyMediaFiles;

    /** @var int Flag indicating whether to include widgets in the template */
    public $includeWidgets;

    /**
     * Get an array of layouts.
     *
     * @param array $params can be filtered by: layoutId, layout, userId, retired, tags, exactTags, ownerUserGroupId and embeddable parameter embed=regions, playlists, widgets, tags
     * @return array|XiboLayout
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting list of layouts ');
        $entries = [];
        $hydratedRegions = [];
        $hydratedWidgets = [];
        $response = $this->doGet($this->url, $params);
        if (isset($params['embed']))
            $embed = ($params['embed'] != null) ? explode(',', $params['embed']) : [];

        foreach ($response as $item) {
            /** @var XiboLayout $layout */
            $layout = new XiboLayout($this->getEntityProvider());
            $layout->hydrate($item);
            foreach ($layout->regions as $reg) {
                /** @var XiboRegion $region */
                $region = new XiboRegion($this->getEntityProvider());
                $region->hydrate($reg);
                if (in_array('playlists', $embed) === true) {
                    /** @var XiboPlaylist $playlist */
                    $playlist = new XiboPlaylist($this->getEntityProvider());
                    $playlist->hydrate($reg['regionPlaylist']);
                    foreach ($playlist->widgets as $widget) {
                        /** @var XiboWidget $widgetObject */
                        $widgetObject = new XiboWidget($this->getEntityProvider());
                        $widgetObject->hydrate($widget);
                        $hydratedWidgets[] = $widgetObject;
                    }
                    $playlist->widgets = $hydratedWidgets;
                    $region->regionPlaylist = $playlist;
                }
                // Add to parent object
                $hydratedRegions[] = $region;
            }
            $layout->regions = $hydratedRegions;

            $entries[] = clone $layout;
        }

        return $entries;
    }

    /**
     * Get the layout by its ID.
     *
     * @param int $id LayoutId to search for
     * @param string $embed A comma separated list of related data tp embed such as regions, playlists, widgets, tags, etc
     * @return XiboLayout
     * @throws XiboApiException
     */
    public function getById($id, $embed = '')
    {
        $this->getLogger()->info('Getting layout ID ' . $id);
        $hydratedRegions = [];
        $hydratedWidgets = [];
        $response = $this->doGet($this->url, [
            'layoutId' => $id, 'retired' => -1, 'embed' => $embed
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        /** @var XiboLayout $layout */
        $layout = $this->hydrate($response[0]);

        foreach ($layout->regions as $item) {
            /** @var XiboRegion $region */
            $region = new XiboRegion($this->getEntityProvider());
            $region->hydrate($item);
            if (strpos($embed, 'playlists') !== false) {
                /** @var XiboPlaylist $playlist */
                $playlist = new XiboPlaylist($this->getEntityProvider());
                $playlist->hydrate($item['regionPlaylist']);
                foreach ($playlist->widgets as $widget) {
                    /** @var XiboWidget $widgetObject */
                    $widgetObject = new XiboWidget($this->getEntityProvider());
                    $widgetObject->hydrate($widget);
                    $hydratedWidgets[] = $widgetObject;
                }
                $playlist->widgets = $hydratedWidgets;
                $region->regionPlaylist = $playlist;
            }
            // Add to parent object
            $hydratedRegions[] = $region;
        }
        $layout->regions = $hydratedRegions;

        return clone $layout;
    }

    /**
     * Create a new layout.
     * Creates new layout with the specified parameters
     *
     * @param string $name Name of the layout
     * @param string $description Optional description of the layout
     * @param int $layoutId If layout should be created from the template, provide the layoutId of the template
     * @param int $resolutionId If template is not provided, provide resolutionId
     * @param int $enableStat Flag indicating whether the Layout stat is enabled
     * @return XiboLayout
     */
    public function create($name, $description, $layoutId, $resolutionId, $enableStat = null)
    {
        $this->getLogger()->debug('Getting Resource Owner');
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->description = $description;
        $this->layoutId = $layoutId;
        $this->resolutionId = $resolutionId;
        $this->enableStat = $enableStat;

        $this->getLogger()->info('Creating Layout ' . $this->layout);
        $response = $this->doPost('/layout', $this->toArray());

        $layout = $this->constructLayoutFromResponse($response);

        return $layout;
    }

    /**
     * Edit an existing Layout.
     *
     * @param string $name new name of the layout
     * @param string $description new description of the layout
     * @param string $tags comma separated list of tags
     * @param int $retired flag indicating whether this layout is retired
     * @param string $backgroundColor A HEX color to use as layout background
     * @param int $backgroundImageId Media ID to use as a layout background
     * @param int $backgroundzIndex The layer number to use for the background
     * @param int $resolutionId new Resolution to use for this layout
     * @param int $enableStat Flag indicating whether the Layout stat is enabled
     * @return XiboLayout
     */
    public function edit($name, $description, $tags, $retired, $backgroundColor, $backgroundImageId, $backgroundzIndex, $resolutionId, $enableStat = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->tags = $tags;
        $this->retired = $retired;
        $this->backgroundColor = $backgroundColor;
        $this->backgroundImageId = $backgroundImageId;
        $this->backgroundzIndex = $backgroundzIndex;
        $this->resolutionId = $resolutionId;
        $this->enableStat = $enableStat;

        $this->getLogger()->info('Editing Layout ID ' . $this->layoutId);
        $response = $this->doPut('/layout/' . $this->layoutId, $this->toArray());

        $layout = $this->constructLayoutFromResponse($response);

        return $layout;
    }


    /**
     * Delete the layout.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting Layout ID ' . $this->layoutId);
        $this->doDelete('/layout/' . $this->layoutId);
        
        return true;
    }

    /**
     * Retire the specified layout.
     *
     * @return bool
     */

    public function retire()
    {
        $this->getLogger()->info('Retiring layout ID ' . $this->layoutId);
        $this->doPut('/layout/retire/' . $this->layoutId);

        return true;
    }

    /**
     * Copy the layout.
     *
     * @param string $name Name of the copied layout
     * @param string $description Description of the copied layout
     * @param int $copyMediaFiles Flag indicating whether to make copies of all media files assigned to the layout being copied
     * @return XiboLayout
     */
    public function copy($name, $description, $copyMediaFiles)
    {
        $this->name = $name;
        $this->description = $description;
        $this->copyMediaFiles = $copyMediaFiles;
        $this->getLogger()->info('Copy Layout ID ' . $this->layoutId);
        $response = $this->doPost('/layout/copy/' . $this->layoutId, $this->toArray());
        
        $this->getLogger()->debug('Passing the response to Hydrate');
        $layout = $this->constructLayoutFromResponse($response);

        return $layout;
    }


    /**
     * Create Region.
     *
     * @param int $width Width of the region
     * @param int $height Height of the region
     * @param int $top Offset from top
     * @param int $left Offset from left
     * @return XiboRegion
     */

    public function createRegion($width, $height, $top, $left)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->width = $width;
        $this->height = $height;
        $this->top = $top;
        $this->left = $left; 

        $response = $this->doPost('/region/' . $this->layoutId, $this->toArray());
        $this->getLogger()->info('Creating Region ' . 'In Layout ID ' . $this->layoutId);

        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
     * Edit Region.
     *
     * @param int $regionId Region ID to Edit
     * @param int $width New width for the region
     * @param int $height New height for the region
     * @param int $top new offset from Top
     * @param int $left new offset from Left
     * @param int $zIndex The layer of the region
     * @param int $loop Flag indicating whether this region should loop if there is only one widget in the timeline
     * @return XiboRegion
     */

    public function editRegion($regionId, $width, $height, $top, $left, $zIndex, $loop)
    {
        $this->width = $width;
        $this->height = $height;
        $this->top = $top;
        $this->left = $left; 
        $this->zIndex = $zIndex;
        $this->loop = $loop;
        $this->getLogger()->info('Editing Region ID ' . $regionId . 'In Layout ID ' . $this->layoutId);
        $response = $this->doPut('/region/' . $regionId, $this->toArray());
        
        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
     * Delete Region.
     *
     * @param int $regionId Region ID to delete
     * @return bool
     */
    public function deleteRegion($regionId)
    {
        $this->getLogger()->info('Deleting Region ID ' . $regionId . 'In Layout ID ' . $this->layoutId);
        $this->doDelete('/region/' . $regionId);
        
        return true;
    }

    /**
     * Position regions.
     *
     * Changes region positions with specified values
     * @param array $regionId array of regionIds to reposition
     * @param array $width array of width values for the regions
     * @param array $height array of height values for the regions
     * @param array $top array of top offset values for the regions
     * @param array $left array of left offset values for the regions
     * @return XiboLayout
     */

    public function positionAll($regionId = [], $width = [], $height = [], $top = [], $left = [])
    {
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
            $response = $this->doPut('/region/position/all/' . $this->layoutId, [
                'regions' => $regionJson
            ]);
        }

        $this->getLogger()->info('Positioning Regions in Layout ID ' . $this->layoutId);
        return $this->hydrate($response);
    }

    /**
     * Get a list of templates.
     *
     * @param array $params
     * @return array|XiboLayout
     */
    public function getTemplate(array $params = [])
    {
        $this->getLogger()->info('Getting list of templates ');
        $entries = [];
        $response = $this->doGet('/template', $params);
        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Get the template by ID
     *
     * @param int $id template ID
     * @return XiboLayout
     * @throws XiboApiException
     */
    public function getByTemplateId($id)
    {
        $this->getLogger()->info('Getting template ID ' . $this->templateId);
        $response = $this->doGet('/template', [
            'templateId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response[0]);
    }

    /**
     * Create Template from provided layout ID.
     *
     * @param int $layoutId The layout ID to create a template from
     * @param int $includeWidgets Flag indicating whether to include widgets in the template
     * @param string $name name of the template
     * @param string $tags comma separeted list of tags for the template
     * @param string $description description of the template
     * @return XiboLayout
     */
    public function createTemplate($layoutId, $includeWidgets, $name, $tags, $description)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->layoutId = $layoutId;
        $this->includeWidgets = $includeWidgets;
        $this->name = $name;
        $this->tags = $tags;
        $this->description = $description;
        $this->getLogger()->info('Creating Template ' . $name . ' from layout ID ' . $layoutId);
        $response = $this->doPost('/template/' . $layoutId, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Add tag.
     * Adds specified tag to the specified layout
     *
     * @param string $tag name of the tag to add
     * @return XiboLayout
     */
    public function addTag($tag)
    {
        $this->tag = $tag;
        $this->getLogger()->info('Adding tag: ' . $tag . ' to layout ID ' . $this->layoutId);
        $response = $this->doPost('/layout/' . $this->layoutId . '/tag', [
            'tag' => [$tag]
        ]);
        $tags = $this->hydrate($response);
        foreach ($response['tags'] as $item) {
            $tag = new XiboLayout($this->getEntityProvider());
            $tag->hydrate($item);
            $tags->tags[] = $tag;
        }
        return $this;
    }

    /**
     * Remove tag.
     * Removes specified tag from the specified layout
     * @param string $tag name of the taf to remove
     * @return XiboLayout
     */
    public function removeTag($tag)
    {
        $this->tag = $tag;
        $this->getLogger()->info('Removing tag: ' . $tag . ' from layout ID ' . $this->layoutId);
        $this->doPost('/layout/' . $this->layoutId . '/untag', [
            'tag' => [$tag]
        ]);

        return $this;
    }

    /**
     * Get Layout status.
     *
     * @param int layoutId The ID of the layout to get the status
     * @return XiboLayout
     * @throws XiboApiException
     */

    public function getStatus()
    {
        $this->getLogger()->info('Getting status for layout ID ' . $this->layoutId);
        $response = $this->doGet('/layout/status/' . $this->layoutId);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
     * Checkout a layout
     * @param int layoutId The ID of the layout to checkout
     * @return XiboLayout
     */
    public function checkout($layoutId)
    {
        $this->getLogger()->info('Checking out layout ID ' . $layoutId);
        $response = $this->doPut('/layout/checkout/' . $layoutId);

        $layout = $this->constructLayoutFromResponse($response);

        $this->getLogger()->info('LayoutId is now: ' . $layout->layoutId);

        return $layout;
    }

    /**
     * Publish a layout
     * @param int layoutId The ID of the layout to checkout
     * @return XiboLayout
     */
    public function publish($layoutId)
    {
        $this->getLogger()->info('Publishing layout ID ' . $layoutId);
        $response = $this->doPut('/layout/publish/' . $layoutId);

        $layout = $this->constructLayoutFromResponse($response);

        $this->getLogger()->debug('LayoutId is now: ' . $layout->layoutId);

        return $layout;
    }

    /**
     * Discard a layouts draft
     * @param int layoutId The ID of the layout to checkout
     * @return XiboLayout
     */
    public function discard($layoutId)
    {
        $this->getLogger()->info('Discarding draft of layout ID ' . $layoutId);
        $response = $this->doPut('/layout/discard/' . $layoutId);

        $layout = $this->constructLayoutFromResponse($response);

        $this->getLogger()->debug('LayoutId is now: ' . $layout->layoutId);

        return $layout;
    }

    /**
     * @param $response
     * @return \Xibo\OAuth2\Client\Entity\XiboEntity|XiboLayout
     */
    private function constructLayoutFromResponse($response)
    {
        $hydratedRegions = [];
        $hydratedWidgets = [];
        /** @var XiboLayout $layout */
        $layout = new XiboLayout($this->getEntityProvider());
        $layout = $layout->hydrate($response);

        $this->getLogger()->debug('Constructing Layout from Response: ' . $layout->layoutId);

        if (isset($response['regions'])) {
            foreach ($response['regions'] as $item) {
                /** @var XiboRegion $region */
                $region = new XiboRegion($this->getEntityProvider());
                $region->hydrate($item);
                /** @var XiboPlaylist $playlist */
                $playlist = new XiboPlaylist($this->getEntityProvider());
                $playlist->hydrate($item['regionPlaylist']);
                foreach ($playlist->widgets as $widget) {
                    /** @var XiboWidget $widgetObject */
                    $widgetObject = new XiboWidget($this->getEntityProvider());
                    $widgetObject->hydrate($widget);
                    $hydratedWidgets[] = $widgetObject;
                }
                $playlist->widgets = $hydratedWidgets;
                $region->regionPlaylist = $playlist;
                $hydratedRegions[] = $region;
            }
            $layout->regions = $hydratedRegions;
        } else {
            $this->getLogger()->debug('No regions returned with Layout object');
        }

        return $layout;
    }
}