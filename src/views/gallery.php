<?php

use Maslosoft\Gallery\GalleryWidget;
use Maslosoft\Gallery\Group;
use Maslosoft\Gallery\Item;
use Maslosoft\Gallery\GalleryVm;
?>
<?php
$vm = $this->vm;
$urlId = $this->id;
/* @var $this GalleryWidget */
/* @var $this GalleryWidget */
/* @var $vm GalleryVm */
?>
<div class="col-md-2">
	Menu...
</div>
<div class="col-md-10">
<!--	<div data-bind="text: <?= $vm->fullscreen; ?>"></div>
	<div data-bind="foreach: <?= $vm->dp ?>.data">
		<div data-bind="text:id"></div>
	</div>-->
	<div class="maslosoft-gallery" id="<?= $this->id; ?>">
		<ul data-bind="foreach: <?= $vm->dp ?>.data">
			<li class="img-polaroid maslosoft-gallery-group col-md-3" style="<?= sprintf('height: %dpx;', $this->height); ?>">
				<div data-bind="foreach: items" class="maslosoft-gallery-image-row">
					<a data-bind="href: '?<?= $urlId; ?>:group=' + $parent.id" class="maslosoft-gallery-image-url" style="width:32%;height:50%;">
						<img data-bind="src: url + '/w/90/h/100/p/0'"/>
					</a>
				</div>
			</li>
		</ul>

		<div class="maslosoft-gallery-overlay" style="">
			<!--<div class="maslosoft-gallery-center-outer">-->
			<!--<div class="maslosoft-gallery-center-inner">-->
			<div data-bind="if: <?= $vm->selectedGroup ?>" class="maslosoft-gallery-group-view">
				<div class="maslosoft-gallery-groups">
					<?php // foreach ($groups as $group): ?>
					<?php /* @var $group Group */ ?>
					<div data-bind="foreach: <?= $vm->selectedGroup ?>.items" class="maslosoft-gallery-image-row">
						<?php // foreach ($group->items as $item): ?>
						<?php /* @var $item Item */ ?>
						<a data--bind="href: '?<?= $urlId; ?>:image=' + id" class="maslosoft-gallery-image-url">
							<img data-bind="src: url" class="img-polaroid dis--maslosoft-gallery-zoom-in" alt="<?php //= $item->title    ?>"/>
						</a>
						<?php // endforeach; ?>
					</div>
					<?php // endforeach; ?>
				</div>
			</div>
			<!--Controls-->
			<div class="maslosoft-gallery-controls">

				<div data-bind="foreach: <?= $vm->dp ?>.data" class="maslosoft-gallery-controls-thumbs">
					<span style="background:black;display:inline-block;margin: 0px 4px;">
					<a data-bind="foreach: items, href: '?<?= $urlId; ?>:group=' + id, css: {'maslosoft-gallery-controls-thumbs-active' : $index == <?= $vm->selectedGroupIndex;?>}" class="maslosoft-gallery-image-url">
						<img data-bind="src: url + '/w/64/h/64/p/0'" style="height:64px;width:64px"/>
					</a>
					</span>
				</div>
				<div class="maslosoft-gallery-controls-thumbs-selector">

				</div>

				<!--Navigation buttons-->
				<!--Previous group-->
				<span data-bind="if: <?= $vm->prevGroup?>">
				<a data-bind="href: '?<?= $urlId; ?>:group=' + <?= $vm->prevGroup?>.id" class="maslosoft-gallery-prev maslosoft-gallery-image-url" rel="tooltip" data-html="true" title="<?= txp('Use key {arrow} to navigate', '<big>&larr;</big>'); ?>"></a>
				</span>
				<!--Rewind to last-->
				<span data-bind="if: !<?= $vm->prevGroup?> && <?= $vm->lastGroup?>">
				<a data-bind="href: '?<?= $urlId; ?>:group=' + <?= $vm->lastGroup?>.id" class="maslosoft-gallery-prev maslosoft-gallery-image-url" rel="tooltip" data-html="true" title="<?= txp('Use key {arrow} to navigate', '<big>&larr;</big>'); ?>">FVD</a>
				</span>
				<!--Next group-->
				<span data-bind="if: <?= $vm->nextGroup?>">
				<a data-bind="href: '?<?= $urlId; ?>:group=' + <?= $vm->nextGroup?>.id" class="maslosoft-gallery-next maslosoft-gallery-image-url" rel="tooltip" data-html="true" title="<?= txp('Use key {arrow} to navigate', '<big>&rarr;</big>'); ?>"></a>
				</span>
				<!--Rewind to first-->
				<span data-bind="if: !<?= $vm->nextGroup?> && <?= $vm->firstGroup?>">
				<a data-bind="href: '?<?= $urlId; ?>:group=' + <?= $vm->firstGroup?>.id" class="maslosoft-gallery-next maslosoft-gallery-image-url" rel="tooltip" data-html="true" title="<?= txp('Use key {arrow} to navigate', '<big>&rarr;</big>'); ?>">REV</a>
				</span>

				<a href="#slide=true" data-bind="visible:!<?= $vm->slide; ?>" class="maslosoft-gallery-play maslosoft-gallery-image-url" rel="tooltip" data-html="true" data-placement="right" title="<?= txp('Start slideshow', '<big>&rarr;</big>'); ?>"></a>
				<a href="#slide=false" data-bind="visible:<?= $vm->slide; ?>" class="maslosoft-gallery-pause maslosoft-gallery-image-url" rel="tooltip" data-html="true" data-placement="right" title="<?= txp('Pause slideshow', '<big>&rarr;</big>'); ?>"></a>

				<a href="#fullscreen=true" data-bind="visible:!<?= $vm->fullscreen; ?>" class="maslosoft-gallery-full-screen maslosoft-gallery-image-url" rel="tooltip" data-html="true" data-placement="bottom" title="<?= txp('Go fullscreen', '<big>&rarr;</big>'); ?>"></a>
				<a href="#fullscreen=false" data-bind="visible:<?= $vm->fullscreen; ?>" class="maslosoft-gallery-normal-screen maslosoft-gallery-image-url" rel="tooltip" data-html="true" data-placement="bottom" title="<?= txp('Exit fullscreen', '<big>&rarr;</big>'); ?>"></a>

				<a href="/<?= Yii::app()->request->pathInfo; ?>/" class="maslosoft-gallery-close maslosoft-gallery-image-url" rel="tooltip" data-html="true" data-placement="bottom" title="<?= txp('Use key {key} to close', '<b>Esc</b>') ?>"></a>
<!--
				http://www.facebook.com/sharer/sharer.php?u=<url to share>&t=<message text>
				https://twitter.com/intent/tweet?text=<message>
				http://www.linkedin.com/shareArticle?mini=true&url=<url>&title=<title>&summary=<description>&source=<source>
-->
				<a href="#" class="maslosoft-gallery-f" rel="tooltip" data-html="true" data-placement="top" title="<?= txp('Post to facebook', '<big>&rarr;</big>'); ?>"></a>
				<a href="#" class="maslosoft-gallery-t" rel="tooltip" data-html="true" data-placement="top" title="<?= txp('Tweet it', '<big>&rarr;</big>'); ?>"></a>

				<a href="#" class="maslosoft-gallery-in" rel="tooltip" data-html="true" data-placement="top" title="<?= txp('Post to LinkedIn', '<big>&rarr;</big>'); ?>"></a>
				<a href="#" class="maslosoft-gallery-plus" rel="tooltip" data-html="true" data-placement="top" title="<?= txp('Post too Google+', '<big>&rarr;</big>'); ?>"></a>


			</div>
<!--			<h1 style="margin: auto;
display: block;
z-index: 1000000;
position: absolute;
top: 50%;
left: 0px;
right: 0px;
font-size: 1400%;"><?= tx('Finish');?></h1>-->
		</div>
	</div>
</div>