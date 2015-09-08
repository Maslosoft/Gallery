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

namespace Maslosoft\Gallery\Adapter;

use Maslosoft\Mangan\DataProvider;
use InvalidArgumentException;

/**
 * Mongo groupping gallery adapter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoGroup implements GalleryInterface
{

	public static $CLS = __CLASS__;

	private $_groups = null;

	public function getGroups()
	{
		
	}

	public function setData($data)
	{
		if (!$data instanceof \Maslosoft\Mangan\DataProvider)
		{
			throw new InvalidArgumentException(spritnf('Parameter `$data` must be of type %s, but %s given', \Maslosoft\Mangan\DataProvider::$CLS, @get_class($data)));
		}

		foreach ($data->data as $group)
		{
			$this->_groups[] = $group;
		}
	}

}
