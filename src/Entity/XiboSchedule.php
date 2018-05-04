<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboSchedule.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboSchedule extends XiboEntity
{
    public $id;
    public $title;
    public $url;
    public $start;
    public $end;
    public $sameDay;
    public $editable;
    public $event;
    public $scheduleEnvent;
    public $userId;
    public $fromDt;
    public $toDt;
    public $events;
    public $displaygroups;
    public $layouts;
    public $campaigns;
    public $eventId;
    public $eventTypeId;
    public $campaignId;
    public $campaign;
    public $commandId;
    public $command;
    public $displayOrder;
    public $isPriority;
    public $displayGroupIds;
    public $dayPartId;
    public $isAlways;
    public $isCustom;
    public $syncTimezone;
    public $recurrenceType;
    public $recurrenceDetail;
    public $recurrenceRange;
    public $recurrenceRepeatsOn;



 	/**
     * @param array $params
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
     * @param $displayGroupId
     * @param $date
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
     * @param array $params
     * @param $id
     * @return array $entries
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
     * Create Campaign/Layout event
     * @param $fromDt
     * @param $toDt
     * @param $campaignId
     * @param $displayGroupIds
     * @param $dayPartId
     * @param $recurrenceType
     * @param $recurrenceDetail
     * @param $recurrenceRange
     * @param $displayOrder
     * @param $isPriority
     * @return XiboSchedule
     */
    public function createEventLayout($fromDt, $toDt, $campaignId, $displayGroupIds, $dayPartId, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority)
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
        $this->getLogger()->info('Creating a new scheduled event ');
        $response = $this->doPost('/schedule', $this->toArray());
        
        $this->getLogger()->debug('Passing the response to hydrate');
        return $this->hydrate($response);
    }

    /**
     * Create Command event
     * @param $fromDt
     * @param $commandId
     * @param $displayGroupIds
     * @param $recurrenceType
     * @param $recurrenceDetail
     * @param $recurrenceRange
     * @param $displayOrder
     * @param $isPriority
     * @return XiboSchedule
     */
    public function createEventCommand($fromDt, $commandId, $displayGroupIds, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority)
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
        $this->getLogger()->info('Creating new scheduled command ');
        $response = $this->doPost('/schedule', $this->toArray());
        
        $this->getLogger()->debug('Passing the response to Hydrate ');
        return $this->hydrate($response);
    }

    /**
     * Create Overlay event
     * @param $fromDt
     * @param $toDt
     * @param $campaignId
     * @param $displayGroupIds
     * @param $dayPartId
     * @param $recurrenceType
     * @param $recurrenceDetail
     * @param $recurrenceRange
     * @param $displayOrder
     * @param $isPriority
     * @return XiboSchedule
     */
    public function createEventOverlay($fromDt, $toDt, $campaignId, $displayGroupIds, $dayPartId, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority)
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
        $this->getLogger()->info('Creating new scheduled Overlay layout event ');
        $response = $this->doPost('/schedule', $this->toArray());
       
        $this->getLogger()->debug('Passing the response to hydrate ');
        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $eventTypeId
     * @param $fromDt
     * @param $toDt
     * @param $campaignId
     * @param $commandId
     * @param $displayGroupIds
     * @param $dayPartId
     * @param $recurrenceType
     * @param $recurrenceDetail
     * @param $recurrenceRange
     * @param $displayOrder
     * @param $isPriority
     * @return XiboSchedule
     */
    public function edit($eventTypeId, $fromDt, $toDt, $campaignId, $commandId, $displayGroupIds, $dayPartId, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority)
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
        $this->getLogger()->info('Editing scheduled event ID ' . $this->eventId);
        $response = $this->doPut('/schedule/' . $this->eventId, $this->toArray());
        
        $this->getLogger()->debug('Passing the response to hydrate ');
        return $this->hydrate($response);
    }


    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting scheduled event ID ' . $this->eventId);
        $this->doDelete('/schedule/' . $this->eventId);

        return true;
    }

}
