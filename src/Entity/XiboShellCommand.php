<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboShellCommand.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboShellCommand extends XiboEntity
{
    /**
     * Create
     * @param $name
     * @param $duration
     * @param $windowsCommand
     * @param $linuxCommand
     * @param $launchThroughCmd
     * @param $terminateCommand
     * @param $useTaskKill
     * @param $commandCode
     */
    public function create($name, $duration, $windowsCommand, $linuxCommand, $launchThroughCmd, $terminateCommand, $useTaskkill, $commandCode, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->windowsCommand = $windowsCommand;
        $this->linuxCommand = $linuxCommand;
        $this->launchThroughCmd = $launchThroughCmd;
        $this->terminateCommand = $terminateCommand;
        $this->useTaskkill = $useTaskkill;
        $this->commandCode = $commandCode;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/shellCommand/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }
}
