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
    public $isRemote;
    public $method;
    public $uri;
    public $postData;
    public $authentication;
    public $username;
    public $password;
    public $refreshRate;
    public $clearRate;
    public $runsAfter;
    public $lastSync;
    public $dataRoot;
    public $summarize;
    public $summarizeField;
    public $columns;
    public $rowId;


	/**
     * @param array $params
     * @return array|XiboDataSet
     */
    public function get(array $params = [])
    {
        $entries = [];
        $this->getLogger()->info('Getting list of dataSets ');
        $response = $this->doGet($this->url, $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
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
        $this->getLogger()->info('Getting dataSet ID ' . $id);
        $response = $this->doGet($this->url, [
            'dataSetId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create
     * @param $name
     * @param $description
     * @return XiboDataSet
     */
    public function create($name, $description)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->dataSet = $name;
        $this->description = $description;
        $this->getLogger()->info('Creating dataSet ' . $name);
        $response = $this->doPost('/dataset', $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $description
     * @return XiboDataSet
     */
    public function edit($name, $description)
    {
        $this->dataSet = $name;
        $this->description = $description;
        $this->getLogger()->info('Editing dataSet ID ' . $this->dataSetId);
        $response = $this->doPut('/dataset/' . $this->dataSetId, $this->toArray());
        
        return $this->hydrate($response);
    }


    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting dataSet ID ' . $this->dataSetId);
        $this->doDelete('/dataset/' . $this->dataSetId);
        
        return true;
    }

    /**
     * Delete with data
     * @return bool
     */
    public function deleteWData()
    {
        $this->getLogger()->info('Forcefully deleting dataSet ID ' . $this->dataSetId);
        $this->doDelete('/dataset/' . $this->dataSetId, [
            'deleteData' => 1
            ]);
        
        return true;
    }

    /**
     * Create Column
     * @param $heading
     * @param $listContent
     * @param $columnOrder
     * @param $dataTypeId
     * @param $dataSetColumnTypeId
     * @param $formula
     * @return XiboDataSet
     */
    public function createColumn($heading, $listContent, $columnOrder, $dataTypeId, $dataSetColumnTypeId, $formula)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->heading = $heading;
        $this->listContent = $listContent;
        $this->columnOrder = $columnOrder;
        $this->dataTypeId = $dataTypeId;
        $this->dataSetColumnTypeId = $dataSetColumnTypeId;
        $this->formula = $formula;
        $this->getLogger()->info('Creating a new column ' . $heading . ' In dataSet ID ' . $this->dataSetId);
        $response = $this->doPost('/dataset/'. $this->dataSetId . '/column', $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * @return XiboDataSetColumn
     * @throws XiboApiException
     */
    public function getColumns()
    {
        return (new XiboDataSetColumn($this->getEntityProvider()))->get($this->dataSetId);
    }
    /**
     * @param $id
     * @return XiboDataSetColumn
     * @throws XiboApiException
     */
    public function getByColumnId($id)
    {
        return (new XiboDataSetColumn($this->getEntityProvider()))->getById($this->dataSetId, $id);
    }

    /**
     * Edit Column
     * @param $heading
     * @param $listContent
     * @param $columnOrder
     * @param $dataTypeId
     * @param $dataSetColumnTypeId
     * @param $formula
     * @return XiboDataSet
     */
    public function editColumn($heading, $listContent, $columnOrder, $dataTypeId, $dataSetColumnTypeId, $formula)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->heading = $heading;
        $this->listContent = $listContent;
        $this->columnOrder = $columnOrder;
        $this->dataTypeId = $dataTypeId;
        $this->dataSetColumnTypeId = $dataSetColumnTypeId;
        $this->formula = $formula;
        $this->getLogger()->info('Editing column ID ' . $this->dataSetColumnId . ' From dataSet ID ' . $this->dataSetId);
        $response = $this->doPut('/dataset/'. $this->dataSetId . '/column/' . $this->dataSetColumnId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Delete Column
     * @return bool
     */
    public function deleteColumn()
    {
        $this->getLogger()->info('Deleting column ID ' . $this->dataSetColumnId . ' From dataSet ID ' . $this->dataSetId);
        $this->doDelete('/dataset/' . $this->dataSetId . '/column/' . $this->dataSetColumnId);
        
        return true;
    }

    /**
     * @return XiboDataSetRow
     * @throws XiboApiException
     */
    public function getData()
    {
        return (new XiboDataSetRow($this->getEntityProvider()))->get($this->dataSetId);
    }

    /**
     * Create Row
     * @param $rowData
     * @return mixed
     */
    public function createRow($columnId, $rowData)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Creating row in dataSet ID ' . $this->dataSetId);
        $response = $this->doPost('/dataset/data/'. $this->dataSetId, [
            'dataSetColumnId_' . $columnId => $rowData
            ]);
        
        return $response;
    }

    /**
     * Edit Row
     * @param $columnId
     * @param $rowData
     * @return mixed
     */
    public function editRow($columnId, $rowData)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Editing row ID ' . $this->rowId . ' From dataSet ID ' . $this->dataSetId);
        $response = $this->doPut('/dataset/data/'. $this->dataSetId . $this->rowId, [
            'dataSetColumnId_' . $columnId => $rowData
            ]);
        
        return $response;
    }

    /**
     * Delete Row
     * @return bool
     */
    public function deleteRow()
    {
        $this->getLogger()->info('Deleting row ID ' . $this->rowId . ' From dataSet ID ' . $this->dataSetId);
        $this->doDelete('/dataset/data/' . $this->dataSetId . $this->rowId);
        
        return true;
    }

}
