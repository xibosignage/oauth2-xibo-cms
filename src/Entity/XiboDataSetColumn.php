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

    public $dataSetColumnId;
    public $dataSetId;
    public $heading;
    public $dataTypeId;
    public $dataSetColumnTypeId;
    public $listContent;
    public $columnOrder;
    public $formula;
    public $dataType;
    public $remoteField;
    public $showFilter;
    public $showSort;
    public $dataSetColumnType;

    /**
     * @param $dataSetId
     * @param array $params
     * @return array|XiboDataSetColumn
     */
    public function get($dataSetId, array $params = [])
    {
        $entries = [];
        $this->dataSetId = $dataSetId;
        $this->getLogger()->info('Getting list of columns from dataSet ID ' . $dataSetId);
        $response = $this->doGet('/dataset/' . $dataSetId . '/column', $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * @param $dataSetId
     * @param $id
     * @return XiboDataSetColumn
     * @throws XiboApiException
     */
    public function getById($dataSetId, $id)
    {
        $this->dataSetId = $dataSetId;
        $this->getLogger()->info('Getting column ID ' . $id . ' From dataSet ID ' . $dataSetId);
        $response = $this->doGet('/dataset/'. $dataSetId .'/column' , [
            'dataSetColumnId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

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
        $this->getLogger()->info('Creating a new column ' . $heading . ' In dataSet ID ' . $this->dataSetId);
        $response = $this->doPost('/dataset/'. $this->dataSetId . '/column', $this->toArray());
        
        return $this->hydrate($response);
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
        $this->getLogger()->info('Editing column ID ' . $this->dataSetColumnId . ' In dataSet ID ' . $this->dataSetId);
        $response = $this->doPut('/dataset/'. $this->dataSetId . '/column/' . $this->dataSetColumnId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Delete Column
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting column ID ' . $this->dataSetColumnId . ' From dataSet ID ' . $this->dataSetId);
        $this->doDelete('/dataset/' . $this->dataSetId . '/column/' . $this->dataSetColumnId);
        
        return true;
    }
 }   