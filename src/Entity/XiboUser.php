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

class XiboUser extends XiboEntity
{
    /**
     * @var int The ID of this User
     */
    public $userId;

    /**
     * @var string The user name
     */
    public $userName;

    /**
     * @var int The user type ID
     */
    public $userTypeId;

    /**
     * @var int Flag indicating whether this user is logged in or not
     */
    public $loggedIn;

    /**
     * @var string Email address of the user used for email alerts
     */
    public $email;

    /**
     * @var int The pageId of the Homepage for this User
     */
    public $homePageId;

    /**
     * @var int A timestamp indicating the time the user last logged into the CMS
     */
    public $lastAccessed;

    /**
     * @var int A flag indicating whether this user has see the new user wizard
     */
    public $newUserWizard;

    /**
     * @var int Flag indicating whether to hide the navigation
     */
    public $hideNavigation = 0;

    /**
     * @var int A flag indicating whether the user is retired
     */
    public $retired;

    /**
     * string User password
     */
    public $password;

    /**
     * string User New password
     */
    public $newPassword;

    /**
     * string User New password retyped
     */
    public $retypeNewPassword;

    /**
     * @var int A flag indicating whether password change should be forced for this user
     */
    public $isPasswordChangeRequired = 0;

    /**
     * @var int The users user group ID
     */
    public $groupId;

    /**
     * @var int The users group name
     */
    public $group;

    /**
     * @var int The users library quota in bytes
     */
    public $libraryQuota = 0;

    /**
     * @var string First Name
     */
    public $firstName;

    /**
     * @var string Last Name
     */
    public $lastName;

    /**
     * @var string Phone Number
     */
    public $phone;

    /**
     * @var string Reference field 1
     */
    public $ref1;

    /**
     * @var string Reference field 2
     */
    public $ref2;

    /**
     * @var string Reference field 3
     */
    public $ref3;

    /**
     * @var string Reference field 4
     */
    public $ref4;

    /**
     * @var string Reference field 5
     */
    public $ref5;

    /**
     * @var array An array of user groups this user is assigned to
     */
    public $groups = [];

    /**
     * @var array An array of Campaigns for this User
     */
    public $campaigns = [];

    /**
     * @var array An array of Layouts for this User
     */
    public $layouts = [];

    /**
     * @var array An array of Media for this user
     */
    public $media = [];

    /**
     * @var array An array of Scheduled Events for this User
     */
    public $events = [];

    /**
     * @var string The name of home page
     */
    public $homePage;

    /**
     * @var int Does this User receive system notifications.
     */
    public $isSystemNotification = 0;

    /**
     * @var int Does this User receive system notifications
     */
    public $isDisplayNotification = 0;

    /**
     * Get an array of Users.
     *
     * @param array $params can be filtered by: userId, userName, userTypeId, retired
     * @return array|XiboUser
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting list of Users ');
        $entries = [];
        $response = $this->doGet('/user', $params);
        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Get the User by user ID.
     *
     * @param int $id userId to search for
     * @return XiboUser
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $this->getLogger()->info('Getting User by user ID ' . $id);
        $response = $this->doGet('/user', [
            'userId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Get Me
     *
     * @return XiboUser
     */
    public function getMe()
    {
        $this->getLogger()->info('Getting list of Users ');
        $response = $this->doGet('/user/me');

        return clone $this->hydrate($response);
    }

    /**
     * Create a new User.
     *
     * @param string $userName The User Name
     * @param string $email The User email address
     * @param int $userTypeId The user type id
     * @param int $homePageId The homepage ID to use for this User
     * @param int $libraryQuota The Users library quota in kilobytes
     * @param string $password The User password
     * @param int $groupId The initial user group ID for this user
     * @param int $newUserWizard Flag indicating whether to show the new user guide
     * @param int $hideNavigation Flag indicating whether to hide the navigation
     * @param int $isPasswordChangeRequired A flag indicating whether password change should be forced for this user
     * @param string $firstName The User first name
     * @param string $lastName The User Last name
     * @param string $phone The user phone number
     * @param string $ref1 Reference 1
     * @param string $ref2 Reference 2
     * @param string $ref3 Reference 3
     * @param string $ref4 Reference 4
     * @param string $ref5 Reference 5
     *
     * @return XiboUser
     */
    public function create($userName, $userTypeId, $homePageId, $password, $groupId, $newUserWizard, $hideNavigation, $email= '', $libraryQuota = 0, $isPasswordChangeRequired = 0, $firstName = '', $lastName = '', $phone = '', $ref1 = '', $ref2 = '', $ref3 = '', $ref4 = '', $ref5 = '')
    {
        $this->userName = $userName;
        $this->userTypeId = $userTypeId;
        $this->homePageId = $homePageId;
        $this->password = $password;
        $this->groupId = $groupId;
        $this->newUserWizard = $newUserWizard;
        $this->hideNavigation = $hideNavigation;
        $this->email = $email;
        $this->libraryQuota = $libraryQuota;
        $this->isPasswordChangeRequired = $isPasswordChangeRequired;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->ref1 = $ref1;
        $this->ref2 = $ref2;
        $this->ref3 = $ref3;
        $this->ref4 = $ref4;
        $this->ref5 = $ref5;

        $this->getLogger()->info('Creating User ' . $this->userName);
        $response = $this->doPost('/user', $this->toArray());

        $this->getLogger()->debug('Passing the response to Hydrate');
        /** @var XiboUser $user */
        $user = $this->hydrate($response);

        return $user;
    }

    /**
     * Edit an existing User.
     *
     * @param int $userId The User ID to edit
     * @param string $userName The User Name
     * @param int $userTypeId The user type id
     * @param int $homePageId The homepage ID to use for this User
     * @param string $newPassword New Password for the User
     * @param string $retypeNewPassword New Password for the User retyped
     * @param int $newUserWizard Flag indicating whether to show the new user guide
     * @param int $hideNavigation Flag indicating whether to hide the navigation
     * @param string $email The User email address
     * @param int $libraryQuota The Users library quota in kilobytes
     * @param int $isPasswordChangeRequired A flag indicating whether password change should be forced for this user
     * @param string $firstName The User first name
     * @param string $lastName The User Last name
     * @param string $phone The user phone number
     * @param string $ref1 Reference 1
     * @param string $ref2 Reference 2
     * @param string $ref3 Reference 3
     * @param string $ref4 Reference 4
     * @param string $ref5 Reference 5
     *
     * @return XiboUser
     */
    public function edit($userId, $userName, $userTypeId, $homePageId, $newUserWizard, $hideNavigation, $newPassword = '', $retypeNewPassword = '', $email= '', $libraryQuota = 0, $isPasswordChangeRequired = 0, $firstName = '', $lastName = '', $phone = '', $ref1 = '', $ref2 = '', $ref3 = '', $ref4 = '', $ref5 = '')
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->userTypeId = $userTypeId;
        $this->homePageId = $homePageId;
        $this->newPassword = $newPassword;
        $this->retypeNewPassword = $retypeNewPassword;
        $this->newUserWizard = $newUserWizard;
        $this->hideNavigation = $hideNavigation;
        $this->email = $email;
        $this->libraryQuota = $libraryQuota;
        $this->isPasswordChangeRequired = $isPasswordChangeRequired;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->ref1 = $ref1;
        $this->ref2 = $ref2;
        $this->ref3 = $ref3;
        $this->ref4 = $ref4;
        $this->ref5 = $ref5;

        $this->getLogger()->info('Editing User ID ' . $userId);
        $response = $this->doPut('/user/' . $userId, $this->toArray());

        $this->getLogger()->debug('Passing the response to Hydrate');
        /** @var XiboUser $user */
        $user = $this->hydrate($response);

        return $user;
    }

    /**
     * Delete the User.
     *
     * @param int $deleteAllItems Flag indicating whether to delete all items owned by that user
     * @param int $reassignUserId Reassign all items owned by this user to the specified user ID
     * @return bool
     */
    public function delete($deleteAllItems = 0, $reassignUserId = null)
    {
        $this->getLogger()->info('Deleting User ID ' . $this->userId);
        $this->doDelete('/user/' . $this->userId, [
            'deleteAllItems' => $deleteAllItems,
            'reassignUserId' => $reassignUserId
        ]);

        return true;
    }
}