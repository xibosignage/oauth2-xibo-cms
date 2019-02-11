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


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboCampaign extends XiboEntity
{
    private $url = '/campaign';

    /** @var int The campaign ID */
    public $campaignId;

    /** @var int user ID of the owner */
    public $ownerId;

    /** @var string The of the campaign */
    public $campaign;

    /** @var int flag is this layout specific campaign? */
    public $isLayoutSpecific = 0;

    /** @var int Number of layout assigned to this campaign */
    public $numberLayouts;

    /** @var int The total duration of all layouts in the campaign */
    public $totalDuration;

    /**
     * Returns a Grid of Campaigns.
     *
     * Search all Campaigns this user has access to
     *
     * @param array $params filter by campaignId, name, tags, hasLayouts, isLayoutSpecific, retired, totalDuration
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
     * Returns campaign with the specified ID.
     *
     * @param int $id campaignId to find
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
     * Add a new campaign with specified name.
     *
     * @param string $campaign name of the campaign
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
     * Edit campaign and change its name to the specified one.
     *
     * @param string $campaign name of the campaign
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
     * Deletes the campaign.
     *
     * @return bool
     */
    public function delete()
    {
        $this->getLogger()->info('Deleting Campaign ID ' . $this->campaignId);
        $this->doDelete($this->url . '/' . $this->campaignId);

        return true;
    }

    /**
     * Assign layout to the campaign.
     *
     * @param array $layoutId Array of layouts Ids to assign to this campaign
     * @param array $displayOrder Array of Display Order numbers for the layouts to assign
     * @param array $unassignLayoutId Array of layouts Ids to unassign from the campaign
     * @return XiboCampaign
     */
    public function assignLayout($layoutId = [], $displayOrder = [], $unassignLayoutId = [])
    {
        // TODO Check if $layoutId and $displayOrderId have the same length, throw error otherwise

        if ($layoutId != [] && $unassignLayoutId == []) {
            for ($i = 0; $i < count($layoutId); $i++) {
                $response = $this->doPost('/campaign/layout/assign/' . $this->campaignId, [
                    'layoutId' => [
                        [
                            'layoutId' => $layoutId[$i],
                            'displayOrder' => $displayOrder[$i]
                        ]
                    ]
                ]);
                $this->getLogger()->info('Assigning Layout ID ' . $layoutId[$i] . ' To Campaign ID ' . $this->campaignId);
            }
        } elseif ($layoutId != [] && $unassignLayoutId != []) {
            for ($i = 0; $i < count($layoutId); $i++) {
                for ($j = 0; $j < count($unassignLayoutId); $j++) {
                    $response = $this->doPost('/campaign/layout/assign/' . $this->campaignId, [
                        'layoutId' => [
                            [
                                'layoutId' => $layoutId[$i],
                                'displayOrder' => $displayOrder[$i]
                            ]
                        ],
                        'unassignLayoutId' => [
                            [
                                'layoutId' => $unassignLayoutId[$j],
                            ]
                        ]
                    ]);
                    $this->getLogger()->info('Assigning Layout ID ' . $layoutId[$i] . ' To Campaign ID ' . $this->campaignId);
                    $this->getLogger()->info('Removing assignment Layout ID ' . $unassignLayoutId[$j] . ' From Campaign ID ' . $this->campaignId);
                }
            }
        } elseif ($layoutId == [] && $unassignLayoutId != []) {
            for ($j = 0; $j < count($unassignLayoutId); $j++) {
                $response = $this->doPost('/campaign/layout/assign/' . $this->campaignId, [
                    'unassignLayoutId' => [
                        [
                            'layoutId' => $unassignLayoutId[$j],
                        ]
                    ]
                ]);
                $this->getLogger()->info('Removing assignment Layout ID ' . $unassignLayoutId[$j] . ' From Campaign ID ' . $this->campaignId);
            }
        }

        // TODO Throw an error if nothing is passed / found

        return $this;
    }

    /**
     * Unassign layout from the campaign.
     *
     * @param array $unassignLayoutId Array of layouts Ids to unassign from the campaign
     * @return XiboCampaign
     */
    public function unassignLayout($unassignLayoutId = [])
    {
        for ($j = 0; $j < count($unassignLayoutId); $j++) {
            $this->doPost('/campaign/layout/assign/' . $this->campaignId, [
                'unassignLayoutId' => [
                    [
                        'layoutId' => $unassignLayoutId[$j],
                    ]
                ]
            ]);
            $this->getLogger()->info('Removing assignment Layout ID ' . $unassignLayoutId[$j] . ' From Campaign ID ' . $this->campaignId);
        }

        return $this;

    }
}

