<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDataSetRow.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboDataSetRow extends XiboEntity
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
     * @param $dataSetId
     * @return XiboDataSetRow
     */
    public function get($dataSetId)
    {
        $this->dataSetId = $dataSetId;
        $this->getLogger()->info('Getting row data from dataSet ID ' . $dataSetId);
        $response = $this->doGet('/dataset/data/'. $this->dataSetId);

        return $response;
    }

    /**
     * Create Row
     * @param $rowData
     * @param $dataSetId
     * @param $columnId
     * @return XiboDataSetRow
     */
    public function create($dataSetId, $columnId, $rowData)
    {
        $this->dataSetId = $dataSetId;
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Creating row in dataSet ID ' . $dataSetId . ' Column ID ' . $columnId);
        $response = $this->doPost('/dataset/data/'. $dataSetId, [
            'dataSetColumnId_' . $columnId => $rowData
            ]);
        
        return $response;
    }

    /**
     * Edit Row
     * @param $rowData
     * @param $dataSetId
     * @param $columnId
     * @return XiboDataSetRow
     */
    public function edit($dataSetId, $columnId, $rowData)
    {
        $this->dataSetId = $dataSetId;
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Editing row ID' . $this->rowId . ' In dataSet ID ' . $dataSetId . ' Column ID' . $columnId);
        $response = $this->doPut('/dataset/data/'. $this->dataSetId . $this->rowId, [
            'dataSetColumnId_' . $columnId => $rowData
            ]);
        
        return $response;
    }

    /**
     * Delete Row
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting row ID ' . $this->rowId . ' From dataSet ID ' . $this->dataSetId);
        $this->doDelete('/dataset/data/' . $this->dataSetId . $this->rowId);
        
        return true;
    }
}
