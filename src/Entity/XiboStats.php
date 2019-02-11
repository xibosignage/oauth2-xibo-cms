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

class XiboStats extends XiboEntity
{
	private $url = '/stats';

    /** @var string The type of stat to return. Layout|Media|Widget or All */
	public $type;

    /** @var string Display name */
    public $display;

    /** @var int The Display ID */
	public $displayId;

    /** @var string Layout name */
    public $layout;

    /** @var int The Layout ID */
	public $layoutId;

    /** @var string Media name */
    public $media;

    /** @var int The Media ID */
	public $mediaId;

    /** @var int The number of times media/layout has played */
	public $numberPlays;

    /** @var int the total duration in seconds the media/layout were displayed */
	public $duration;

    /** @var string Date the first time the media/layout were displayed */
	public $minStart;

    /** @var string Date the last time the media/layout were displayed */
	public $maxEnd;

	/**
	 * @param array $params can be filtered by: type, fromDt, toDt, displayId, layoutId, mediaId
	 * @return array|XiboStats
	 */
	public function get(array $params = [])
	{
		$this->getLogger()->info('Getting list of statistics ');
		$entries = [];
		$response = $this->doGet($this->url, $params);
		foreach ($response as $item) {
			$entries[] = clone $this->hydrate($item);
		}
	
		return $entries;
	}
}
