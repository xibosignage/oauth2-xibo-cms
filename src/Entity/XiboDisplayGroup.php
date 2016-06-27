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

        return $this->hydrate($response[0]);
    }

    /**
     * Create
     * @param $groupName
     * @param $groupDescription
     * @param $isDynamic
     * @param $dynamicCriteria
     * @return XiboDisplayGroup
     */
    public function create($groupName, $groupDescription, $isDynamic, $dynamicCriteria)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->displayGroup = $groupName;
        $this->description = $groupDescription;
        $this->isDynamic = $isDynamic;
        $this->dynamicCriteria = $dynamicCriteria;

        $response = $this->doPost('/displaygroup', $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $groupName
     * @param $groupDescription
     * @param $isDynamic
     * @param $dynamicCriteria
     * @return XiboDisplayGroup
     */
    public function edit($groupName, $groupDescription, $isDynamic, $dynamicCriteria)
    {
        $this->displayGroup = $groupName;
        $this->description = $groupDescription;
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
     * @param $groupDisplay
     * @return XiboDisplayGroup
     */
    public function assignDisplay($groupDisplay)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();

        $response = $this->doPost('/displaygroup/' . $this->displayGroupId . '/display/assign', [
                            'displayId' => $groupDisplay
                             ], $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Assign display group
     * @param $groupDisplayGroup
     * @return XiboDisplayGroup
     */
    public function assignDisplayGroup($groupDisplayGroup)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();

        $response = $this->doPost('/displaygroup/' . $this->displayGroupId . '/displayGroup/assign', [
        'displayGroupId' => $groupDisplayGroup
        ], $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Assign layout
     * @param $groupLayout
     * @return XiboDisplayGroup
     */
    public function assignLayout($groupLayout)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();

        $response = $this->doPost('/displaygroup/' . $this->displayGroupId . '/layout/assign', [
            'layoutId' => $groupLayout
            ], $this->toArray());

        return $this->hydrate($response);
    }
}
