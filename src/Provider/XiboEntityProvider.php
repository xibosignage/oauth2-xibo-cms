<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboEntity.php)
 */


namespace Xibo\OAuth2\Client\Provider;


use League\OAuth2\Client\Token\AccessToken;
use Xibo\OAuth2\Client\Exception\EmptyProviderException;

class XiboEntityProvider
{
    /** @var  Xibo */
    private $provider;

    /** @var  AccessToken */
    private $token;

    /**
     * Set Provider
     * @param Xibo $provider
     */
    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get Provider
     * @return Xibo
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Get Access Token
     * @return AccessToken
     * @throws \Exception
     */
    private function getAccessToken()
    {
        if ($this->provider === null)
            throw new EmptyProviderException();

        if ($this->token == null || $this->token->hasExpired()) {
            // Get and store a new token
            $this->token = $this->provider->getAccessToken('client_credentials');
        }

        return $this->token;
    }

    /**
     * @param $url
     * @param $params
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($url, $params = [], $options = [])
    {
        return $this->request('GET', $url . '?' . http_build_query($params), $options);
    }

    /**
     * @param $url
     * @param array $params
     * @param array $options
     * @return mixed
     */
    public function post($url, $params = [], $options = [])
    {
        return $this->request('POST', $url . '?' . http_build_query($params), $options);
    }

    /**
     * @param $url
     * @param array $params
     * @param array $options
     * @return mixed
     */
    public function put($url, $params = [], $options = [])
    {
        return $this->request('PUT', $url, http_build_query($params), $options);
    }

    /**
     * @param $url
     * @param array $params
     * @param array $options
     * @return mixed
     */
    public function delete($url, $params = [], $options = [])
    {
        return $this->request('DELETE', $url, http_build_query($params), $options);
    }

    /**
     * Request
     * @param $method
     * @param $url
     * @param array $params
     * @return mixed
     * @throws EmptyProviderException
     */
    private function request($method, $url, $params = [])
    {
        $request = $this->provider->getAuthenticatedRequest($method, $this->provider->getCmsApiUrl() . rtrim($url, '/'), $this->getAccessToken(), $params);

        return $this->provider->getResponse($request);
    }
}