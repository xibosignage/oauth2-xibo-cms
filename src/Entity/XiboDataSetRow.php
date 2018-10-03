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

class XiboDataSetRow extends XiboEntity
{

	private $url = '/dataset';

    /** @var int The ID of the DataSet that this row belongs to */
    public $dataSetId;

    /** @var int The ID of the DataSet column that this row belongs to */
    public $dataSetColumnId;

    /** @var int The ID of the DataSet row */
    public $rowId;


    /**
     * Get a data for the specified dataSetId.
     *
     * @param int $dataSetId The DataSetId
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
     * Create Row.
     *
     * @param string $rowData The data to add to the specified column
     * @param int $dataSetId The DataSetId
     * @param int $columnId The dataSetColumnId
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
     * Edit Row.
     *
     * @param string $rowData The data to edit to the specified column
     * @param int $dataSetId The DataSetId
     * @param int $columnId The dataSetColumnId
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
     * Delete Row.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting row ID ' . $this->rowId . ' From dataSet ID ' . $this->dataSetId);
        $this->doDelete('/dataset/data/' . $this->dataSetId . $this->rowId);
        
        return true;
    }
}
