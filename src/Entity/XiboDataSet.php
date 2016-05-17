<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDataSet.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboDataSet extends XiboEntity
{

	private $url = '/dataset';

    public $dataSetId;
    public $dataSet;
    public $description;
    public $userId;
    public $lastDataEdit;
    public $owner;
    public $groupsWithPermissions;
    public $code;
    public $isLookup;

	/**
     * @param array $params
     * @return array|XiboDataSet
     */
    public function get(array $params = [])
    {
        $entries = [];
        $response = $this->doGet($this->url, $params);

        foreach ($response as $item) {
            $entries[] = $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * @param $id
     * @return XiboDataSet
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet($this->url, [
            'dataSetId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return $this->hydrate($response[0]);
    }

    /**
     * Create
     * @param $dataSetName
     * @param $dataSetDescription
     */
    public function create($dataSetName, $dataSetDescription)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->dataSet = $dataSetName;
        $this->description = $dataSetDescription;
        $response = $this->doPost('/dataset', $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $dataSetName
     * @param $dataSetDescription
     */
    public function edit($dataSetName, $dataSetDescription)
    {
        $this->dataSet = $dataSetName;
        $this->description = $dataSetDescription;
        $response = $this->doPut('/dataset/' . $this->dataSetId, $this->toArray());
        
        return $this->hydrate($response);
    }


    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/dataset/' . $this->dataSetId);
        
        return true;
    }
}
