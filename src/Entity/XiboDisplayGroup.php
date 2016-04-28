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
     * @return array[XiboDisplayGroup]
     */
    public function get()
    {
        $entries = [];
        $response = $this->doGet('/displaygroup');

        foreach ($response as $item) {
            $entries[] = $this->hydrate($item);
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
     * @return XiboEntity
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
     * @param $displayGroupId
     * @param $groupName
     * @param $groupDescription
     * @param $isDynamic
     * @param $dynamicCriteria
     * @return XiboEntity
     */
    public function edit($displayGroupId, $groupName, $groupDescription, $isDynamic, $dynamicCriteria)
    {
        $this->displayGroup = $groupName;
        $this->description = $groupDescription;
        $this->isDynamic = $isDynamic;
        $this->dynamicCriteria = $dynamicCriteria;

        $response = $this->doPut('/displaygroup/' . $displayGroupId, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Delete
     * @param $displayGroupId
     * @return bool
     */
    public function delete($displayGroupId)
    {
        $this->doDelete('/displaygroup/' . $displayGroupId);

        return true;
    }
}