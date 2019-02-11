<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboEntity.php)
 */


namespace Xibo\OAuth2\Client\Provider;
use GuzzleHttp\Psr7\MultipartStream;
use League\OAuth2\Client\Token\AccessToken;
use Xibo\OAuth2\Client\Exception\EmptyProviderException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class XiboEntityProvider
{
    /** @var  Xibo */
    private $provider;
    /** @var  XiboUser */
    private $me;
    /** @var  AccessToken */
    private $token;
    /** @var LoggerInterface */
    private $logger;
    
    /**
     * Xibo Entity Provider Constructor 
     * @param Xibo $provider
     * @param array $logger
     */
    public function __construct($provider, array $logger = [])
    {
        if (isset($logger['logger']))
            $this->logger = $logger['logger'];
        else
            $this->logger = new NullLogger();

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
     * Get Me
     * @return XiboUser
     */
    public function getMe()
    {
        $this->getLogger()->debug('Getting Resource Owner');
        if ($this->me == null) {
            $this->me = $this->provider->getResourceOwner($this->getAccessToken());
        }
        return $this->me;
    }
    /**
     * Get Logger
     */
    public function getLogger()
    {
        return $this->logger;
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
        $this->getLogger()->debug('Checking Access token and requesting a new one if necessary');
        if ($this->token == null || $this->token->hasExpired() || $this->token->getExpires() <= time() + 10) {
            // Get and store a new token
        $this->getLogger()->info('Getting a new Access Token');
            $this->token = $this->provider->getAccessToken('client_credentials');
        }
        else {
            $this->token = $this->token;
        }
        return $this->token;
    }
    
    /**
     * @param $url
     * @param $params
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($url, $params = [])
    {
        $this->logger->debug('Passing GET params to request');
        return $this->request('GET', $url . '?' . http_build_query($params));
    }
    
    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    public function post($url, $params = [])
    {
        $this->logger->debug('Passing POST params to request');
        return $this->request('POST', $url, $params);
    }
    
    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    public function put($url, $params = [])
    {
        $this->logger->debug('Passing PUT params to request');
        return $this->request('PUT', $url, $params);
    }
    
    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    public function delete($url, $params = [])
    {
        $this->logger->debug('Passing Delete params to request');
        return $this->request('DELETE', $url, $params);
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
        $this->getLogger()->debug('Creating a new request with received parameters');
        $options = [
            'headers' => null, 'body' => null
        ];
        // Multipart
        if (array_key_exists('multipart', $params)) {
            // Build the multipart message
            $options['body'] = new MultipartStream($params['multipart']);
        } else if (array_key_exists('json', $params)) {
            // Build the JSON body and content type
            $options['body'] = json_encode($params['json']);
            $options['headers'] = ['content-type' => 'application/json'];
        } else if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
            $options['headers'] = ['content-type' => 'application/x-www-form-urlencoded'];
            if (count($params) > 0) {
                $options['body'] = http_build_query($params, null, '&');
            }
        }
        $this->logger->debug('Getting Authenticated Request');
        $request = $this->provider->getAuthenticatedRequest($method, $this->provider->getCmsApiUrl() . rtrim($url, '/'), $this->getAccessToken(), $options);
        $this->logger->debug('Getting parsed response from Abstract Provider');
        return $this->provider->getParsedResponse($request);
    }
}
