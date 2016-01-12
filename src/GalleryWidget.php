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

use CDataProvider;
use Maslosoft\Ilmatar\Components\JavaScript;
use Maslosoft\Gallery\Adapter\MongoGroup;
use Maslosoft\Ilmatar\Widgets\JsWidget;
use Maslosoft\Ilmatar\Widgets\MsWidget;

/**
 * Description of Gallery
 * @author Piotr
 */
class GalleryWidget extends MsWidget
{

// <editor-fold defaultstate="collapsed" desc="Display properties">
	/**
	 * Width of images group
	 * @var int
	 */
	public $width = 300;

	/**
	 * Height of images group
	 * @var int
	 */
	public $height = 200;

	/**
	 * Maximum full image width
	 * @var int
	 */
	public $imgWidth = 1920;

	/**
	 * Maximum full image height
	 * @var int
	 */
	public $imgHeight = 1080;

	/**
	 * Margin between images, used to calculate single image dimensions
	 * @var int
	 */
	public $margin = 4;

	/**
	 * Route used to create image url
	 * @var string
	 */
	public $route = 'content/asset/get/';

	/**
	 * Params for `createUrl` combined with `$route`
	 * Use similarly as params for `createUrl`.
	 * If value of array contains curly braces it will be replaced with
	 * corresponding item value.
	 * Example:
	 * <code>
	 * $params = [
	 * 		'w' => '{width}'
	 * ];
	 * </code>
	 * Will become:
	 * <code>
	 * $params = [
	 * 		'w' => 100
	 * ];
	 * </code>
	 * @var
	 */
	public $params = [
		'id' => '{id}',
		'w' => '{width}',
		'h' => '{height}',
		'p' => '0'
	];
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Data related fields">
	/**
	 * Whenever to use groupping gallery or single images gallery
	 * @var bool
	 */
	public $useGroups = true;

	/**
	 * Dataprovider
	 * @var CDataProvider
	 */
	public $dataProvider = null;

	/**
	 *
	 * @var GalleryVm
	 */
	public $vm;
	public $adapter = null;
	public $groups = [];
	public $result = [];
	public $jsRef;

// </editor-fold>

	public function init()
	{
//		$this->clientScript->registerPackage('ko');
//		$this->clientScript->registerPackage('history');
//		$this->clientScript->registerPackage('purl');
//		$this->clientScript->registerPackage('screenfull');
//		$this->clientScript->registerPackage('underscore');

		$this->vm = new GalleryVm($this->id);
		$this->vm->dp = $this->dataProvider;

//		var_dump(uniqid());
//		var_dump();

		$options = [];
		$optionKeys = [
			'imgWidth',
			'imgHeight'
		];
		foreach ($optionKeys as $key)
		{
			$options[$key] = $this->$key;
		}

		$options['fadeControls'] = true;

		$params = [
			JavaScript::encode($this->id),
			$this->vm,
			JavaScript::encode($options)
		];

		$this->jsRef = new JsWidget($this, $params, __DIR__ . '/../dist');

		$path = $this->assetManager->publish(__DIR__ . '/../dist/css/');
		$this->clientScript->registerCssFile($path . '/gallery.min.css');

		if (null === $this->adapter)
		{
			$this->adapter = MongoGroup::$CLS;
		}
		if ($this->useGroups)
		{
			$this->_initGroups();
		}
		else
		{
			$this->_initSimple();
		}
	}

	public function run()
	{
		return $this->render('gallery', [], true);
	}

	private function _initGroups()
	{
		foreach ($this->groups as $items)
		{
			$this->result[] = $this->_initGroup($items);
		}
	}

	private function _initSimple()
	{

	}

	/**
	 *
	 * @param type $items
	 * @return array
	 */
	private function _initGroup($items)
	{
		$result = [];
		$amount = count($items);

		$splitFactor = floor(sqrt($amount));
		$split = ceil($amount / $splitFactor);
		$counter = 0;
//		var_dump($split);
		foreach ($items as $id)
		{
			if (($counter % $split) == 0)
			{
				$group = new Group;
				$result[] = $group;
			}
			$item = new Item($this, $group);
			$item->id = $id;
			$group->items[] = $item;
			$counter++;
		}
		$heightAmount = count($result);
		$firstRow = true;
		$lastRow = false;

		// Fix height if margins does not fit height
		$heightFix = (ceil($this->height / $heightAmount) * $heightAmount) - $this->height;

		foreach ($result as $group)
		{
			$counter++;
			if ($counter == $heightAmount)
			{
				$lastRow = true;
			}
			$first = true;
			$widthFix = (ceil($this->width / $group->amount) * $group->amount) - $this->width;
			foreach ($group->items as $item)
			{
				// Use ceil here, width and height will be refined with $widthFix and $heightFix
				$item->width = ceil($this->width / $group->amount);
				$item->height = ceil($this->height / $heightAmount);

				// Apply width margins
				if (!$first)
				{
					$item->width = $item->width - $this->margin;
				}

				// Apply height margins
				if (!$firstRow)
				{
					$item->height = $item->height - $this->margin;
				}

				// Apply uneven items width/margin fix
				if ($heightFix)
				{
					$item->height = $item->height - 1;
				}
				if ($widthFix)
				{
					$item->width = $item->width - 1;
					$widthFix--;
				}
				$first = false;
			}
			if ($heightFix)
			{
				$heightFix--;
			}
			$firstRow = false;
		}
		return $result;
	}

}
