<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboLayout.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboLayout extends XiboEntity
{
    private $url = '/layout';

    public $layoutId;
    public $ownerId;
    public $campaignId;
    public $backgroundImageId;
    public $schemaVersion;
    public $layout;
    public $description;
    public $backgroundColor;
    public $createdDt;
    public $modifiedDt;
    public $status;
    public $retired;
    public $backgroundzIndex;
    public $width;
    public $height;
    public $displayOrder;
    public $duration;
    public $tags;

    /**
     * @param array $params
     * @return array|XiboLayout
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
     * @return XiboLayout
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet($this->url, [
            'layoutId' => $id, 'retired' => -1
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create
     * @param $name
     * @param $description
     * @param $layoutId
     * @param $resolutionId
     */
    public function create($name, $description, $layoutId, $resolutionId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->description = $description;
        $this->layoutId = $layoutId;
        $this->resolutionId = $resolutionId;
        $response = $this->doPost('/layout', $this->toArray());
        
        $layout = $this->hydrate($response);
        
        foreach ($response['regions'] as $item) {
            $region = new XiboRegion($this->getEntityProvider());
            $region->hydrate($item);
            // Add to parent object
            $layout->regions[] = $region;
        }
        
        return $layout;
    }

    /**
     * Edit
     * @param $name;
     * @param $description;
     * @param $tags;
     * @param $retired;
     * @param $backgroundColor;
     * @param $backgroundImageId;
     * @param $backgroundzIndex;
     * @param $resolutionId;
     * @return XiboLayout
     */
    public function edit($name, $description, $tags, $retired, $backgroundColor,$backgroundImageId, $backgroundzIndex, $resolutionId)
    {
        $this->name = $name;
        $this->description = $description;
        $this->tags = $tags;
        $this->retired = $retired;
        $this->backgroundColor = $backgroundColor;
        $this->backgroundImageId = $backgroundImageId;
        $this->backgroundzIndex = $backgroundzIndex;
        $this->resolutionId = $resolutionId;
        $response = $this->doPut('/layout/' . $this->layoutId, $this->toArray());
        
        return $this->hydrate($response);
    }


    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/layout/' . $this->layoutId);
        
        return true;
    }


    /**
     * Copy
     * @param $name
     * @param $description
     * @param $copyMediaFiles
     */
    public function copy($name, $description, $copyMediaFiles)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->description = $description;
        $this->copyMediaFiles = $copyMediaFiles;
        $response = $this->doPost('/layout/copy/' . $this->layoutId, $this->toArray());
        
        return $this->hydrate($response);
    }


    /**
     * Create Region
     */

    public function createRegion($width, $height, $top, $left)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->width = $width;
        $this->height = $height;
        $this->top = $top;
        $this->left = $left; 

        $response = $this->doPost('/region/' . $this->layoutId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Edit Region
     */

    public function editRegion($width, $height, $top, $left, $zIndex, $loop)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->width = $width;
        $this->height = $height;
        $this->top = $top;
        $this->left = $left; 
        $this->zIndex = $zIndex;
        $this->loop = $loop;

        $response = $this->doPut('/region/' . $this->regionId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Delete Region
     * @return bool
     */
    public function deleteRegion()
    {
        $this->doDelete('/region/' . $this->regionId);
        
        return true;
    }

    /**
     * @param $id
     * @return XiboLayout
     * @throws XiboApiException
     */
    public function getByTemplateId($id)
    {
        $response = $this->doGet('/template', [
            'templateId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return $this->hydrate($response[0]);
    }

    /**
     * Add tag
     * @param $tags
     * @return XiboLayout
     */
    public function addTag($tags)
    {
        $this->tag = $tags;
        $response = $this->doPost('/layout/' . $this->layoutId . '/tag', [
            'tag' => [$tags]
            ]);

        $tags = $this->hydrate($response);
        foreach ($response['tags'] as $item) {
            $tag = new XiboLayout($this->getEntityProvider());
            $tag->hydrate($item);
            $tags->tags[] = $tag;
        }
        return $this;
    }
}
