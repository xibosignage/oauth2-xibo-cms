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

class XiboDaypart extends XiboEntity
{
	private $url = '/daypart';

    /** @var int The dayPrt ID */
	public $dayPartId;

    /** @var string The dayPart name */
	public $name;

    /** @var string The dayPart description */
	public $description;

    /** @var string The start time for this dayPart */
	public $startTime;

    /** @var string The end time for this dayPart */
	public $endTime;

    /** @var array[string] String array of exception days*/
	public $exceptionDays;

    /** @var array[]string] String array of exception start times to match the exception days */
	public $exceptionStartTimes;

    /** @var array[]string] String array of exception end times to match the exception days */
	public $exceptionEndTimes;

	/**
     * Get a list of dayparts.
     *
     * @param array $params ccan be filtered by dayPartId, name and embeddable parameter embed=exceptions
     * @return array|XiboDaypart
     */
    public function get(array $params = [])
    {
        $entries = [];
        $this->getLogger()->info('Getting dayparts ' . $this->name);
        $response = $this->doGet($this->url, $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Get the DayPart by dayPartId.
     *
     * @param int $id The DayPart ID
     * @return XiboDaypart
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $this->getLogger()->info('Getting dayPart ID ' . $id);
        $response = $this->doGet($this->url, [
            'dayPartId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create a new DayPrt.
     *
     * @param string $name The name for the daypart
     * @param string $description The description for the daypart
     * @param string $startTime The start time for this daypart
     * @param string $endTime The end time for this daypart
     * @param array[string] $exceptionDays String array of exception days
     * @param array[string] $exceptionStartTimes String array of start times to match the exception days
     * @param array[string] $exceptionEndTimes String array of end times to match the exception days
     * @return XiboDaypart
     */
    public function create($name, $description, $startTime, $endTime, $exceptionDays = [], $exceptionStartTimes = [], $exceptionEndTimes = [])
    {
        $this->name = $name;
        $this->description = $description;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->exceptionDays = $exceptionDays;
        $this->exceptionStartTimes = $exceptionStartTimes;
        $this->exceptionEndTimes = $exceptionEndTimes;

        $this->getLogger()->info('Creating dayPart ' . $name);
        $response = $this->doPost('/daypart', $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit Daypart.
     *
     * @param string $name The name for the daypart
     * @param string $description The description for the daypart
     * @param string $startTime The start time for this daypart
     * @param string $endTime The end time for this daypart
     * @param array[string] $exceptionDays String array of exception days
     * @param array[string] $exceptionStartTimes String array of start times to match the exception days
     * @param array[string] $exceptionEndTimes String array of end times to match the exception days
     * @return XiboDaypart
     */
    public function edit($name, $description, $startTime, $endTime, $exceptionDays = [], $exceptionStartTimes = [], $exceptionEndTimes = [])
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->description = $description;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->exceptionDays = $exceptionDays;
        $this->exceptionStartTimes = $exceptionStartTimes;
        $this->exceptionEndTimes = $exceptionEndTimes;
        
        $this->getLogger()->info('Editing dayPart ID ' . $this->dayPartId);
        $response = $this->doPut('/daypart/' . $this->dayPartId, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Delete the daypart.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting dayPart ID ' . $this->dayPartId);
        $this->doDelete('/daypart/' . $this->dayPartId);
        
        return true;
    }

}