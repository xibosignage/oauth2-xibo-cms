<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDataSetView.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboDataSetView extends XiboWidget
{
    public $widgetId;
    public $playlistId;
    public $ownerId;
    public $type;
    public $duration;
    public $displayOrder;
    public $useDuration;
    public $calculatedDuration;
    public $widgetOptions;
    public $mediaIds;
    public $audio;
    public $permissions;
    public $module;
    public $name;
    public $dataSetId;
    public $updateInterval;
    public $rowsPerPage;
    public $showHeadings;
    public $upperLimit;
    public $lowerLimit;
    public $filter;
    public $ordering;
    public $templateId;
    public $overrideTemplate;
    public $useOrderingClause;
    public $useFilteringClause;
    public $noDataMessage;

    /**
     * Create
     * @param $dataSetId
     * @param $playlistId
     * @return XiboDataSetView
     */
    public function create($dataSetId, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->dataSetId = $dataSetId;
        $this->playlistId = $playlistId;
        $this->getLogger()->info('Creating DataSet View widget and assigning it to playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/dataSetView/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param array $dataSetColumnId
     * @param $updateInterval
     * @param $rowsPerPage
     * @param $showHeadings
     * @param int $upperLimit
     * @param int $lowerLimit
     * @param $filter
     * @param $ordering
     * @param string $templateId
     * @param int $overrideTemplate
     * @param int $useOrderingClause
     * @param int $useFilteringClause
     * @param string $noDataMessage
     * @param $widgetId
     * @return XiboDataSetView
     */
    public function edit($name, $duration, $useDuration, $dataSetColumnId = [], $updateInterval, $rowsPerPage, $showHeadings, $upperLimit = 0, $lowerLimit = 0, $filter = null, $ordering = null, $templateId = 'empty', $overrideTemplate = 0, $useOrderingClause = 0, $useFilteringClause = 0, $noDataMessage = '', $widgetId)
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
        $this->getLogger()->info('Editing widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId, $this->toArray());

        return $this->hydrate($response);

    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Deleting widget ID' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
