<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboWidget.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

/**
 * Class XiboWidget
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboWidget extends XiboEntity
{
    public $widgetId;
    public $playlistId;
    public $ownerId;
    public $type;
    public $duration;
    public $displayOrder;
    public $useDuration;
    public $calculatedDuration;
    public $widgetOptions;
    public $mediaIds;
    public $audio;
    public $permissions;
    public $module;

    /**
     * Get by Id
     * @param $widgetId
     * @return $this|XiboWidget
     * @throws XiboApiException
     */
    public function getById($widgetId)
    {
        $this->getLogger()->info('Getting widget ID ' . $widgetId);
        $response = $this->doGet('/playlist/widget', [
            'widgetId' => $widgetId
        ]);

        $resonse = clone $this->hydrate($response[0]);
        if ($response[0]['type'] != $this->type)
            throw new XiboApiException('Invalid widget type');

        return $this;
    }
}