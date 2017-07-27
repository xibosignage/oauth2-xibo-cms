<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDisplayGroup.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

/**
 * Class XiboDisplayGroup
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboDisplayGroup extends XiboEntity
{
    public $displayGroupId;
    public $displayGroup;
    public $description;
    public $isDisplaySpecific = 0;
    public $isDynamic = 0;
    public $dynamicCriteria;
    public $userId = 0;

    /**
     * @param array $params
     * @return array[XiboDisplayGroup]
     */
    public function get(array $params = [])
    {
        $entries = [];
        $response = $this->doGet('/displaygroup', $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboDisplayGroup
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet('/displaygroup', [
            'displayGroupId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single display group, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create
     * @param $displayGroup
     * @param $description
     * @param $isDynamic
     * @param $dynamicCriteria
     * @return XiboDisplayGroup
     */
    public function create($displayGroup, $description, $isDynamic, $dynamicCriteria)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->displayGroup = $displayGroup;
        $this->description = $description;
        $this->isDynamic = $isDynamic;
        $this->dynamicCriteria = $dynamicCriteria;

        $response = $this->doPost('/displaygroup', $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $displayGroup
     * @param $description
     * @param $isDynamic
     * @param $dynamicCriteria
     * @return XiboDisplayGroup
     */
    public function edit($displayGroup, $description, $isDynamic, $dynamicCriteria)
    {
        $this->displayGroup = $displayGroup;
        $this->description = $description;
        $this->isDynamic = $isDynamic;
        $this->dynamicCriteria = $dynamicCriteria;

        $response = $this->doPut('/displaygroup/' . $this->displayGroupId, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/displaygroup/' . $this->displayGroupId);

        return true;
    }
    
    /**
     * Assign display
     * @param $displayId
     * @return XiboDisplayGroup
     */
    public function assignDisplay($displayId)
    {

        $response = $this->doPost('/displaygroup/' . $this->displayGroupId . '/display/assign', [
            'displayId' => $displayId
            ]);

        return $this;
    }

    /**
     * Assign display group
     * @param int $displayGroupId
     * @return XiboDisplayGroup
     */
    public function assignDisplayGroup($displayGroupId)
    {

        $response = $this->doPost('/displaygroup/' . $this->displayGroupId . '/displayGroup/assign', [
        'displayGroupId' => $displayGroupId
        ]);
        return $this;
    }

    /**
     * Assign layout
     * @param $layoutId
     * @return XiboDisplayGroup
     */
    public function assignLayout($layoutId)
    {

        $response = $this->doPost('/displaygroup/' . $this->displayGroupId . '/layout/assign', [
            'layoutId' => $layoutId
            ]);

        return $this;
    }

    /**
     * Assign media
     * @param $mediaId
     * @return XiboDisplayGroup
     */
    public function assignMedia($mediaId)
    {

        $response = $this->doPost('/displaygroup/' . $this->displayGroupId . '/media/assign', [
            'mediaId' => $mediaId
            ]);

        return $this;
    }
}
