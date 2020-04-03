<?php
/**
 * Copyright (C) 2020 Xibo Signage Ltd
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

use Xibo\OAuth2\Client\Exception\InvalidArgumentException;
use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboReportSchedule extends XiboEntity
{
    private $url = '/report/reportschedule';

    /** @var int ReportSchedule ID */
    public $reportScheduleId;

    /** @var string The ReportSchedule */
    public $reportschedule;

    /** @var string The schedule report name */
    public $reportName;

    /** @var string The last saved report Id of a report schedule */
    public $lastSavedReportId;

    /**
     * Create a new reportschedule.
     * Creates new reportschedule with the specified parameters
     *
     * @param string $name Name of the reportschedule
     * @param string $reportName reportName of the reportschedule
     * @param string $filter  provide the filter of the report
     * @param string $groupByFilter  provide the groupByFilter of the report
     * @param bool $sendEmail email the report
     * @param int $displayId
     * @param string $hiddenFields
     * @return XiboReportSchedule
     */
    public function create($name, $reportName, $filter,
       $groupByFilter = null, $sendEmail = null, $displayId = null, $hiddenFields = null)
    {
        $this->getLogger()->debug('Getting Resource Owner');
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->reportName = $reportName;
        $this->filter = $filter;
        $this->groupByFilter = $groupByFilter;
        $this->sendEmail = $sendEmail;
        $this->displayId = $displayId;
        $this->hiddenFields = $hiddenFields;

        $this->getLogger()->info('Creating Report Schedule ' . $this->reportschedule);
        $response = $this->doPost($this->url, $this->toArray());

        $reportschedule = $this->constructReportScheduleFromResponse($response);

        return $reportschedule;
    }

    /**
     * Delete the report schedule.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting Report Schedule ID ' . $this->reportScheduleId);
        $this->doDelete($this->url . $this->reportScheduleId);
        
        return true;
    }

    /**
     * @param $response
     * @return \Xibo\OAuth2\Client\Entity\XiboEntity|XiboReportSchedule
     */
    private function constructReportScheduleFromResponse($response)
    {
        /** @var XiboReportSchedule $reportschedule */
        $reportschedule = new XiboReportSchedule($this->getEntityProvider());
        $reportschedule = $reportschedule->hydrate($response);

        $this->getLogger()->debug('Constructing Report Schedule from Response: ' . $reportschedule->reportScheduleId);

        return $reportschedule;
    }


    /**
     * Get the Report Schedule by its ID.
     *
     * @param int $id ReportScheduleId to search for
     * @return XiboReportSchedule
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $this->getLogger()->info('Getting Report Schedule ID ' . $id);
        $response = $this->doGet($this->url, [
            'reportScheduleId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        /** @var XiboReportSchedule $reportSchedule */
        $reportSchedule = $this->hydrate($response[0]);

        return clone $reportSchedule;
    }
}
