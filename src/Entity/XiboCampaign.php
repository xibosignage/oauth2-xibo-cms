<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboCampaign.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboCampaign extends XiboEntity
{
    private $url = '/campaign';

    public $campaignId;
    public $ownerId;
    public $campaign;
    public $isLayoutSpecific = 0;
    public $numberLayouts;

    public function get()
    {
        $entries = [];
        $response = $this->doGet($this->url);

        foreach ($response as $item) {
            $entries[] = $this->hydrate($item);
        }

        return $entries;
    }

    public function getById($id)
    {
        $response = $this->doGet($this->url, [
            'campaignId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return $this->hydrate($response[0]);
    }

    public function create($campaign)
    {
        $this->ownerId = $this->getEntityProvider()->getMe()->getId();
        $this->campaign = $campaign;

        $response = $this->doPost($this->url, $this->toArray());

        return $this->hydrate($response);
    }

    public function edit($campaignId, $campaign)
    {
        $this->ownerId = $this->getEntityProvider()->getMe()->getId();
        $this->campaign = $campaign;

        $response = $this->doPut($this->url . '/' . $campaignId, $this->toArray());

        return $this->hydrate($response);
    }

    public function delete($campaignId)
    {
        $this->doDelete($this->url . '/' . $campaignId);

        return true;
    }
}