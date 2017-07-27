<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDataSetColumn.php)
 */

namespace Xibo\OAuth2\Client\Entity;

use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboDataSetColumn extends XiboEntity
{

    private $url = '/dataset';

    public $dataSetId;
    public $dataSetColumnId;
    public $rowId;
    public $dataSet;
    public $description;
    public $userId;
    public $lastDataEdit;
    public $owner;
    public $groupsWithPermissions;
    public $code;
    public $isLookup;
    public $heading;
    public $listContent;
    public $columnOrder;
    public $dataTypeId;
    public $dataSetColumnTypeId;
    public $formula;
    public $dataType;
    public $dataSetColumnType;
    public $dataSetColumnId_ID;

     /**
     * Create Column
     * @param $heading
     * @param $listContent
     * @param $columnOrder
     * @param $dataTypeId
     * @param $dataSetColumnTypeId
     * @param $formula
     * @param $dataSetId
     * @return XiboDataSetColumn
     */
    public function create($dataSetId, $heading, $listContent, $columnOrder, $dataTypeId, $dataSetColumnTypeId, $formula)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->heading = $heading;
        $this->listContent = $listContent;
        $this->columnOrder = $columnOrder;
        $this->dataTypeId = $dataTypeId;
        $this->dataSetColumnTypeId = $dataSetColumnTypeId;
        $this->formula = $formula;
        $this->dataSetId = $dataSetId;
        $response = $this->doPost('/dataset/'. $this->dataSetId . '/column', $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * @param $id
     * @return XiboDataSetColumn
     * @throws XiboApiException
     */
    public function getById($dataSetId, $id)
    {
        $this->dataSetId = $dataSetId;
        $response = $this->doGet('/dataset/'. $this->dataSetId .'/column' , [
            'dataSetColumnId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Edit Column
     * @param $heading
     * @param $listContent
     * @param $columnOrder
     * @param $dataTypeId
     * @param $dataSetColumnTypeId
     * @param $formula
     * @return XiboDataSetColumn
     */
    public function edit($heading, $listContent, $columnOrder, $dataTypeId, $dataSetColumnTypeId, $formula)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->heading = $heading;
        $this->listContent = $listContent;
        $this->columnOrder = $columnOrder;
        $this->dataTypeId = $dataTypeId;
        $this->dataSetColumnTypeId = $dataSetColumnTypeId;
        $this->formula = $formula;
        $response = $this->doPut('/dataset/'. $this->dataSetId . '/column/' . $this->dataSetColumnId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Delete Column
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/dataset/' . $this->dataSetId . '/column/' . $this->dataSetColumnId);
        
        return true;
    }
 }   