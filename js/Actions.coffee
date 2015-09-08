class @Maslosoft.Gallery.Actions

	#
	# @var @Maslosoft.Gallery.GalleryVm
	#
	vm: null

	#
	# @var Maslosoft.Gallery.Presenter
	#
	presenter: null

	#
	# @var Maslosoft.Gallery.DataManager
	#
	dm: null

	#
	# @param Maslosoft.Gallery.GalleryWidget gallery
	#
	constructor: (gallery) ->
		@vm = gallery.vm
		@presenter = gallery.presenter
		@dm = new Maslosoft.Gallery.DataManager(gallery)
		
	##
	# Actions
	##

	#
	# Show page
	# TODO implement page navigation
	#
	page: (page) ->
		# TODO implement page sliding

	#
	# Show group
	#
	#
	group: (id = null) ->
		# TODO Group view code
		if id
			group = @dm.findGroup(id)
			console.log "Viewing group #{group.id}"
			@vm.selectedGroup = group
		else
			group = @vm.selectedGroup
		@vm.selectedGroupIndex = @dm.groupIndex(group)
		@vm.nextGroup = @dm.nextGroup(group)
		@vm.prevGroup = @dm.prevGroup(group)
		@presenter.showGroup()

	#
	# Show image
	#
	image: (id) ->
		# TODO Img view code
		image = @dm.findImage(id)

		console.log "Viewing image #{image.id}"
		@vm.selectedImage = image
		
		@presenter.showImage()

		# Handle zoom out
#		image.parent().one 'click', {image: image}, @group

		@vm.selectedImage = image

	close: (e = null) ->
		# TODO Handle `page` param history
		# FIXME @vm.selectedGroup() might not be set!
		# Just add ?page=1 etc.
		console.log 'Forced close'
		History.pushState(null, null, purl(window.location).attr('path'))
		@presenter.hideOverlay()
