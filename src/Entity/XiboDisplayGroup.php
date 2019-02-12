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

/**
 * Class XiboDisplayGroup
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboDisplayGroup extends XiboEntity
{
    /** @var int The Display Group ID */
    public $displayGroupId;

    /** @var string The Display Group Name */
    public $displayGroup;

    /** @var string The Display Group Description */
    public $description;

    /** @var string A comma separated list of tags for display group */
    public $tags;

    /** @var int Flag whether the Display Group belongs to a display or is user created */
    public $isDisplaySpecific = 0;

    /** @var int Flag indicating whether this Display Group is dynamic */
    public $isDynamic = 0;

    /** @var string The filter criteria for this dynamic group. A comma separated set of regular expressions to apply */
    public $dynamicCriteria;

    /** @var int The User ID */
    public $userId = 0;


    /**
     * Get a list of Display Groups.
     *
     * @param array $params can be filtered by: displayGroupId, displayGroup, displayId, nestedDisplayId, dynamicCriteria, isDisplaySpecific, forSchedule
     * @return array[XiboDisplayGroup]
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting list of Display Groups');
        $entries = [];
        $response = $this->doGet('/displaygroup', $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * Get Display Group by Id.
     *
     * @param int $id the Display Group ID
     * @return $this|XiboDisplayGroup
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $this->getLogger()->info('Getting Display Group ID ' . $id);
        $response = $this->doGet('/displaygroup', [
            'displayGroupId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single display group, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create Display Group.
     *
     * @param string $displayGroup The display group name
     * @param string $description The display group description
     * @param int $isDynamic Flag indicating whether this Display Group is dynamic
     * @param string $dynamicCriteria The filter criteria for this dynamic group. A comma separated set of regular expressions to apply
     * @return XiboDisplayGroup
     */
    public function create($displayGroup, $description, $isDynamic, $dynamicCriteria)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->displayGroup = $displayGroup;
        $this->description = $description;
        $this->isDynamic = $isDynamic;
        $this->dynamicCriteria = $dynamicCriteria;

        $this->getLogger()->info('Creating a new display Group ' . $displayGroup);
        $response = $this->doPost('/displaygroup', $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param string $displayGroup The display group name
     * @param string $description The display group description
     * @param int $isDynamic Flag indicating whether this Display Group is dynamic
     * @param string $dynamicCriteria The filter criteria for this dynamic group. A comma separated set of regular expressions to apply
     * @return XiboDisplayGroup
     */
    public function edit($displayGroup, $description, $isDynamic, $dynamicCriteria)
    {
        $this->displayGroup = $displayGroup;
        $this->description = $description;
        $this->isDynamic = $isDynamic;
        $this->dynamicCriteria = $dynamicCriteria;

        $this->getLogger()->info('Editing Display Group ID ' . $this->displayGroupId);
        $response = $this->doPut('/displaygroup/' . $this->displayGroupId, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Delete the display group.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting Display Group ID ' . $this->displayGroupId);
        $this->doDelete('/displaygroup/' . $this->displayGroupId);

        return true;
    }
    
    /**
     * Assign display to the display group.
     *
     * @param array|int $displayId Display ID to assign
     * @return XiboDisplayGroup
     */
    public function assignDisplay($displayId)
    {
        $this->getLogger()->info('Assigning display ID ' . json_encode($displayId) . ' To display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/display/assign', [
            'displayId' => $displayId
            ]);

        return $this;
    }

    /**
     * Unassign display from display group.
     *
     * @param array|int $displayId The Display ID to unassign
     * @return XiboDisplayGroup
     */
    public function unassignDisplay($displayId)
    {
        $this->getLogger()->info('Unassigning display ID ' . json_encode($displayId) . ' From display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/display/unassign', [
            'displayId' => $displayId
            ]);

        return $this;
    }

    /**
     * Assign display group to a display group.
     *
     * @param array|int $displayGroupId The Display Group ID to assign
     * @return XiboDisplayGroup
     */
    public function assignDisplayGroup($displayGroupId)
    {
        $this->getLogger()->info('Assigning display Group ID ' . json_encode($displayGroupId) . ' To display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/displayGroup/assign', [
        'displayGroupId' => $displayGroupId
        ]);
        return $this;
    }

    /**
     * Unassign display group from display group.
     *
     * @param array|int $displayGroupId The Display Group ID to unassign
     * @return XiboDisplayGroup
     */
    public function unassignDisplayGroup($displayGroupId)
    {
        $this->getLogger()->info('Unassigning display Group ID ' . json_encode($displayGroupId) . ' From display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/displayGroup/unassign', [
        'displayGroupId' => $displayGroupId
        ]);
        return $this;
    }

    /**
     * Assign layout to display Group.
     *
     * @param array|int $layoutId The Layout ID to assign
     * @return XiboDisplayGroup
     */
    public function assignLayout($layoutId)
    {
        $this->getLogger()->info('Assigning Layout ID ' . json_encode($layoutId) . ' To display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/layout/assign', [
            'layoutId' => $layoutId
            ]);

        return $this;
    }

    /**
     * Unassign layout from display group.
     *
     * @param array|int $layoutId The Layout ID to unassign
     * @return XiboDisplayGroup
     */
    public function unassignLayout($layoutId)
    {
        $this->getLogger()->info('Unassigning Layout ID ' . json_encode($layoutId) . ' From display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/layout/unassign', [
            'layoutId' => $layoutId
            ]);

        return $this;
    }

    /**
     * Assign media to display group.
     *
     * @param array|int $mediaId Media ID to assign
     * @return XiboDisplayGroup
     */
    public function assignMedia($mediaId)
    {
        $this->getLogger()->info('Assigning media ID ' . json_encode($mediaId) . ' To display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/media/assign', [
            'mediaId' => $mediaId
            ]);

        return $this;
    }

    /**
     * Unassign media from display group.
     *
     * @param array|int $mediaId Media ID to unassign
     * @return XiboDisplayGroup
     */
    public function unassignMedia($mediaId)
    {
        $this->getLogger()->info('Unassigning media ID ' . json_encode($mediaId) . ' From display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/media/unassign', [
            'mediaId' => $mediaId
            ]);

        return $this;
    }

    /**
     * Collect Now.
     *
     * Issue a CollectNow action to the displayGroup
     */
    public function collectNow()
    {
        $this->getLogger()->info('Sending Collect Now action to display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/action/collectNow');

        return $this;
    }

    /**
     * Clear Stats and logs.
     *
     * Issue a clearStatsAndLogs action to the displayGroup
     */
    public function clear()
    {
        $this->getLogger()->info('Sending Clear All stats and logs action to display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/action/clearStatsAndLogs');

        return $this;
    }

    /**
     * ChangeLayout.
     *
     * Issue changeLayout action to the display Group
     *
     * @param int $layoutId Layout ID
     * @param int $duration Duration in seconds for this layout change to remain in effect
     * @param int $downloadRequired Flag indicating whether the player should perform a collect before playing the layout
     * @param string $changeMode Whether to queue or replace layout with this action
     * @return XiboDisplayGroup
     */
    public function changeLayout($layoutId, $duration, $downloadRequired, $changeMode)
    {
        $this->getLogger()->info('Sending Change Layout action to display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/action/changeLayout', [
            'layoutId' => $layoutId,
            'duration' => $duration,
            'downloadRequired' => $downloadRequired,
            'changeMode' => $changeMode
        ]);

        return $this;
    }
    /**
     * Revert to Schedule.
     *
     * Issue revertToSchedule action to the display Group
     */
    public function revertToSchedule()
    {
        $this->getLogger()->info('Sending Revert to Schedule action to display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/action/revertToSchedule');

        return $this;
    }

    /**
     * Overlay Layout.
     *
     * Issue overLayout action to the display group
     *
     * @param int $layoutId Layout ID
     * @param int $duration Duration in seconds for this layout change to remain in effect
     * @param int $downloadRequired Flag indicating whether the player should perform a collect before playing the layout
     * @return XiboDisplayGroup
     */
    public function overlayLayout($layoutId, $duration, $downloadRequired)
    {
        $this->getLogger()->info('Sending Overlay Layout action to display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/action/overlayLayout', [
            'layoutId' => $layoutId,
            'duration' => $duration,
            'downloadRequired' => $downloadRequired
        ]);

        return $this;
    }

    /**
     * Send a command to the display group.
     *
     * @param int $commandId The Command ID
     * @return XiboDisplayGroup
     */
    public function command($commandId)
    {
        $this->getLogger()->info('Sending predefined Command to display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/action/command', [
            'commandId' => $commandId,
        ]);

        return $this;
    }
}
