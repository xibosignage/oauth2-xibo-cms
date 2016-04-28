<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboLayout.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboLayout extends XiboEntity
{
    private $url = '/layout';

    public $layoutId;
    public $ownerId;
    public $campaignId;
    public $backgroundImageId;
    public $schemaVersion;
    public $layout;
    public $description;
    public $backgroundColor;
    public $createdDt;
    public $modifiedDt;
    public $status;
    public $retired;
    public $backgroundzIndex;
    public $width;
    public $height;
    public $displayOrder;
    public $duration;

    /**
     * @param int $start
     * @param int $length
     * @return array|XiboLayout
     */
    public function get($start = 0, $length = 10)
    {
        $entries = [];
        $response = $this->doGet($this->url, [
            'start' => $start,
            'length' => $length
        ]);

        foreach ($response as $item) {
            $entries[] = $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * @param $id
     * @return XiboLayout
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet($this->url, [
            'layoutId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return $this->hydrate($response[0]);
    }
}