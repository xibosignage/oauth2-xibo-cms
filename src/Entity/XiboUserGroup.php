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

class XiboUserGroup extends XiboEntity
{
    /**
     * @var int The Group ID
     */
    public $groupId;

    /**
     * @var string The group name
     */
    public $group;

    /**
     * @var int A flag indicating whether this is a user specific group or not
     */
    public $isUserSpecific = 0;

    /**
     * @var int A flag indicating the special everyone group
     */
    public $isEveryone = 0;

    /**
     * @var int This users library quota in bytes. 0 = unlimited
     */
    public $libraryQuota = 0;

    /**
     * @var int Does this Group receive system notifications.
     */
    public $isSystemNotification = 0;

    /**
     * @var int Does this Group receive display notifications.
     */
    public $isDisplayNotification = 0;

    /**
     * @var int Flag indicating whether to copy group members.
     */
    public $copyMembers = 0;

    /**
     * Get an array of User Groups.
     *
     * @param array $params can be filtered by: userGroupId, userGroup
     * @return array|XiboUserGroup
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting list of User Groups ');
        $entries = [];

        $response = $this->doGet('/group', $params);
        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Get the User Group by user group ID.
     *
     * @param int $id userGroupId to search for
     * @return XiboUserGroup
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $this->getLogger()->info('Getting User Group by ID ' . $id);
        $response = $this->doGet('/group', [
            'userGroupId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create a new User Group.
     *
     * @param string $group The Group Name
     * @param int $libraryQuota The User Group library quota in kilobytes
     * @param int $isSystemNotification Flag (0, 1), should members of this Group receive system notifications?
     * @param int $isDisplayNotification Flag (0, 1), should members of this Group receive Display notifications for Displays they have permissions to see
     * @return XiboUserGroup
     */
    public function create($group, $libraryQuota = 0, $isSystemNotification = 0, $isDisplayNotification = 0)
    {
        $this->group = $group;
        $this->libraryQuota = $libraryQuota;
        $this->isSystemNotification = $isSystemNotification;
        $this->isDisplayNotification = $isDisplayNotification;

        $this->getLogger()->info('Creating User Group ' . $this->group);
        $response = $this->doPost('/group', $this->toArray());

        $this->getLogger()->debug('Passing the response to Hydrate');
        /** @var XiboUserGroup $userGroup */
        $userGroup = $this->hydrate($response);

        return $userGroup;
    }

    /**
     * Edit an existing User Group.
     *
     * @param int $groupId The ID of the group to edit
     * @param string $group The Group Name
     * @param int $libraryQuota The User Group library quota in kilobytes
     * @param int $isSystemNotification Flag (0, 1), should members of this Group receive system notifications?
     * @param int $isDisplayNotification Flag (0, 1), should members of this Group receive Display notifications for Displays they have permissions to see
     * @return XiboUserGroup
     */
    public function edit($groupId, $group, $libraryQuota = 0, $isSystemNotification = 0, $isDisplayNotification = 0)
    {
        $this->groupId = $groupId;
        $this->group = $group;
        $this->libraryQuota = $libraryQuota;
        $this->isSystemNotification = $isSystemNotification;
        $this->isDisplayNotification = $isDisplayNotification;

        $this->getLogger()->info('Editing User Group ID ' . $this->groupId);
        $response = $this->doPut('/group/' . $groupId, $this->toArray());

        $this->getLogger()->debug('Passing the response to Hydrate');
        /** @var XiboUserGroup $userGroup */
        $userGroup = $this->hydrate($response);

        return $userGroup;
    }

    /**
     * Delete the User Group
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting User Group ID ' . $this->groupId);
        $this->doDelete('/group/' . $this->groupId);

        return true;
    }

    /**
     * Assign User to a User Group.
     *
     * @param int $userGroupId The ID of the user group
     * @param array|int $userId The ID of the user
     *
     * @return XiboUserGroup
     */
    public function assignUser($userGroupId, $userId = [])
    {
        $this->groupId = $userGroupId;

        $this->getLogger()->info('Assigning User IDs ' . json_encode($userId) . ' To User Group ID ' . $this->groupId);
        $response = $this->doPost('/group/members/assign/' . $userGroupId, [
            'userId' => $userId
        ]);

        /** @var XiboUserGroup $userGroup */
        $userGroup = $this->hydrate($response);

        return $userGroup;
    }

    /**
     * Unassign User to a User Group.
     *
     * @param int $userGroupId The ID of the user group
     * @param array|int $userId The ID of the user
     *
     * @return XiboUserGroup
     */
    public function unassignUser($userGroupId, $userId = [])
    {
        $this->groupId = $userGroupId;

        $this->getLogger()->info('Unassigning User IDs ' . json_encode($userId) . ' From User Group ID ' . $this->groupId);
        $response = $this->doPost('/group/members/unassign/' . $userGroupId, [
            'userId' => $userId
        ]);

        /** @var XiboUserGroup $userGroup */
        $userGroup = $this->hydrate($response);

        return $userGroup;
    }

    /**
     * Copy User Group.
     *
     * @param int $userGroupId The ID of the user group
     * @param string $group Group Name
     * @param int $copyMembers Flag indicating whether to copy group members
     *
     * @return XiboUserGroup
     */
    public function copy($userGroupId, $group, $copyMembers = 0)
    {
        $this->groupId = $userGroupId;
        $this->copyMembers = $copyMembers;

        $this->getLogger()->info('Creating a copy of User Group ID ' . $userGroupId);
        $response = $this->doPost('/group/' . $userGroupId . '/copy', [
            'group' => $group,
            'copyMembers' => $copyMembers
        ]);

        /** @var XiboUserGroup $userGroup */
        $userGroup = $this->hydrate($response);

        return $userGroup;
    }
}