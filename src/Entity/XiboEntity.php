<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboEntity.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Provider\XiboEntityProvider;

class XiboEntity
{
    /** @var  XiboEntityProvider */
    private $entityProvider;

    /**
     * @param XiboEntityProvider $provider
     */
    public function __construct($provider)
    {
        $this->entityProvider = $provider;
    }

    /**
     * @return XiboEntityProvider
     */
    protected function getEntityProvider()
    {
        return $this->entityProvider;
    }

    /**
     * @param $url
     * @param $params
     * @return mixed
     */
    protected function doGet($url, $params = [])
    {
        return $this->getEntityProvider()->get($url, $params);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    protected function doPost($url, $params = [])
    {
        return $this->getEntityProvider()->post($url, $params);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    protected function doPut($url, $params = [])
    {
        return $this->getEntityProvider()->put($url, $params);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    protected function doDelete($url, $params = [])
    {
        return $this->getEntityProvider()->delete($url, $params);
    }
}