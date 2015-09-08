class @Maslosoft.Gallery.Activities

	#
	# @var @Maslosoft.Gallery.GalleryVm
	#
	vm: null

	dm: null

	action: null

	presenter: null

	#
	# @param Maslosoft.Gallery.GalleryWidget gallery
	#
	constructor: (gallery) ->
		@vm = gallery.vm
		@dm = new Maslosoft.Gallery.DataManager(gallery)
		@presenter = gallery.presenter
		@action = gallery.action
	

	slide: (play) ->
		# TODO This should rely on knockout binding, not explicitly
		@vm.slide = !@vm.slide
		@slider()

	slider: () =>
		if @vm.slide
			if @vm.nextGroup
				@action.group(@dm.nextGroup(@vm.selectedGroup))
				setTimeout(@slider, 5000)
			else
				@action.group(@vm.firstGroup)
				@vm.slide = false
				@presenter.fadeControls()

	fullscreen: (active) ->
		if active is 'true'
			@vm.fullscreen = true
			screenfull.request()
		else
			@vm.fullscreen = false
			screenfull.exit()

