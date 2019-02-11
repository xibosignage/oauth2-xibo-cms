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


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboDataSet extends XiboEntity
{

	private $url = '/dataset';

    /** @var int The dataSet ID */
    public $dataSetId;

    /** @var string The dataSet name */
    public $dataSet;

    /** @var string The dataSet description */
    public $description;

    /** @var int The user (owner) ID */
    public $userId;

    /** @var int time of the last edit made to the dataSet */
    public $lastDataEdit;

    /** @var string owner name */
    public $owner;

    /** @var string list of groups with permissions to this dataSet */
    public $groupsWithPermissions;

    /** @var string DataSet code */
    public $code;

    /** @var int Flag to indicate whether this DataSet is a lookup table */
    public $isLookup;

    /** @var int Flag to indicate whether this DataSet is a remote dataSet */
    public $isRemote;

    /** @var string The Request Method GET or POST */
    public $method;

    /** @var string URI to call to fetch Data from. Replacements are {{DATE}}, {{TIME}} and, in case this is a sequencial used DataSet, {{COL.NAME}} where NAME is a ColumnName from the underlying DataSet. */
    public $uri;

    /** @var string Data to send as POST-Data to the remote host with the same Replacements as in the URI */
    public $postData;

    /** @var string Authentication method, can be none, digest, basic */
    public $authentication;

    /** @var string Username to authenticate with */
    public $username;

    /** @var string Password to authenticate with */
    public $password;

    /** @var int Time in seconds this DataSet should fetch new Datas from the remote host */
    public $refreshRate;

    /** @var int Time in seconds when this Dataset should be cleared. If here is a lower value than in RefreshRate it will be cleared when the data is refreshed */
    public $clearRate;

    /** @var int DataSetID of the DataSet which should be fetched and present before the Data from this DataSet are fetched */
    public $runsAfter;

    /** @var int Last Synchronisation Timestamp */
    public $lastSync;

    /** @var int Last Clear Timestamp */
    public $lastClear;

    /** @var string Root-Element form JSON where the data are stored in */
    public $dataRoot;

    /** @var string Optional function to use for summarize or count unique fields in a remote request */
    public $summarize;

    /** @var string JSON-Element below the Root-Element on which the consolidation should be applied on */
    public $summarizeField;

    /** @var array DataSetColumn[] */
    public $columns;

    /** @var int The row Id */
    public $rowId;


	/**
     * Get the list of dataSets.
     *
     * @param array $params filter the results by: dataSetId, dataSet, code, embeddable parameter embed=columns
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
     * Get the DataSet by DataSetId.
     *
     * @param int $id the DataSetId to find
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
     * Create the DataSet.
     *
     * @param string $name New name for the dataSet
     * @param string $description New Description for the dataSet
     * @param string $code New code for this DataSet
     * @param int $isRemote Flag (0, 1) is this remote DataSet?
     * @param string $method For isRemote=1, The Request Method GET or POST
     * @param string $uri For isRemote=1, The URI, without query parameters
     * @param string $postData For isRemote=1, Query parameter encoded data to add to the request
     * @param string $authentication For isRemote=1, HTTP Authentication method None|Basic|Digest
     * @param string $username for isRemote=1, HTTP Authentication User Name
     * @param string $password for isRemote=1, HTTP Authentication Password
     * @param int $refreshRate for isRemote=1, How often in seconds should this remote DataSet be refreshed
     * @param int $clearRate for isRemote=1, How often in seconds should this remote DataSet be truncated
     * @param int $runsAfter for isRemote=1, Optional dataSetId which should be run before this remote DataSet
     * @param string $dataRoot for isRemote=1, The root of the data in the Remote source which is used as the base for all remote columns
     * @param string $summarize for isRemote=1, Should the data be aggregated? None|Summarize|Count
     * @param string $summarizeField for isRemote=1, Which field should be used to summarize
     * @return XiboDataSet
     */
    public function create($name, $description, $code = '', $isRemote = 0, $method = '', $uri = '', $postData = '', $authentication = '', $username = '', $password = '', $refreshRate = null, $clearRate = null, $runsAfter = null, $dataRoot = '', $summarize = '', $summarizeField = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->dataSet = $name;
        $this->description = $description;
        $this->code = $code;
        $this->isRemote = $isRemote;
        $this->method = $method;
        $this->uri = $uri;
        $this->postData = $postData;
        $this->authentication = $authentication;
        $this->username = $username;
        $this->password = $password;
        $this->refreshRate = $refreshRate;
        $this->clearRate = $clearRate;
        $this->runsAfter = $runsAfter;
        $this->dataRoot = $dataRoot;
        $this->summarize = $summarize;
        $this->summarizeField = $summarizeField;

        $this->getLogger()->info('Creating dataSet ' . $name);
        $response = $this->doPost('/dataset', $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Edit the DataSet.
     *
     * @param int $dataSetId The Id of the dataSet to edit
     * @param string $name New name for the dataSet
     * @param string $description New Description for the dataSet
     * @param string $code New code for this DataSet
     * @param int $isRemote Flag (0, 1) is this remote DataSet?
     * @param string $method For isRemote=1, The Request Method GET or POST
     * @param string $uri For isRemote=1, The URI, without query parameters
     * @param string $postData For isRemote=1, Query parameter encoded data to add to the request
     * @param string $authentication For isRemote=1, HTTP Authentication method None|Basic|Digest
     * @param string $username for isRemote=1, HTTP Authentication User Name
     * @param string $password for isRemote=1, HTTP Authentication Password
     * @param int $refreshRate for isRemote=1, How often in seconds should this remote DataSet be refreshed
     * @param int $clearRate for isRemote=1, How often in seconds should this remote DataSet be truncated
     * @param int $runsAfter for isRemote=1, Optional dataSetId which should be run before this remote DataSet
     * @param string $dataRoot for isRemote=1, The root of the data in the Remote source which is used as the base for all remote columns
     * @param string $summarize for isRemote=1, Should the data be aggregated? None|Summarize|Count
     * @param string $summarizeField for isRemote=1, Which field should be used to summarize
     * @return XiboDataSet
     */
    public function edit($dataSetId, $name, $description, $code = '', $isRemote = 0, $method = '', $uri = '', $postData = '', $authentication = '', $username = '', $password = '', $refreshRate = null, $clearRate = null, $runsAfter = null, $dataRoot = '', $summarize = '', $summarizeField = '')
    {
        $this->dataSetId = $dataSetId;
        $this->dataSet = $name;
        $this->description = $description;
        $this->code = $code;
        $this->isRemote = $isRemote;
        $this->method = $method;
        $this->uri = $uri;
        $this->postData = $postData;
        $this->authentication = $authentication;
        $this->username = $username;
        $this->password = $password;
        $this->refreshRate = $refreshRate;
        $this->clearRate = $clearRate;
        $this->runsAfter = $runsAfter;
        $this->dataRoot = $dataRoot;
        $this->summarize = $summarize;
        $this->summarizeField = $summarizeField;

        $this->getLogger()->info('Editing dataSet ID ' . $this->dataSetId);
        $response = $this->doPut('/dataset/' . $dataSetId, $this->toArray());
        
        return $this->hydrate($response);
    }


    /**
     * Delete the dataSet.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting dataSet ID ' . $this->dataSetId);
        $this->doDelete('/dataset/' . $this->dataSetId);
        
        return true;
    }

    /**
     * Force delete dataSet that contains a data in it.
     *
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
     * Copy DataSet.
     *
     * @param int $dataSetId Id of the dataSet to copy
     * @param string $dataSet Name of the copied dataSet
     * @param string $description Description of the copied dataSet
     * @param string $code Code for the new dataSet
     * @return XiboDataSet
     */
    public function copy($dataSetId, $dataSet, $description = '', $code ='')
    {
        $this->dataSetId = $dataSetId;
        $this->dataSet = $dataSet;
        $this->description = $description;
        $this->code = $code;

        $this->getLogger()->info('Copy dataSet ID ' . $dataSetId);
        $response = $this->doPost('/dataset/copy/' . $dataSetId, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Create Column.
     *
     * @param string $heading The heading for the column
     * @param string $listContent A comma separated list of content for dropdowns
     * @param int $columnOrder Display order for this column
     * @param int $dataTypeId The data type ID for this column 1 - String, 2 - Number, 3 - Date, 4 - External Image, 5 - Internal Image
     * @param int $dataSetColumnTypeId The column type for this column 1 - Value, 2 - Formula, 3 -  Remote
     * @param string $formula MySQL SELECT syntax formula for this column if the column type is set to formula
     * @param string $remoteField Only for dataSetColumnTypeId=3, JSON-String to select Data from the remote DataSet
     * @param int $showFilter Flag indicating whether this column should present a filter on DataEntry
     * @param int $showSort Flag indicating whether this column should allow sorting on DataEntry
     * @return XiboDataSet
     */
    public function createColumn($heading, $listContent, $columnOrder, $dataTypeId, $dataSetColumnTypeId, $formula, $remoteField = '', $showFilter = 0, $showSort = 0)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->heading = $heading;
        $this->listContent = $listContent;
        $this->columnOrder = $columnOrder;
        $this->dataTypeId = $dataTypeId;
        $this->dataSetColumnTypeId = $dataSetColumnTypeId;
        $this->formula = $formula;
        $this->remoteField = $remoteField;
        $this->showFilter = $showFilter;
        $this->showSort = $showSort;

        $this->getLogger()->info('Creating a new column ' . $heading . ' In dataSet ID ' . $this->dataSetId);
        $response = $this->doPost('/dataset/'. $this->dataSetId . '/column', $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Get columns for this DataSet.
     *
     * @return XiboDataSetColumn
     * @throws XiboApiException
     */
    public function getColumns()
    {
        return (new XiboDataSetColumn($this->getEntityProvider()))->get($this->dataSetId);
    }
    /**
     * Get a specific column by dataSetColumnId for this DataSet.
     *
     * @param $id
     * @return XiboDataSetColumn
     * @throws XiboApiException
     */
    public function getByColumnId($id)
    {
        return (new XiboDataSetColumn($this->getEntityProvider()))->getById($this->dataSetId, $id);
    }

    /**
     * Edit Column.
     *
     * @param string $heading The heading for the column
     * @param string $listContent A comma separated list of content for dropdowns
     * @param int $columnOrder Display order for this column
     * @param int $dataTypeId The data type ID for this column 1 - String, 2 - Number, 3 - Date, 4 - External Image, 5 - Internal Image
     * @param int $dataSetColumnTypeId The column type for this column 1 - Value, 2 - Formula, 3 -  Remote
     * @param string $formula MySQL SELECT syntax formula for this column if the column type is set to formula
     * @param string $remoteField Only for dataSetColumnTypeId=3, JSON-String to select Data from the remote DataSet
     * @param int $showFilter Flag indicating whether this column should present a filter on DataEntry
     * @param int $showSort Flag indicating whether this column should allow sorting on DataEntry
     * @return XiboDataSet
     * @return XiboDataSet
     */
    public function editColumn($heading, $listContent, $columnOrder, $dataTypeId, $dataSetColumnTypeId, $formula, $remoteField = '', $showFilter = 0, $showSort = 0)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->heading = $heading;
        $this->listContent = $listContent;
        $this->columnOrder = $columnOrder;
        $this->dataTypeId = $dataTypeId;
        $this->dataSetColumnTypeId = $dataSetColumnTypeId;
        $this->formula = $formula;
        $this->remoteField = $remoteField;
        $this->showFilter = $showFilter;
        $this->showSort = $showSort;

        $this->getLogger()->info('Editing column ID ' . $this->dataSetColumnId . ' From dataSet ID ' . $this->dataSetId);
        $response = $this->doPut('/dataset/'. $this->dataSetId . '/column/' . $this->dataSetColumnId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Delete Column.
     *
     * @return bool
     */
    public function deleteColumn()
    {
        $this->getLogger()->info('Deleting column ID ' . $this->dataSetColumnId . ' From dataSet ID ' . $this->dataSetId);
        $this->doDelete('/dataset/' . $this->dataSetId . '/column/' . $this->dataSetColumnId);
        
        return true;
    }

    /**
     * Get DataSet row Data.
     *
     * @return XiboDataSetRow
     * @throws XiboApiException
     */
    public function getData()
    {
        return (new XiboDataSetRow($this->getEntityProvider()))->get($this->dataSetId);
    }

    /**
     * Create Row.
     *
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
     * Edit Row.
     *
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
     * Delete Row.
     *
     * @return bool
     */
    public function deleteRow()
    {
        $this->getLogger()->info('Deleting row ID ' . $this->rowId . ' From dataSet ID ' . $this->dataSetId);
        $this->doDelete('/dataset/data/' . $this->dataSetId . $this->rowId);
        
        return true;
    }

}
