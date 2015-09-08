<?php

/**
 * This SOFTWARE PRODUCT is protected by copyright laws and international copyright treaties,
 * as well as other intellectual property laws and treaties.
 * This SOFTWARE PRODUCT is licensed, not sold.
 * For full licence agreement see enclosed LICENCE.html file.
 *
 * @licence LICENCE.html
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Ilmatar\Widgets\Gallery;

use CComponent;

/**
 * Group of gallery items
 *
 * @author Piotr
 * @property int $amount Amount of items in group
 * @property string $url Url to view group
 */
class Group extends CComponent
{
	public $id = '';

	public $title = '';

	/**
	 * Items holder
	 * @var Item[]
	 */
	public $items = [];

	private static $idCounter = 0;

	public function __construct($id = null)
	{
		$this->id = $id;
		if(null === $this->id)
		{
			$this->id = self::$idCounter++;
		}
	}

	public function getAmount()
	{
		return count($this->items);
	}

	public function setAmount($amount)
	{
		return $this;
	}

	public function getUrl()
	{
		return sprintf('?group=%s', $this->id);
	}

	public function setUrl($url)
	{
		return $this;
	}

}
