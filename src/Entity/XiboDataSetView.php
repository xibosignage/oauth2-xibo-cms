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

class XiboDataSetView extends XiboWidget
{
    /** @var int The widget ID */
    public $widgetId;

    /** @var int The playlist ID */
    public $playlistId;

    /** @var int The dataSet ID */
    public $dataSetId;

    /** @var int The owner ID */
    public $ownerId;

    /** @var int Widget duration */
    public $duration;

    /** @var int Flag whether to use custom duration */
    public $useDuration;

    /** @var int Widget duration */
    public $displayOrder;

    /** @var array of widget Options */
    public $widgetOptions;

    /** @var array(int) of media Ids */
    public $mediaIds;

    /** @var array of audio details assigned to the widget */
    public $audio;

    /** @var array of permissions to the widget */
    public $permissions;

    /** @var string Optional widget Name */
    public $name;

    /** @var int Widget Update Interval in minutes */
    public $updateInterval;

    /** @var int Number of rows per page (0 for no limit) */
    public $rowsPerPage;

    /** @var int Should the table show Heading? */
    public $showHeadings;

    /** @var int Upper row limit for this dataSet, 0 for no limit */
    public $upperLimit;

    /** @var int Lower row limit for this dataSet, 0 for no limit */
    public $lowerLimit;

    /** @var string SQL clause to filter this dataSet */
    public $filter;

    /** @var string SQL clause for how to order this dataSet */
    public $ordering;

    /** @var string Template you'd like to apply, empty or light-green */
    public $templateId;

    /** @var int Flag to override the template */
    public $overrideTemplate;

    /** @var int Flag to use advanced order clause - set to 1 if the ordering is provided */
    public $useOrderingClause;

    /** @var int Flag to use advanced filter clause - set to 1 if the filter is provided */
    public $useFilteringClause;

    /** @var string A message to display when there is no data returned from the source */
    public $noDataMessage;

    /**
     * Create a new dataSetView widget.
     *
     * @param int $dataSetId The dataSet ID to use as a source for dataSetView widget
     * @param int $playlistId The playlist ID to which this dataSetView widget should be added
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboDataSetView
     */
    public function create($dataSetId, $playlistId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->dataSetId = $dataSetId;
        $this->playlistId = $playlistId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Creating DataSet View widget and assigning it to playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/dataSetView/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit the DataSetView widget.
     *
     * @param string $name Optional widget name
     * @param int $duration Widget Duration
     * @param int $useDuration Flag whether to use custom duration
     * @param array $dataSetColumnId An array of dataSetColumnIds to assign
     * @param int $updateInterval Widget update interval in minutes
     * @param int $rowsPerPage Number of rows per page, 0 for no pages
     * @param int $showHeadings Flag Should the table show the Heading?
     * @param int $upperLimit Upper row limit for this dataSet, 0 for no limit
     * @param int $lowerLimit Lower row limit for this dataSet, 0 for no limit
     * @param string $filter SQL clause to filter the dataSet
     * @param string $ordering SQL clause to order the dataSet
     * @param string $templateId Template to apply, available options: empty, light-green
     * @param int $overrideTemplate Flag whether to override the template
     * @param int $useOrderingClause Flag whether to use advanced ordering, set to 1 if ordring is provided
     * @param int $useFilteringClause Flag whether to use advanced filer, set to 1 ifd filter is provided
     * @param string $noDataMessage A message to display when there is no data returned from the source
     * @param int $widgetId The Widget ID to edit
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboDataSetView
     */
    public function edit($name, $duration, $useDuration, $dataSetColumnId = [], $updateInterval, $rowsPerPage, $showHeadings, $upperLimit = 0, $lowerLimit = 0, $filter = null, $ordering = null, $templateId = 'empty', $overrideTemplate = 0, $useOrderingClause = 0, $useFilteringClause = 0, $noDataMessage = '', $widgetId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->dataSetColumnId = $dataSetColumnId;
        $this->updateInterval = $updateInterval;
        $this->rowsPerPage = $rowsPerPage;
        $this->showHeadings = $showHeadings;
        $this->upperLimit = $upperLimit;
        $this->lowerLimit = $lowerLimit;
        $this->filter = $filter;
        $this->ordering = $ordering;
        $this->templateId = $templateId;
        $this->overrideTemplate = $overrideTemplate;
        $this->useOrderingClause = $useOrderingClause;
        $this->useFilteringClause = $useFilteringClause;
        $this->noDataMessage = $noDataMessage;
        $this->widgetId = $widgetId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId, $this->toArray());

        return $this->hydrate($response);

    }

    /**
     * Delete the widget.
     */
    public function delete()
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Deleting widget ID' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
