<?php
/**
 * Copyright (C) 2019 Xibo Signage Ltd
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

class XiboPermissions extends XiboEntity
{
    /** @var int The Permission ID */
    public $permissionId;

    /** @var int The Entity ID */
    public $entityId;

    /** @var int The Object ID */
    public $objectId;

    /** @var int isUser A flag indicating whether the groupId refers to a user specific group */
    public $isUser;

    /** @var string The entity name that this refers to */
    public $entity;

    /** @var string group The group name - can be user specific user group */
    public $group;

    /** @var int A flag indicating whether view permission is granted */
    public $view;

    /** @var int A flag indicating whether edit permission is granted */
    public $edit;

    /** @var int A flag indicating whether delete permission is granted */
    public $delete;

    /** @var int A flag indicating whether modify permission permission is granted */
    public $modifyPermissions;

    /** @var int The group ID */
    public $groupId;

    /** @var int The owner ID */
    public $ownerId;

    /**
     * Get permissions to specific entity.
     *
     * @param string $entity The Name of the entity Campaign, DataSet, DayPart, DisplayGroup, Media, Notification, Page, Playlist, Region, Widget
     * @param int $objectId the ID of the object to return permissions for
     * @return XiboPermissions|array
     */
    public function getPermissions($entity, $objectId)
    {
        $this->getLogger()->info('Getting Permissions arrays for Entity ' . $entity . ' with ID ' . $objectId);
        $response = $this->doGet('/user/permissions/' . $entity . '/' . $objectId);
        $entries = [];

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Set permissions to specific entity.
     *
     * @param string $entity The Name of the entity Campaign, DataSet, DayPart, DisplayGroup, Media, Notification, Page, Playlist, Region, Widget
     * @param int $objectId the ID of the object to return permissions for
     * @param int $groupId The group ID to which the permissions should be set for
     * @param int $view A flag indicating whether view permission should be granted
     * @param int $edit A flag indicating whether edit permission should be granted
     * @param int $delete A flag indicating whether delete permission should be granted
     * @param int $ownerId Change the owner of this item. Leave empty to keep the current owner
     * @return XiboPermissions
     */

    public function setPermissions($entity, $objectId, $groupId, $view, $edit, $delete, $ownerId = null)
    {
        $this->getLogger()->info('Setting Permissions view ' . $view . ' edit ' . $edit . ' delete ' . $delete . ' for user group ID ' . $groupId . ' to Entity ' . $entity . ' with ID ' . $objectId);

        $this->entity = $entity;
        $this->objectId = $objectId;
        $this->groupId = $groupId;
        $this->view = $view;
        $this->edit = $edit;
        $this->delete = $delete;
        $this->ownerId = $ownerId;

        $response = $this->doPost('/user/permissions/' . $entity . '/' . $objectId, [
            'groupIds' => [
               $groupId => [
                   'view' => $view,
                   'edit' => $edit,
                   'delete' => $delete
               ]
           ],
            'ownerId' => $ownerId
        ]);

        return $this->hydrate($response);
    }

}