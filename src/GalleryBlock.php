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

use Maslosoft\Hi5Edit\Blocks\Base\ContentBlock;
use Maslosoft\Hi5Edit\Interfaces\IEditorWidget;

/**
 * Photo gallery widget class
 * 
 * @license licence
 * @copyright licence
 * @author Piotr Maselkowski <piotr at maselkowski dot pl>
 */
class GalleryBlock extends ContentBlock implements IEditorWidget
{

	public function getName()
	{
		return tx('Image gallery');
	}

	public function edit()
	{

	}

	public function editTemplate()
	{

	}

	public function getDescription()
	{

	}

	public function render($view, $data = null, $return = false)
	{

	}

	public function run()
	{

	}

	public function editOptions()
	{

	}

	public function editToolbar()
	{
		
	}

	/**
	 * @SlotFor('Maslosoft\Hi5Edit\Signals\EditorWidget')
	 * @param \Maslosoft\Hi5Edit\Signals\EditorWidget $signal
	 */
	public function reactOn(\Maslosoft\Hi5Edit\Signals\EditorWidget $signal)
	{
		$signal->widget = $this;
	}

}