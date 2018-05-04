<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDisplayGroup.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

/**
 * Class XiboDisplayGroup
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboDisplayGroup extends XiboEntity
{
    public $displayGroupId;
    public $displayGroup;
    public $description;
    public $isDisplaySpecific = 0;
    public $isDynamic = 0;
    public $dynamicCriteria;
    public $userId = 0;
    public $duration;
    public $changeMode;
    public $downloadRequired;
    public $commandId;
    public $layoutId;
    public $mediaId;

    /**
     * @param array $params
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
     * Get by Id
     * @param $id
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
     * Create
     * @param $displayGroup
     * @param $description
     * @param $isDynamic
     * @param $dynamicCriteria
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
     * @param $displayGroup
     * @param $description
     * @param $isDynamic
     * @param $dynamicCriteria
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
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting Display Group ID ' . $this->displayGroupId);
        $this->doDelete('/displaygroup/' . $this->displayGroupId);

        return true;
    }
    
    /**
     * Assign display
     * @param $displayId
     * @return XiboDisplayGroup
     */
    public function assignDisplay($displayId)
    {
        $this->getLogger()->info('Assigning display ID ' . $displayId . ' To display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/display/assign', [
            'displayId' => $displayId
            ]);

        return $this;
    }

    /**
     * Unassign display
     * @param $displayId
     * @return XiboDisplayGroup
     */
    public function unassignDisplay($displayId)
    {
        $this->getLogger()->info('Unassigning display ID ' . $displayId . ' From display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/display/unassign', [
            'displayId' => $displayId
            ]);

        return $this;
    }

    /**
     * Assign display group
     * @param int $displayGroupId
     * @return XiboDisplayGroup
     */
    public function assignDisplayGroup($displayGroupId)
    {
        $this->getLogger()->info('Assigning display Group ID ' . $displayGroupId . ' To display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/displayGroup/assign', [
        'displayGroupId' => $displayGroupId
        ]);
        return $this;
    }

    /**
     * Unassign display group
     * @param int $displayGroupId
     * @return XiboDisplayGroup
     */
    public function unassignDisplayGroup($displayGroupId)
    {
        $this->getLogger()->info('Unassigning display Group ID ' . $displayGroupId . ' From display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/displayGroup/unassign', [
        'displayGroupId' => $displayGroupId
        ]);
        return $this;
    }

    /**
     * Assign layout
     * @param $layoutId
     * @return XiboDisplayGroup
     */
    public function assignLayout($layoutId)
    {
        $this->getLogger()->info('Assigning Layout ID ' . $layoutId . ' To display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/layout/assign', [
            'layoutId' => $layoutId
            ]);

        return $this;
    }

    /**
     * Unassign layout
     * @param $layoutId
     * @return XiboDisplayGroup
     */
    public function unassignLayout($layoutId)
    {
        $this->getLogger()->info('Unassigning Layout ID ' . $layoutId . ' From display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/layout/unassign', [
            'layoutId' => $layoutId
            ]);

        return $this;
    }

    /**
     * Assign media
     * @param $mediaId
     * @return XiboDisplayGroup
     */
    public function assignMedia($mediaId)
    {
        $this->getLogger()->info('Assigning media ID ' . $mediaId . ' To display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/media/assign', [
            'mediaId' => $mediaId
            ]);

        return $this;
    }

    /**
     * Unassign media
     * @param $mediaId
     * @return XiboDisplayGroup
     */
    public function unassignMedia($mediaId)
    {
        $this->getLogger()->info('Unassigning media ID ' . $mediaId . ' From display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/media/unassign', [
            'mediaId' => $mediaId
            ]);

        return $this;
    }

    /**
     * Version Instructions
     * @param $mediaId
     * @return XiboDisplayGroup
     */
    public function version($mediaId)
    {
        $this->getLogger()->info('Assigning media ID ' . $mediaId . ' To display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/version', [
            'mediaId' => $mediaId
            ]);

        return $this;
    }

    /**
     * Collect Now
     */
    public function collectNow()
    {
        $this->getLogger()->info('Sending Collect Now action to display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/action/collectNow');

        return $this;
    }

    /**
     * Clear Stats and logs
     */
    public function clear()
    {
        $this->getLogger()->info('Sending Clear All stats and logs action to display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/action/clearStatsAndLogs');

        return $this;
    }

    /**
     * ChangeLayout
     * @param $layoutId
     * @param $duration
     * @param $downloadRequired
     * @param $changeMode
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
     * Revert to Schedule
     */
    public function revertToSchedule()
    {
        $this->getLogger()->info('Sending Revert to Schedule action to display Group ID ' . $this->displayGroupId);
        $this->doPost('/displaygroup/' . $this->displayGroupId . '/action/revertToSchedule');

        return $this;
    }

    /**
     * Overlay Layout
     * @param $layoutId
     * @param $duration
     * @param $downloadRequired
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
     * Command
     * @param $commandId
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
