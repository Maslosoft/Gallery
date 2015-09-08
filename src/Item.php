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

namespace Maslosoft\Gallery;

use CComponent;
use CMap;
use Yii;

/**
 * Single gallery items metadata
 *
 * @author Piotr
 * @property string $src Image src value
 */
class Item extends CComponent
{

	/**
	 * Widget instance
	 * @var GalleryWidget
	 */
	private $_widget = null;

	/**
	 * Group instance
	 * @var Group
	 */
	private $_group = null;

	public function __construct(GalleryWidget $widget, Group $group)
	{
		$this->_widget = $widget;
		$this->_group = $group;
	}

	/**
	 * Item id
	 * @var string
	 */
	public $id = '';

	/**
	 * User displayed title
	 * @var string
	 */
	public $title = '';

	/**
	 * User displayed description
	 * @var string
	 */
	public $description = '';

	/**
	 * Calculated width of single image
	 * @var int
	 */
	public $width = 0;

	/**
	 * Calculated height of single image
	 * @var int
	 */
	public $height = 0;

	public function getSrc($params = [])
	{
		$params = CMap::mergeArray($this->_widget->params, $params);
		$parsedParams = [];
		foreach ($params as $key => $value)
		{
			if (preg_match('~\{\w+\}~', $value))
			{
				$attr = preg_replace('~[\{\}]~', '', $value);
				$parsedParams[$key] = $this->$attr;
			}
			else
			{
				$parsedParams[$key] = $value;
			}
		}
		$src = Yii::app()->createUrl($this->_widget->route, $parsedParams);
		return $src;
	}

	public function setSrc($src)
	{
		return $this;
	}

	public function getUrl()
	{
		return sprintf('?image=%s', $this->id);
	}

	public function setUrl($url)
	{
		return $this;
	}

}
