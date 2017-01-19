<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboDataSetView.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboDataSetView extends XiboEntity
{
	/**
     * Create
     * @param $name
     * @param $dataSetId
     */
    public function create($name, $dataSetId, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->dataSetId = $dataSetId;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/dataSetView/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }
}

