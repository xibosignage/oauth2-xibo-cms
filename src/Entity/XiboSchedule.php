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
	private $url = '/schedule';
    private $url2 = '/schedule/data/events';
	public $eventId;
	public $eventTypeId;
	public $toDt;
	public $fromDt;
	public $isPriority;
	public $displayOrder;
	public $title;
	public $campaign;
	public $campaignId;
	public $command;
	public $commandId;
	public $displayGroupId;
	public $userId;
	public $recurrenceType;
	public $recurrenceDetail;
	public $recurrenceRange;
	public $dayPartId;

 	/**
     * @param array $params
     * @return array|XiboSchedule
     */
    public function get(array $params = [])
    {
        $entries = [];
        $response = $this->doGet($this->url2, $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * @param $id
     * @return XiboSchedule
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet($this->url2, [
            'eventId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create Campaign/Layout event
     * @param $eventTypeId
     * @param $fromDt
     * @param $toDt
     * @param $campaignId
     * @param $displaygroupIds
     * @param $dayPartId
     * @param $recurrenceType
     * @param $recurrenceDetail
     * @param $recurrenceRange
     * @param $displayOrder
     * @param $isPriority
     * @return XiboSchedule
     */
    public function createEventLayout($fromDt, $toDt, $campaignId, $displaygroupIds, $dayPartId, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = 1;
        $this->fromDt = $fromDt;
        $this->toDt = $toDt;
        $this->campaignId = $campaignId;
        $this->displayGroupIds = $displaygroupIds;
        $this->dayPartId = $dayPartId;
        $this->recurrenceType = $recurrenceType;
        $this->recurrenceDetail = $recurrenceDetail;
        $this->recurrenceRange = $recurrenceRange;
        $this->displayOrder = $displayOrder;
        $this->isPriority = $isPriority;
        $response = $this->doPost('/schedule', $this->toArray());
       
        return $this->hydrate($response);
    }

    /**
     * Create Command event
     * @param $eventTypeId
     * @param $fromDt
     * @param $commandId
     * @param $displaygroupIds
     * @param $recurrenceType
     * @param $recurrenceDetail
     * @param $recurrenceRange
     * @param $displayOrder
     * @param $isPriority
     * @return XiboSchedule
     */
    public function createEventCommand($fromDt, $commandId, $displaygroupIds, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = 2;
        $this->fromDt = $fromDt;
        $this->commandId = $commandId;
        $this->displayGroupIds = $displaygroupIds;
        $this->recurrenceType = $recurrenceType;
        $this->recurrenceDetail = $recurrenceDetail;
        $this->recurrenceRange = $recurrenceRange;
        $this->displayOrder = $displayOrder;
        $this->isPriority = $isPriority;
        $response = $this->doPost('/schedule', $this->toArray());
       
        return $this->hydrate($response);
    }

    /**
     * Create Overlay event
     * @param $eventTypeId
     * @param $fromDt
     * @param $toDt
     * @param $campaignId
     * @param $displaygroupIds
     * @param $dayPartId
     * @param $recurrenceType
     * @param $recurrenceDetail
     * @param $recurrenceRange
     * @param $displayOrder
     * @param $isPriority
     * @return XiboSchedule
     */
    public function createEvetOverlay($fromDt, $toDt, $campaignId, $displaygroupIds, $dayPartId, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = 3;
        $this->fromDt = $fromDt;
        $this->toDt = $toDt;
        $this->campaignId = $campaignId;
        $this->displayGroupIds = $displaygroupIds;
        $this->dayPartId = $dayPartId;
        $this->recurrenceType = $recurrenceType;
        $this->recurrenceDetail = $recurrenceDetail;
        $this->recurrenceRange = $recurrenceRange;
        $this->displayOrder = $displayOrder;
        $this->isPriority = $isPriority;
        $response = $this->doPost('/schedule', $this->toArray());
       
        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $eventTypeId
     * @param $fromDt
     * @param $toDt
     * @param $campaignId
     * @param $commandId
     * @param $displaygroupIds
     * @param $dayPartId
     * @param $recurrenceType
     * @param $recurrenceDetail
     * @param $recurrenceRange
     * @param $displayOrder
     * @param $isPriority
     * @return XiboSchedule
     */
    public function edit($eventTypeId, $fromDt, $toDt, $campaignId,$commandId, $displaygroupIds, $dayPartId, $recurrenceType, $recurrenceDetail, $recurrenceRange, $displayOrder, $isPriority)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->eventTypeId = $eventTypeId;
        $this->fromDt = $fromDt;
        $this->toDt = $toDt;
        $this->campaignId = $campaignId;
        $this->commandId = $commandId;
        $this->displayGroupIds = $displaygroupIds;
        $this->dayPartId = $dayPartId;
        $this->recurrenceType = $recurrenceType;
        $this->recurrenceDetail = $recurrenceDetail;
        $this->recurrenceRange = $recurrenceRange;
        $this->displayOrder = $displayOrder;
        $this->isPriority = $isPriority;
        $response = $this->doPut('/schedule/' . $this->eventId, $this->toArray());
       
        return $this->hydrate($response);
    }


    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/schedule/' . $this->eventId);
        return true;
    }

}
