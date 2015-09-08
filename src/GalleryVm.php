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

use Maslosoft\Ilmatar\Widgets\Ko\Vm;

/**
 * Description of GalleryVm
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class GalleryVm extends Vm
{
	public $urlId = '';
	public $selectedImage = null;
	public $selectedImageIndex = 0;
	public $nextImage = null;
	public $previmage = null;


	public $selectedGroup = null;
	public $selectedGroupIndex = 0;
	public $nextGroup = null;
	public $firstGroup = null;
	public $prevGroup = null;
	public $lastGroup = null;

	public $slide = false;
	public $fullscreen = false;

	public $dp = null;
}
