<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboApiException.php)
 */


namespace Xibo\OAuth2\Client\Exception;


class XiboApiException extends \Exception
{
    public $httpStatusCode = 400;
    public $handledException = false;

    /**
     * @return bool
     */
    public function handledException()
    {
        return $this->handledException;
    }
}