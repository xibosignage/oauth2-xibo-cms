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

class XiboDataSetColumn extends XiboEntity
{

    private $url = '/dataset';

    /** @var int The ID of this DataSetColum */
    public $dataSetColumnId;

    /** @var int The ID of the DataSet that this Column belongs to */
    public $dataSetId;

    /** @var string The Column Heading */
    public $heading;

    /** @var int The ID of the DataType for this Column */
    public $dataTypeId;

    /** @var int The ID of the ColumnType for this Column */
    public $dataSetColumnTypeId;

    /** @var string Comma separated list of valid content for drop down columns */
    public $listContent;

    /** @var int The order this column should be displayed */
    public $columnOrder;

    /** @var string A MySQL formula for this column */
    public $formula;

    /** @var string The data type for this Column */
    public $dataType;

    /** @var string The data field of the remote DataSet as a JSON-String */
    public $remoteField;

    /** @var int Does this column show a filter on the data entry page? */
    public $showFilter;

    /** @var int Does this column allow a sorting on the data entry page? */
    public $showSort;

    /**
     * Get a list of columns for this DataSet.
     *
     * @param int $dataSetId The DataSet Id
     * @param array $params can be filtered by dataSetColumnId
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
     * Get column by Id for this DataSet.
     *
     * @param int $dataSetId The DataSet Id
     * @param int $id The dataSetColumnId to get
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
      * @return XiboDataSetColumn
     */
    public function create($dataSetId, $heading, $listContent, $columnOrder, $dataTypeId, $dataSetColumnTypeId, $formula, $remoteField = '', $showFilter = 0, $showSort = 0)
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
        $this->dataSetId = $dataSetId;
        $this->getLogger()->info('Creating a new column ' . $heading . ' In dataSet ID ' . $this->dataSetId);
        $response = $this->doPost('/dataset/'. $this->dataSetId . '/column', $this->toArray());
        
        return $this->hydrate($response);
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
     * @return XiboDataSetColumn
     */
    public function edit($heading, $listContent, $columnOrder, $dataTypeId, $dataSetColumnTypeId, $formula, $remoteField = '', $showFilter = 0, $showSort = 0)
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
        $this->getLogger()->info('Editing column ID ' . $this->dataSetColumnId . ' In dataSet ID ' . $this->dataSetId);
        $response = $this->doPut('/dataset/'. $this->dataSetId . '/column/' . $this->dataSetColumnId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Delete Column.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting column ID ' . $this->dataSetColumnId . ' From dataSet ID ' . $this->dataSetId);
        $this->doDelete('/dataset/' . $this->dataSetId . '/column/' . $this->dataSetColumnId);
        
        return true;
    }
 }   