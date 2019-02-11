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

class XiboSchedule extends XiboEntity
{
    /** @var int The scheduled event ID */
    public $id;

    /** @var int The DisplayGroupId to return the event list for. */
    public $displayGroupId;

    /** @var string Date in Y-m-d H:i:s */
    public $date;

    /** @var int The Event Type Id*/
    public $eventTypeId;

    /** @var int The Campaign ID to use for this Event. If a Layout is needed then the Campaign specific ID for that Layout should be used.*/
    public $campaignId;

    /** @var int The Command ID to use for this Event.*/
    public $commandId;

    /** @var int The display order for this event. */
    public $displayOrder;

    /** @var int An integer indicating the priority of this event. Normal events have a priority of 0. */
    public $isPriority;

    /** @var array[int] The Display Group IDs for this event. Display specific Group IDs should be used to schedule on single displays. */
    public $displayGroupIds;

    /** @var int The Day Part for this event. Overrides supported are 0(custom) and 1(always). Defaulted to 0. */
    public $dayPartId = 0;

    /** @var int Flag Should this schedule be synced to the resulting Display timezone? */
    public $syncTimezone;

    /** @var string The from date for this event. */
    public $fromDt;

    /** @var string The to date for this event. */
    public $toDt;

    /** @var string The type of recurrence to apply to this event */
    public $recurrenceType;

    /** @var int The interval for the recurrence. */
    public $recurrenceDetail;

    /** @var string The end date for this events recurrence. */
    public $recurrenceRange;

    /** @var string The days of the week that this event repeats - weekly only */
    public $recurrenceRepeatsOn;

    /** @var int The Scheduled Event ID */
    public $eventId;

 	/**
     * Get a list of scheduled events
     *
     * @param array $params can be filtered by: displayGroupIds, from, to
     * @return array|XiboSchedule
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting list of scheduled events');
        $entries = [];
        $response = $this->doGet('/schedule/data/events', $params);

        foreach ($response['result'] as $item) {
            $entries[] = clone $this->hydrate($item);
        }
        return $entries;
    }

    /**
     * Get events for specified display Group ID.
     *
     * @param int $displayGroupId The display group ID
     * @param string $date Date in Y-m-d H:i:s
     * @return array|XiboSchedule
     */
    public function getEvents($displayGroupId, $date)
    {
        $this->getLogger()->info('Getting list of events for specified display group ' . $displayGroupId . ' and date ' . $date);
        $response = $this->doGet('/schedule/' . $displayGroupId . '/events', [
            'date' => $date
        ]);

       return clone $this->hydrate($response);
    }

    /**
     * Get a list of scheduled events for the specified display Group.
     *
     * @param array $params
     * @param int $id The Event ID
     * @return array $entries require displayGroupId additionally to and from can be added for dayparts other than always
     * @throws XiboApiException
     */
    public function getById(array $params = [], $id)
    {
        $this->getLogger()->info('Getting event with ID ' . $id);
        $entries = [];
        # need at least display group (always events) and to, from (other dayparts)
        $response = $this->doGet('/schedule/data/events', $params);

        foreach ($response['result'] as $item) {
            if($item['id'] === $id){
            $entries[] = clone $this->hydrate($item);
        }
            else
                throw new XiboApiException('Provided eventId not found ' . $id);
        }
        return $entries;
    }

    /**
     * Create Campaign/Layout event.
     *
     * @param string $fromDt the FROM date for this event
     * @param string $toDt The TO date for this event
     * @param int $campaignId The Campaign ID to use for this Event. If a Layout is needed then the Campaign specific ID for that Layout should be used
     * @param array[int] $displayGroupIds Array of display Group IDs for this event, Display specific Display Group IDs should be used to schedule on single displays.
     * @param int $dayPartId The Day Part for this event. Overrides supported are 0(custom) and 1(always). Defaulted to 0
     * @param string $recurrenceType The type of recurrence to apply to this event, Available values : , Minute, Hour, Day, Week, Month, Year
     * @param int $recurrenceDetail The interval for the recurrence
     * @param string $recurrenceRange The end date for this events recurrence
     * @param int $displayOrder The display order for this event.
     * @param int $isPriority An integer indicating the priority of this event. Normal events have a priority of 0
     * @param int $syncTimezone Flag Should this schedule be synced to the resulting Display timezone
     * @return XiboSchedule
     */
    public function createEventLayout($fromDt, $toDt, $campaignId, $displayGroupIds, $dayPartId, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority, $syncTimezone)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = 1;
        $this->fromDt = $fromDt;
        $this->toDt = $toDt;
        $this->campaignId = $campaignId;
        $this->displayGroupIds = $displayGroupIds;
        $this->dayPartId = $dayPartId;
        $this->recurrenceType = $recurrenceType;
        $this->recurrenceDetail = $recurrenceDetail;
        $this->recurrenceRange = $recurrenceRange;
        $this->displayOrder = $displayOrder;
        $this->isPriority = $isPriority;
        $this->syncTimezone = $syncTimezone;

        $this->getLogger()->info('Creating a new scheduled event ');
        $response = $this->doPost('/schedule', $this->toArray());
        
        $this->getLogger()->debug('Passing the response to hydrate');
        return $this->hydrate($response);
    }

    /**
     * Create Command event.
     *
     * @param string $fromDt the FROM date for this event
     * @param int $commandId The Command ID to use for this Event
     * @param array[int] $displayGroupIds Array of display Group IDs for this event, Display specific Display Group IDs should be used to schedule on single displays.
     * @param string $recurrenceType The type of recurrence to apply to this event, Available values : , Minute, Hour, Day, Week, Month, Year
     * @param int $recurrenceDetail The interval for the recurrence
     * @param string $recurrenceRange The end date for this events recurrence
     * @param int $displayOrder The display order for this event.
     * @param int $isPriority An integer indicating the priority of this event. Normal events have a priority of 0
     * @param int $syncTimezone Flag Should this schedule be synced to the resulting Display timezone
     * @return XiboSchedule
     */
    public function createEventCommand($fromDt, $commandId, $displayGroupIds, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority, $syncTimezone)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = 2;
        $this->fromDt = $fromDt;
        $this->commandId = $commandId;
        $this->displayGroupIds = $displayGroupIds;
        $this->recurrenceType = $recurrenceType;
        $this->recurrenceDetail = $recurrenceDetail;
        $this->recurrenceRange = $recurrenceRange;
        $this->displayOrder = $displayOrder;
        $this->isPriority = $isPriority;
        $this->syncTimezone = $syncTimezone;

        $this->getLogger()->info('Creating new scheduled command ');
        $response = $this->doPost('/schedule', $this->toArray());
        
        $this->getLogger()->debug('Passing the response to Hydrate ');
        return $this->hydrate($response);
    }

    /**
     * Create Overlay event.
     *
     * @param string $fromDt the FROM date for this event
     * @param string $toDt The TO date for this event
     * @param int $campaignId The Campaign ID to use for this Event. If a Layout is needed then the Campaign specific ID for that Layout should be used
     * @param array[int] $displayGroupIds Array of display Group IDs for this event, Display specific Display Group IDs should be used to schedule on single displays.
     * @param int $dayPartId The Day Part for this event. Overrides supported are 0(custom) and 1(always). Defaulted to 0
     * @param string $recurrenceType The type of recurrence to apply to this event, Available values : , Minute, Hour, Day, Week, Month, Year
     * @param int $recurrenceDetail The interval for the recurrence
     * @param string $recurrenceRange The end date for this events recurrence
     * @param int $displayOrder The display order for this event.
     * @param int $isPriority An integer indicating the priority of this event. Normal events have a priority of 0
     * @param int $syncTimezone Flag Should this schedule be synced to the resulting Display timezone
     * @return XiboSchedule
     */
    public function createEventOverlay($fromDt, $toDt, $campaignId, $displayGroupIds, $dayPartId, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority, $syncTimezone)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = 3;
        $this->fromDt = $fromDt;
        $this->toDt = $toDt;
        $this->campaignId = $campaignId;
        $this->displayGroupIds = $displayGroupIds;
        $this->dayPartId = $dayPartId;
        $this->recurrenceType = $recurrenceType;
        $this->recurrenceDetail = $recurrenceDetail;
        $this->recurrenceRange = $recurrenceRange;
        $this->displayOrder = $displayOrder;
        $this->isPriority = $isPriority;
        $this->syncTimezone = $syncTimezone;

        $this->getLogger()->info('Creating new scheduled Overlay layout event ');
        $response = $this->doPost('/schedule', $this->toArray());
       
        $this->getLogger()->debug('Passing the response to hydrate ');
        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param int $eventTypeId The Event Type Id to use for this Event. 1=Campaign, 2=Command, 3=Overlay
     * @param string $fromDt the FROM date for this event
     * @param string $toDt The TO date for this event
     * @param int $campaignId The Campaign ID to use for this Event. If a Layout is needed then the Campaign specific ID for that Layout should be used
     * @param int $commandId The Command ID to use for this Event
     * @param array[int] $displayGroupIds Array of display Group IDs for this event, Display specific Display Group IDs should be used to schedule on single displays.
     * @param int $dayPartId The Day Part for this event. Overrides supported are 0(custom) and 1(always). Defaulted to 0
     * @param string $recurrenceType The type of recurrence to apply to this event, Available values : , Minute, Hour, Day, Week, Month, Year
     * @param int $recurrenceDetail The interval for the recurrence
     * @param string $recurrenceRange The end date for this events recurrence
     * @param int $displayOrder The display order for this event.
     * @param int $isPriority An integer indicating the priority of this event. Normal events have a priority of 0
     * @param int $syncTimezone Flag Should this schedule be synced to the resulting Display timezone
     * @return XiboSchedule
     */
    public function edit($eventTypeId, $fromDt, $toDt, $campaignId, $commandId, $displayGroupIds, $dayPartId, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority, $syncTimezone)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = $eventTypeId;
        $this->fromDt = $fromDt;
        $this->toDt = $toDt;
        $this->campaignId = $campaignId;
        $this->commandId = $commandId;
        $this->displayGroupIds = $displayGroupIds;
        $this->dayPartId = $dayPartId;
        $this->recurrenceType = $recurrenceType;
        $this->recurrenceDetail = $recurrenceDetail;
        $this->recurrenceRange = $recurrenceRange;
        $this->displayOrder = $displayOrder;
        $this->isPriority = $isPriority;
        $this->syncTimezone = $syncTimezone;

        $this->getLogger()->info('Editing scheduled event ID ' . $this->eventId);
        $response = $this->doPut('/schedule/' . $this->eventId, $this->toArray());
        
        $this->getLogger()->debug('Passing the response to hydrate ');
        return $this->hydrate($response);
    }


    /**
     * Delete the scheduled event.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting scheduled event ID ' . $this->eventId);
        $this->doDelete('/schedule/' . $this->eventId);

        return true;
    }

}
