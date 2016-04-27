<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDisplayGroup.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

/**
 * Class XiboDisplayGroup
 * @package Xibo\OAuth2\Client\Entity
 */
class XiboDisplayGroup extends XiboEntity
{
    public $displayGroupId;

    private function fromResponse(array $attributes = [])
    {
        $this->displayGroupId = $attributes['displayGroupId'];

        return $this;
    }

    public function getById($id)
    {
        $response = $this->doGet('/displaygroup', [
            'displayGroupId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single display group, found ' . count($response));

        return $this->fromResponse($response[0]);
    }

    public function create($groupName, $groupDescription, $isDynamic, $dynamicCriteria)
    {

    }

    public function edit()
    {

    }

    public function delete()
    {

    }
}