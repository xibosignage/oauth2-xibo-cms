<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboNotification.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;
class XiboNotification extends XiboEntity
{
	private $url = '/notification';
	public $notificationId;
    public $createdDt;
    public $releaseDt;
    public $subject;
    public $body;
    public $isEmail;
    public $isInterrupt;
    public $isSystem;
    public $userId;
    public $displayGroupId;

    /**
     * @param array $params
     * @return array[XiboNotification]
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting a list of Notifications');
        $entries = [];
        $response = $this->doGet('/notification', $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Create
     * @param $subject
     * @param $body
     * @param $releaseDt
     * @param $isEmail
     * @param $isInterrupt
     * @param $displayGroupIds
     * @param $userGroupIds
     * @return XiboNotification
     */
    public function create($subject, $body, $releaseDt, $isEmail, $isInterrupt, $displayGroupIds = [], $userGroupIds = [])
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->subject = $subject;
        $this->body = $body;
        $this->releaseDt = $releaseDt;
        $this->isEmail = $isEmail;
        $this->isInterrupt = $isInterrupt;
        $this->displayGroupIds = $displayGroupIds;
        $this->userGroupIds = $userGroupIds;

        $this->getLogger()->info('Creating Notification ' . $subject);
        $response = $this->doPost('/notification', $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $notificationId
     * @param $subject
     * @param $body
     * @param $releaseDt
     * @param $isEmail
     * @param $isInterrupt
     * @param $displayGroupIds
     * @param $userGroupIds
     * @return XiboNotification
     */
    public function edit($notificationId, $subject, $body, $releaseDt, $isEmail, $isInterrupt, $displayGroupIds = [], $userGroupIds = [])
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->notificationId = $notificationId;
        $this->subject = $subject;
        $this->body = $body;
        $this->releaseDt = $releaseDt;
        $this->isEmail = $isEmail;
        $this->isInterrupt = $isInterrupt;
        $this->displayGroupIds = $displayGroupIds;
        $this->userGroupIds = $userGroupIds;
        
        $this->getLogger()->info('Editing Notification ID' . $notificationId);
        $response = $this->doPut('/notification', $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/notification/' . $this->notificationId);
        return true;
    }
}