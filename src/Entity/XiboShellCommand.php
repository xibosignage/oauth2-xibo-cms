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

class XiboShellCommand extends XiboWidget
{
    /** @var int The Widget ID */
    public $widgetId;

    /** @var int The Playlist ID */
    public $playlistId;

    /** @var int The Owner ID */
    public $ownerId;

    /** @var string The Widget Type */
    public $type;

    /** @var int The Widget Duration */
    public $duration;

    /** @var int The Display Order of the Widget */
    public $displayOrder;

    /** @var int Flag indicating whether to use custom duration */
    public $useDuration;

    /** @var string Optional Widget name */
    public $name;

    /** @var string Enter a Windows command line compatible command */
    public $windowsCommand;

    /** @var string Enter a Android / Linux command line compatible command */
    public $linuxCommand;

    /** @var int Flag  Windows only, Should the player launch this command through the windows command line (cmd.exe)? This is useful for batch files, if you try to terminate this command only the command line will be terminated */
    public $launchThroughCmd;

    /** @var int Flag Should the player forcefully terminate the command after the duration specified, 0 to let the command terminate naturally */
    public $terminateCommand;

    /** @var int Flag Windows only, should the player use taskkill to terminate commands */
    public $useTaskill;

    /** @var string Enter a reference code for exiting command in CMS */
    public $commandCode;

    /**
     * Create new Shell Command Widget.
     *
     * @param string $name Optional widget name
     * @param int $duration Widget duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param string $windowsCommand Enter a Windows command line compatible command
     * @param string $linuxCommand Enter a Android / Linux command line compatible command
     * @param int $launchThroughCmd Flag  Windows only, Should the player launch this command through the windows command line (cmd.exe)? This is useful for batch files, if you try to terminate this command only the command line will be terminated
     * @param int $terminateCommand Flag Should the player forcefully terminate the command after the duration specified, 0 to let the command terminate naturally
     * @param int $useTaskkill Flag Windows only, should the player use taskkill to terminate commands
     * @param string $commandCode Enter a reference code for exiting command in CMS
     * @param int $playlistId Playlist ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboShellCommand
     */
    public function create($name, $duration, $useDuration, $windowsCommand, $linuxCommand, $launchThroughCmd, $terminateCommand, $useTaskkill, $commandCode, $playlistId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->windowsCommand = $windowsCommand;
        $this->linuxCommand = $linuxCommand;
        $this->launchThroughCmd = $launchThroughCmd;
        $this->terminateCommand = $terminateCommand;
        $this->useTaskkill = $useTaskkill;
        $this->commandCode = $commandCode;
        $this->playlistId = $playlistId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Creating new Schell Command widget in playlist ID ' . $playlistId);
        $response = $this->doPost('/playlist/widget/shellCommand/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit existing Shell Command Widget.
     *
     * @param string $name Optional widget name
     * @param int $duration Widget duration
     * @param int $useDuration Flag indicating whether to use custom duration
     * @param string $windowsCommand Enter a Windows command line compatible command
     * @param string $linuxCommand Enter a Android / Linux command line compatible command
     * @param int $launchThroughCmd Flag  Windows only, Should the player launch this command through the windows command line (cmd.exe)? This is useful for batch files, if you try to terminate this command only the command line will be terminated
     * @param int $terminateCommand Flag Should the player forcefully terminate the command after the duration specified, 0 to let the command terminate naturally
     * @param int $useTaskkill Flag Windows only, should the player use taskkill to terminate commands
     * @param string $commandCode Enter a reference code for exiting command in CMS
     * @param int $widgetId the Widget ID
     * @param string $enableStat The settings to enable the collection of Proof of Play statistics, available options: ON, Off, Inherit
     * @return XiboShellCommand
     */
    public function edit($name, $duration, $useDuration, $windowsCommand, $linuxCommand, $launchThroughCmd, $terminateCommand, $useTaskkill, $commandCode, $widgetId, $enableStat = '')
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->windowsCommand = $windowsCommand;
        $this->linuxCommand = $linuxCommand;
        $this->launchThroughCmd = $launchThroughCmd;
        $this->terminateCommand = $terminateCommand;
        $this->useTaskkill = $useTaskkill;
        $this->commandCode = $commandCode;
        $this->widgetId = $widgetId;
        $this->enableStat = $enableStat;
        $this->getLogger()->info('Editing a widget ID ' . $widgetId);
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Delete the widget.
     *
     * @return bool
     */
    public function delete()
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->getLogger()->info('Deleting a widget ID ' . $this->widgetId);
        $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
