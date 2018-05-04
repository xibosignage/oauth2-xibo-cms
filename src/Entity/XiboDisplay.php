<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDisplay.php)
 */
namespace Xibo\OAuth2\Client\Entity;
use Xibo\OAuth2\Client\Exception\XiboApiException;
/**
 * Class XiboDisplay
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboDisplay extends XiboEntity
{
    public $displayId;
    public $display;
    public $description;
    public $auditingUntil;
    public $defaultLayoutId = 0;
    public $license;
    public $licensed;
    public $loggedIn;
    public $lastAccessed;
    public $incSchedule;
    public $emailAlert;
    public $alertTimeout;
    public $clientAddress;
    public $mediaInventoryStatus;
    public $macAddress;
    public $lastChanged;
    public $numberOfMacAddressChanges;
    public $lastWakeOnLanCommandSent;
    public $wakeOnLanEnabled;
    public $wakeOnLanTime;
    public $broadCastAddress;
    public $secureOn;
    public $cidr;
    public $latitude;
    public $longitude;
    public $versionInstructions;
    public $clientType;
    public $clientVersion;
    public $clientCode;
    public $displayProfileId;
    public $currentLayoutId;
    public $screenShotRequested;
    public $storageAvailableSpace;
    public $storageTotalSpace;
    public $displayGroupId;
    public $currentLayout;
    public $defaultLayout;
    public $xmrChannel;
    public $xmrPubKey;
    public $lastCommandSuccess;
    public $displayGroups = [];
    /**
     * @param array $params
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
     * Get by Id
     * @param $id
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
     * @param $display
     * @param $description
     * @param $tags
     * @param $auditingUntil
     * @param $defaultLayoutId
     * @param $licensed;
     * @param $license;
     * @param $incSchedule
     * @param $emailAlert
     * @param $alertTimeout
     * @param $wakeOnLanEnabled
     * @param $wakeOnLanTime
     * @param $broadCastAddress
     * @param $secureOn
     * @param $cidr
     * @param $latitude
     * @param $longitude
     * @param $timeZone
     * @param $displayProfileId
     * @param $clearCachedData
     * @param $rekeyXmr
     * @return XiboDisplay
     */
    public function edit($display, $description, $tags, $auditingUntil = null, $defaultLayoutId, $licensed, $license, $incSchedule = 0, $emailAlert = 0, $alertTimeout = null, $wakeOnLanEnabled = 0, $wakeOnLanTime = null, $broadCastAddress = null, $secureOn = null, $cidr = null, $latitude = null, $longitude = null, $timeZone = null, $displayProfileId = null, $clearCachedData = 1, $rekeyXmr = 0)
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
        $this->getLogger()->info('Editing display ' . $this->display);
        $response = $this->doPut('/display/' . $this->displayId, $this->toArray());
        
        return $this->hydrate($response);
    }

    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting display ID ' . $this->displayId);
        $this->doDelete('/display/' . $this->displayId);
        
        return true;
    }

    /**
     * Request screenshot
     */
    public function screenshot()
    {
        $this->getLogger()->info('Requesting a screenshot from display ID ' . $this->displayId);
        $this->doPut('/display/requestscreenshot/' . $this->displayId);
    }

    /**
     * Wake On Lan
     */
    public function wol()
    {
        $this->getLogger()->info('Sending WoL request to display ID ' . $this->displayId);
        $this->doPost('/display/wol/' . $this->displayId);
    }
}
