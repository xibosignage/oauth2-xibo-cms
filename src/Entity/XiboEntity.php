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


use Xibo\OAuth2\Client\Provider\XiboEntityProvider;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
     * Hydrate an entity with properties
     *
     * @param array $properties
     * @param array $options
     *
     * @return self
     */
    public function hydrate(array $properties, $options = [])
    {
        $this->getLogger()->debug('Hydrating the response');
        $intProperties = (array_key_exists('intProperties', $options)) ? $options['intProperties'] : [];
        $stringProperties = (array_key_exists('stringProperties', $options)) ? $options['stringProperties'] : [];
        $htmlStringProperties = (array_key_exists('htmlStringProperties', $options)) ? $options['htmlStringProperties'] : [];

        foreach ($properties as $prop => $val) {
            if (property_exists($this, $prop)) {

                if (stripos(strrev($prop), 'dI') === 0 || in_array($prop, $intProperties))
                    $val = intval($val);
                else if (in_array($prop, $stringProperties))
                    $val = filter_var($val, FILTER_SANITIZE_STRING);
                else if (in_array($prop, $htmlStringProperties))
                    $val = htmlentities($val);

                $this->{$prop} =  $val;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ObjectVars::getObjectVars($this);
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

    /**
     * Get Logger
     */
    public function getLogger()
    {
        return $this->getEntityProvider()->getlogger();
    }
    
}
