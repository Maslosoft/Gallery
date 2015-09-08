class @Maslosoft.Gallery.GalleryWidget

	#
	# @var string
	#
	id: ''

	#
	# @var Maslosoft.Gallery.Options
	#
	options: {}

	#
	# @var @Maslosoft.Gallery.GalleryVm
	#
	vm: null

	#
	# @var Maslosoft.Gallery.Presenter
	#
	presenter: null

	#
	# @var Maslosoft.Gallery.Actions
	#
	action: null

	#
	# @var Maslosoft.Gallery.Activities
	#
	activity: null

	constructor: (@id, @vm = {}, @options = {}) ->

		@presenter = new Maslosoft.Gallery.Presenter(@)
		@action = new Maslosoft.Gallery.Actions(@)
		@activity = new Maslosoft.Gallery.Activities(@)
		
		# Click events binding
		@presenter.element.on "click", ".maslosoft-gallery-image-url", @urlClick
		@presenter.overlay.on 'click', '.maslosoft-gallery-image-row', @overlayClick

		# Recalculate dimensions on resize
		jQuery(window).resize @presenter.resize
		History.Adapter.bind window, 'statechange', @historyChange
		@historyChange()

	##
	# Event handlers
	##
	historyChange: () =>
		state = History.getState()
		url = purl state.url

		console.log "Processing url #{state.url}"
		
		for name, value of url.param()
			console.log "#{name}: #{value}"
			break
		
		regex = new RegExp "#{@id}\:"
		console.log regex

		if name and name.match(regex)
			name = name.replace(regex, '')
			if typeof @action[name] is 'function'
				console.log "Calling @action.#{name}()"
				@action[name](value)
				return @

		@action.close()

	hashChange: (url) =>
		fragment = purl(url).attr('fragment')
		parts = fragment.split('=')
		name = parts.shift()
		value = parts.shift()
		if name
			if typeof @activity[name] is 'function'
				console.log "Calling @activity.#{name}()"
				@activity[name](value)
				return @
		return @

	urlClick: (e) =>
#		alert('urlclick')
		e.preventDefault()
		e.stopPropagation()
		element = jQuery(e.currentTarget)
		id = element.data('id')
		title = element.data('title')
		console.log "Clicked link #{e.currentTarget.href}"
		History.pushState(null, title, e.currentTarget.href)
		@hashChange(e.currentTarget.href)
		return false

	overlayClick: (e) =>
		console.log 'Maybe clicked overlay...'
		console.log e
		if jQuery(e.target).is('.maslosoft-gallery-image-row')
			console.log 'Ok, closing on overlay click'
			# TODO Handle `page` param history
			History.pushState(null, null, purl(window.location).attr('path'))
			@presenter.hideOverlay()
