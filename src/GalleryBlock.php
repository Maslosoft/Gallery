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

use Maslosoft\Hi5Edit\Blocks\Base\ContentBlock;
use Maslosoft\Hi5Edit\Interfaces\EditorWidgetInterface;
use Maslosoft\Hi5Edit\Signals\EditorWidget;

/**
 * Photo gallery widget class
 * @Label('Advanced Gallery')
 * @Description('Gallery with support for groupping image display')
 * @license licence
 * @copyright licence
 * @author Piotr Maselkowski <piotr at maselkowski dot pl>
 */
class GalleryBlock extends ContentBlock implements EditorWidgetInterface
{

	public function edit()
	{
		
	}

	public function view()
	{
		
	}

	/**
	 * Signal reactor
	 * @param EditorWidget $signal
	 */
	public function reactOn(EditorWidget $signal)
	{
		$signal->widget = $this;
	}

}
