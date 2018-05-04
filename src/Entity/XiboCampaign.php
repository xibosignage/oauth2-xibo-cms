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
    public $totalDuration;

    /**
     * @param array $params
     * @return array|XiboCampaign
     */
    public function get(array $params = [])
    {
        $this->getLogger()->info('Getting list of Campaigns');
        $entries = [];
        $response = $this->doGet($this->url, $params);

        foreach ($response as $item) {
            $entries[] = clone $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * @param $id
     * @return XiboCampaign
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $this->getLogger()->info('Getting Campaign ID ' . $id);
        $response = $this->doGet($this->url, [
            'campaignId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return clone $this->hydrate($response[0]);
    }

    /**
     * @param $campaign
     * @return XiboCampaign
     */
    public function create($campaign)
    {
        $this->getLogger()->debug('Getting Resource Owner');
        $this->ownerId = $this->getEntityProvider()->getMe()->getId();
        $this->campaign = $campaign;

        // Rewrite parameter mismatch
        $array = $this->toArray();
        $array['name'] = $array['campaign'];
        $this->getLogger()->info('Creating Campaign ' . $this->campaign);
        $response = $this->doPost($this->url, $array);

        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
     * @param $campaign
     * @return XiboCampaign
     */
    public function edit($campaign)
    {
        $this->getLogger()->debug('Getting Resource Owner');
        $this->ownerId = $this->getEntityProvider()->getMe()->getId();
        $this->campaign = $campaign;

        // Rewrite parameter mismatch
        $array = $this->toArray();
        $array['name'] = $array['campaign'];
        $this->getLogger()->info('Editing Campaign ID ' . $this->campaignId);
        $response = $this->doPut($this->url . '/' . $this->campaignId, $array);

        $this->getLogger()->debug('Passing the response to Hydrate');
        return $this->hydrate($response);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting Campaign ID ' . $this->campaignId);
        $this->doDelete($this->url . '/' . $this->campaignId);

        return true;
    }

    /**
     * Assign layout
     * @param $layoutId
     * @param int $campaignId
     * @return XiboCampaign
     */
    public function assignLayout($layoutId)
    {
        $this->getLogger()->info('Assigning Layout ID ' . $layoutId . ' To Campaign ID ' . $this->campaignId);
        $response = $this->doPost('/campaign/layout/assign/' . $this->campaignId, [
            'layoutId' => [
                [
                    'layoutId' => $layoutId,
                    'displayOrder' => 1
                ]
            ]
        ]);

        return $this;
    }
}
