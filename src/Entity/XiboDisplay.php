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
 * Class XiboDisplay
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboDisplay extends XiboEntity
{
    /** @var int The Display ID */
    public $displayId;

    /** @var string The display name */
    public $display;

    /** @var string The display description */
    public $description;

    /**
     * @var string Tags associated with this Display
     */
    public $tags;

    /** @var string Date this Display records auditing information until */
    public $auditingUntil;

    /** @var int The ID of the default layout for this display */
    public $defaultLayoutId = 0;

    /** @var string The name of the default for this display */
    public $defaultLayout;

    /** @var int HardwareKey for this Display */
    public $license;

    /** @var string Flag indicating whether this display is authorised with CMS */
    public $licensed;

    /** @var int Display logged in status */
    public $loggedIn;

    /** @var int last accessed timestamp */
    public $lastAccessed;

    /** @var int Flag indicating whether the default layout should be included in the Schedule */
    public $incSchedule;

    /** @var int Flag indicating whether the Display generated up/down email alerts */
    public $emailAlert;

    /** @var int How long in seconds should this display wait before alerting when it hasn't connected - Overrides for the collection interval */
    public $alertTimeout;

    /** @var string display IP address */
    public $clientAddress;

    /** @var int Display Status */
    public $mediaInventoryStatus;

    /** @var string Display MAC address */
    public $macAddress;

    /** @var int Flag indicating whether Wake On Lan is enabled for this display */
    public $wakeOnLanEnabled;

    /** @var string A h:i string representing the time that the Display should receive its Wake on LAN command */
    public $wakeOnLanTime;

    /** @var int Timestamp of the last WoL command sent to the display */
    public $lastWakeOnLanCommandSent;

    /** @var string The BroadCast Address for this display - used by WoL */
    public $broadCastAddress;

    /** @var string The secure on configuration for this display */
    public $secureOn;

    /** @var string The CIDR configuration for this display */
    public $cidr;

    /** @var double The latitude of this display */
    public $latitude;

    /** @var double The longitude of this display */
    public $longitude;

    /** @var string The timezone for this display, leave empty to use CMS timezone */
    public $timeZone;

    /** @var string The Display Type */
    public $clientType;

    /** @var string The Display Version */
    public $clientVersion;

    /** @var int the Display code */
    public $clientCode;

    /** @var int The Display Profile ID */
    public $displayProfileId;

    /** @var int The ID of the current layout */
    public $currentLayoutId;

    /** @var string The name of the current layout */
    public $currentLayout;

    /** @var int the status of Screen Shot Request action */
    public $screenShotRequested;

    /** @var string the available storage */
    public $storageAvailableSpace;

    /** @var string the total storage */
    public $storageTotalSpace;

    /** @var int The display Group ID */
    public $displayGroupId;

    /** @var string The Player Subscription Channel */
    public $xmrChannel;

    /** @var string The Player Public Key */
    public $xmrPubKey;

    /** @var int A flag indicating whether to Clear the cached XMR configuration and send a rekey acction */
    public $rekeyXmr;

    /** @var int A flag indicating whether to Clear all Cached data for this display */
    public $clearCachedData;

    /** @var int The last command success, 0 = failure, 1 = success, 2 = unknown */
    public $lastCommandSuccess;

    /** @var int The Version Media ID to override one set in the Display Profile */
    public $versionMediaId;

    /**
     * Get a list of displays.
     *
     * @param array $params can be filtered by, displayId, displayGropupId, display, macAddress, hardwareKey, clientVersion, clientCode, clientType, authorised, displayProfileId, loggedIn, status and embeddable parameter embed=displaygroups
     * @return array[XiboDisplay]
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting list of Displays');
        $entries = [];
        $response = $this->doGet('/display', $params);
        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }
        
        return $entries;
    }
    /**
     * Get Display by Id.
     *
     * @param int $id The Display ID
     * @return $this|XiboDisplay
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $this->getLogger()->info('Getting display ID ' . $id);
        $response = $this->doGet('/display', [
            'displayId' => $id
        ]);
        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single display, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }
    /**
     * Edit
     * @param string $display Display Name
     * @param string $description Display Description
     * @param string $tags A comma separated list of tags
     * @param string $auditingUntil A Date this Display records auditing information until
     * @param int $defaultLayoutId The ID of the default layout for this display
     * @param int $licensed Flag indicating whether this display is authorised with CMS
     * @param string $license HardwareKey for this Display
     * @param int $incSchedule Flag indicating whether the default layout should be included in the Schedule
     * @param int $emailAlert Flag indicating whether the Display generated up/down email alerts
     * @param int $alertTimeout How long in seconds should this display wait before alerting when it hasn't connected - Overrides for the collection interval
     * @param int $wakeOnLanEnabled Flag indicating whether Wake On Lan is enabled for this display
     * @param string $wakeOnLanTime A h:i string representing the time that the display should receive its Wake on Lan command
     * @param string $broadCastAddress The BroadCast Address for this display - used by WoL
     * @param string $secureOn The secure on configuration for this display
     * @param string $cidr The CIDR configuration for this display
     * @param number $latitude The latitude of this display
     * @param number $longitude The longitude for this display
     * @param string $timeZone The timezone for this display, leave empty to use CMS timezone
     * @param int $displayProfileId The Display Profile ID
     * @param int $clearCachedData A flag indicating whether to Clear all Cached data for this display
     * @param int $rekeyXmr A flag indicating whether to Clear the cached XMR configuration and send a rekey acction
     * @param int $versionMediaId The Version Media ID to override one set in the Display Profile
     * @return XiboDisplay
     */
    public function edit($display, $description, $tags, $auditingUntil = null, $defaultLayoutId, $licensed, $license, $incSchedule = 0, $emailAlert = 0, $alertTimeout = null, $wakeOnLanEnabled = 0, $wakeOnLanTime = null, $broadCastAddress = null, $secureOn = null, $cidr = null, $latitude = null, $longitude = null, $timeZone = null, $displayProfileId = null, $clearCachedData = 1, $rekeyXmr = 0, $versionMediaId = 0)
    {
        $this->display = $display;
        $this->description = $description;
        $this->tags = $tags;
        $this->auditingUntil = $auditingUntil;
        $this->defaultLayoutId = $defaultLayoutId;
        $this->licensed = $licensed;
        $this->license = $license;
        $this->incSchedule = $incSchedule;
        $this->emailAlert = $emailAlert;
        $this->alertTimeout = $alertTimeout;
        $this->wakeOnLanEnabled = $wakeOnLanEnabled;
        $this->wakeOnLanTime = $wakeOnLanTime;
        $this->broadCastAddress = $broadCastAddress;
        $this->secureOn = $secureOn;
        $this->cidr = $cidr;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->timeZone = $timeZone;
        $this->displayProfileId = $displayProfileId;
        $this->clearCachedData = $clearCachedData;
        $this->rekeyXmr = $rekeyXmr;
        $this->versionMediaId = $versionMediaId;

        $this->getLogger()->info('Editing display ' . $this->display);
        $response = $this->doPut('/display/' . $this->displayId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Delete the display.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting display ID ' . $this->displayId);
        $this->doDelete('/display/' . $this->displayId);
        
        return true;
    }

    /**
     * Request screenshot from this display.
     *
     */
    public function screenshot()
    {
        $this->getLogger()->info('Requesting a screenshot from display ID ' . $this->displayId);
        $this->doPut('/display/requestscreenshot/' . $this->displayId);
    }

    /**
     * Wake On Lan.
     *
     */
    public function wol()
    {
        $this->getLogger()->info('Sending WoL request to display ID ' . $this->displayId);
        $this->doPost('/display/wol/' . $this->displayId);
    }


    /**
     * Authorise the display.
     *
     */
    public function authorise()
    {
        $this->getLogger()->info('Setting Authorise for display ID ' . $this->displayId);
        $this->doPut('/display/authorise/' . $this->displayId);
    }

    /**
     * Set the default layout for the display.
     *
     * @param int $layoutId The ID of the default layout
     *
     */
    public function defaultLayout($layoutId)
    {
        $this->getLogger()->info('Setting Default layout ID ' . $layoutId . ' for display ID ' . $this->displayId);
        $this->doPut('/display/defaultlayout/' . $this->displayId, [
            'layoutId' => $layoutId
        ]);
    }
}
