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
class XiboNotification extends XiboEntity
{
	private $url = '/notification';

    /** @var int The Notification ID */
	public $notificationId;

    /** @var string Date the notification was created */
    public $createdDt;

    /** @var string ISO date representing the release date for this notification */
    public $releaseDt;

    /** @var string Notification subject */
    public $subject;

    /** @var string The body of the notification message */
    public $body;

    /** @var int Flag indicating whether this notification should be emailed.*/
    public $isEmail;

    /** @var int Flag indicating whether this notification should interrupt the web portal nativation/login */
    public $isInterrupt;

    /** @var int Flag indicating whether this notification is a system notification */
    public $isSystem;

    /** @var int The User ID */
    public $userId;

    /** @var array[int] The display group ids to assign this notification to */
    public $displayGroupIds;

    /** @var array[int] The user group ids to assign to this notification */
    public $userGroupIds;

    /**
     * Get a list of notifications
     *
     * @param array $params can be filtered by: notificationId, subject
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
     * Create new Notification.
     *
     * @param string $subject The Notification Subject
     * @param string $body The Notification message
     * @param string $releaseDt ISO date representing the release date for this notification
     * @param int $isEmail Flag indicating whether this notification should be emailed
     * @param int $isInterrupt Flag indicating whether this notification should interrupt the web portal nativation/login
     * @param array[int] $displayGroupIds The display group ids to assign this notification to
     * @param array[int] $userGroupIds The user group ids to assign to this notification
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
     * Edit the Notification.
     *
     * @param int $notificationId Notification ID
     * @param string $subject The Notification Subject
     * @param string $body The Notification message
     * @param string $releaseDt ISO date representing the release date for this notification
     * @param int $isEmail Flag indicating whether this notification should be emailed
     * @param int $isInterrupt Flag indicating whether this notification should interrupt the web portal nativation/login
     * @param array[int] $displayGroupIds The display group ids to assign this notification to
     * @param array[int] $userGroupIds The user group ids to assign to this notification
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
     * Delete the Notification.
     *
     * @return bool
     */
    public function delete()
    {
        $this->doDelete('/notification/' . $this->notificationId);
        return true;
    }
}