<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2015 Spring Signage Ltd
 * (Xibo.php)
 */

namespace Xibo\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;

class Xibo extends AbstractProvider
{
    protected $baseUrl;

    public function setCmsUrl($url)
    {
        $this->baseUrl = rtrim($url, '/');
    }

    /**
     * Get the URL that this provider uses to begin authorization.
     *
     * @return string
     */
    public function urlAuthorize()
    {
        return $this->baseUrl . '/api/authorize';
    }

    /**
     * Get the URL that this provider uses to request an access token.
     *
     * @return string
     */
    public function urlAccessToken()
    {
        return $this->baseUrl . '/api/authorize/access_token';
    }

    /**
     * Get the URL that this provider uses to request user details.
     *
     * Since this URL is typically an authorized route, most providers will require you to pass the access_token as
     * a parameter to the request. For example, the google url is:
     *
     * 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token='.$token
     *
     * @param AccessToken $token
     * @return string
     */
    public function urlUserDetails(AccessToken $token)
    {
        return $this->baseUrl . '/api/user/me?access_token=' . $token;
    }

    /**
     * Given an object response from the server, process the user details into a format expected by the user
     * of the client.
     *
     * @param object $response
     * @param AccessToken $token
     * @return mixed
     */
    public function userDetails($response, AccessToken $token)
    {
        return $response;
    }
}