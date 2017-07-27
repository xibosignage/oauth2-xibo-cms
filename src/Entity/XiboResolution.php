<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboResolution.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboResolution extends XiboEntity
{
    public $url = '/resolution';

    public $resolutionId;
    public $resolution;
    public $width;
    public $height;
    public $designerWidth;
    public $designerHeight;
    public $version = 2;
    public $enabled = 1;

    /**
     * @param array $params
     * @return array|XiboResolution
     */
    public function get(array $params = [])
    {
        $entries = [];
        $response = $this->doGet($this->url, $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * @param $id
     * @return XiboResolution
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet($this->url, [
            'resolutionId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * Create
     * @param $resolution
     * @param $width
     * @param $height
     */
    public function create($resolution, $width, $height)
    {
    $this->userId = $this->getEntityProvider()->getMe()->getId();
    $this->resolution = $resolution;
    $this->width = $width;
    $this->height = $height;
    $response = $this->doPost('/resolution', $this->toArray());
   
    return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $resolution
     * @param $width
     * @param $height
     * @return XiboResolution
     */
    public function edit($resolution, $width, $height)
    {
    $this->resolution = $resolution;
    $this->width = $width;
    $this->height = $height;
    $response = $this->doPut('/resolution/' . $this->resolutionId, $this->toArray());
    return $this->hydrate($response);
    }

    /**
     * Delete
     * @return bool
     */
    public function delete()
    {
    $this->doDelete('/resolution/' . $this->resolutionId);
    return true;
    }
}
