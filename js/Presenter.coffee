class @Maslosoft.Ilmatar.Widgets.Gallery.Presenter
	#
	# @var string
	#
	id: ''

	#
	# @var jQuery
	#
	element: null

	#
	# @var jQuery
	#
	overlay: null

	view: null

	row: null

	controls: null

	play: null

	stop: null

	moves: 100

	#
	# @param Maslosoft.Ilmatar.Widgets.Gallery.GalleryWidget gallery
	#
	constructor: (gallery) ->

		@vm = gallery.vm
		@element = jQuery "##{gallery.id}"
		@overlay = @element.find '.maslosoft-gallery-overlay'
		@view = @overlay.find '.maslosoft-gallery-group-view'
		@row = @view.find '.maslosoft-gallery-image-row'
		@controls = @overlay.find '.maslosoft-gallery-controls'
		@thumbs = @controls.find '.maslosoft-gallery-controls-thumbs'
		@thumbsSelector = @controls.find '.maslosoft-gallery-controls-thumbs-selector'

		@play = @element.find '.maslosoft-gallery-play'
		@pause = @element.find '.maslosoft-gallery-pause'

		@viewable = {
			width: 0,
			height: 0
		}

		# Controls fading
		if gallery.options.fadeControls

			# Start fading on mouse move
			jQuery(document).on 'mousemove', @mouseMove
			
			# This is to avoid flickering when mouse over link
			@element.on "mousemove", "a[rel='tooltip']", @urlMouseMove

			# Prevent fade when clicking without moving
			jQuery(document).on 'mousedown', @fadeControls

			# Prevent fade when using keyboard navigation
			jQuery(document).on 'keydown', @fadeControls

			# Initial fade
			@fadeControls()

	timer: null

	fadeControls: (e, data) =>
		@controls.stop(true, true)
		@controls.fadeIn()
		clearTimeout(@timer)
		@timer = setTimeout(@_fadeOutControls, 5000)

	_fadeOutControls: () =>
		@controls.fadeOut('slow')

	mouseMove: (e, data) =>
		# Ignore accidental moves
		@moves++
		if @moves > 20
			console.log 'mousemoves ' + @moves
			@moves = 0
			@fadeControls(e, data)

	urlMouseMove: (e) =>
		console.log 'url mouse move'
		e.stopPropagation()

	zoomOut: (e, data) =>
		e.stopPropagation()
		e.preventDefault()
		@showGroup()

	showImage: () =>
		@_calculateHeight()

		view = @view
		selector = "#mid-image-#{@vm.selectedImage.id} img"
		image = jQuery selector
		animation = {
			height:0,
			width:0
		}

		animation = {}

		jQuery('.maslosoft-gallery-groups img').not(selector).animate(animation, 200, () ->
			img = jQuery(this)
			img.parent().hide()
#			img.parent().parent().fadeout()
#			console.log img.parent()
			parents = img.parent().parents('.maslosoft-gallery-image-row')#.map(() -> return this)
#			console.log parents.length
#			console.log parents
			for val in parents
#				console.log image.parent().parent()
				elem = jQuery(val)
#				console.log elem.find(selector).length
				if not elem.find(selector).length
					elem.hide()
		)
#		console.log "viewable.height: #{@viewable.height}"
		animation = {
			height: @viewable.height,
			width: 'auto'
		}
		animation = {}
		image.animate animation, 500

		# Handle cursors
		image.addClass 'maslosoft-gallery-zoom-out'

		@showOverlay()

	showGroup: () ->
		groups = jQuery '.maslosoft-gallery-groups'
		groups.find('.maslosoft-gallery-image-row').show()
		groups.find('.maslosoft-gallery-image-url').show()
		images = groups.find('img')
		images.removeClass 'maslosoft-gallery-zoom-out'
		images.height(0)
		images.show()
		@showOverlay()

	showOverlay: () ->
		# Hide scrollbars only when they are visible
		console.log "scrollHeight: #{document.body.scrollHeight}"
		console.log "clientHeight: #{document.body.clientHeight}"
		if document.body.scrollHeight == document.body.clientHeight
			jQuery('body').addClass 'maslosoft-gallery-body-scrolls'
		@overlay.show()
		@_calculateHeight()

	hideOverlay: () ->
		@overlay.hide();
		jQuery('body').removeClass 'maslosoft-gallery-body-scrolls'

	resize: () =>
		@_calculateHeight()

	##
	# Helper methods
	##
	_calculate: () ->

		# Viewable area
		@viewable = {
			width: jQuery(window).width(),
			height: jQuery(window).height()
		}
		@viewable.ar = @viewable.width / @viewable.height
		
		# Set view dimentions
		oWidth = @_getOutlineWidth(@view)
		oHeight = @_getOutlineHeight(@view)
		@view.width(@viewable.width - oWidth)
		@view.height(@viewable.height - oHeight)

		# Images
		image = @view.find('img').first()
		oImageWidth = @_getOutlineWidth(image)
		oImageHeight = @_getOutlineHeight(image)

		totalWidth = 0
		weights = []
#		@vm.selectedGroup.items.length

		if @vm.selectedGroup
			for image in @vm.selectedGroup.items
				ar = (image.file.width + oImageWidth) / (image.file.height + oImageHeight)
				weights.push(parseInt(ar * 100))
				# NOTE @view.height() is on purpose here
				totalWidth += ar * @view.height()

			@imgAr = totalWidth / @view.height()
			rows = Math.ceil(@imgAr / @viewable.ar)

			images = @view.find('img')
			console.log images
	#		for image in images
			images.height(Math.ceil((@view.height()) / rows) - oImageHeight)

			console.log "Total width: #{totalWidth} (of images)"
			console.log "View width: #{@view.width()}"
			console.log weights
			console.log "Rows: #{rows}"
			partitions = @linearPartition(weights, rows)

			index = 0
			for part in partitions
				index += part.length
				console.log index
				jQuery(images[index - 1]).parent().after('<div />')

			@selectThumbs()

	selectThumbs: () ->
		console.log 'selectThumbs'
		@vm.selectedGroupIndex
		@thumbs.removeClass('maslosoft-gallery-controls-thumbs-active')
		
		@active = jQuery(@thumbs.find('a')[@vm.selectedGroupIndex])
		if not @active then return
		console.log @active
		@active.addClass('maslosoft-gallery-controls-thumbs-active')
		offset = @active.offset()
		width = @active.width()
		console.log offset
		console.log "Left position: #{@active.position().left}"
		console.log "Right thumbs offset: " + (@viewable.width - (@active.offset().left + @active.outerWidth()))
		# Selector is over 50%, slide thumbs
		if (@viewable.width / 2) < (@active.position().left + (width / 2))
			console.log 'Should scroll'
			left = (@viewable.width / 2) - (width / 2)
			@thumbsSelector.css({left: left, width: width})

			leftClip = left - offset.left
			console.log("#{@active.position().left} - #{leftClip}")
#			console.log(@thumbsSelector.css('left'))
#			left = left - @active.css('left')
			@thumbs.css({left: @thumbs.offset().left + leftClip})
		else
			@thumbs.css({left: 0})
			offset = @active.offset()
			width = @active.width()
			@thumbsSelector.css({left: offset.left, width: width})
			console.log 'Should not scroll'
			console.log "#{@viewable.width} / 2 - (#{@active.position().left + (width / 2)})"

		@thumbsSelector.animate({boxShadow: '0px 0px 1000px 0px white'}, 2000, null, () =>
			@thumbsSelector.animate({boxShadow: '0px 0px 0px 0px white'}, 2000)
		)
#			@thumbs.css({left: - (@active.position().left - width / 2)})

		

	_calculateHeight: () ->
		@_calculate()
		return
		total = @overlay.height()

		rows = @view.find('.maslosoft-gallery-image-row').length
		console.log rows

		image = @row.find('img').first()
		images = @row.find('img')

		# Get margins, paddings and border heights
		view = @_getOutlineHeight(@view)
		row = @_getOutlineHeight(@row)
		img = @_getOutlineHeight(image)
		ctrl = @controls.outerHeight()

		outersHeight = (row * rows) + view + (img * rows) + ctrl

		viewableOutersHeight = row + view + img + ctrl

		@viewable.height = (total - viewableOutersHeight)

		imgHeight = (total - outersHeight) / rows

		console.log "Viewable height: #{@viewable.height}"
		viewHeight = total - (view)
		console.log "imgHeight: #{imgHeight}"
		# For zoomed image
		if jQuery('.maslosoft-gallery-groups').find('img:visible').length is 1
			images.height(@viewable.height)
			images.width('auto')
		else
			images.height(imgHeight)
			images.width('auto')
#		@view.height(viewHeight)
#		console.log height

	# Get height of margins, padding and border widths
	_getOutlineHeight: (elem) ->

		padding = parseInt(elem.css('padding-top')) + parseInt(elem.css('padding-bottom'))
		margin = parseInt(elem.css('margin-top')) + parseInt(elem.css('margin-bottom'))
		border = parseInt(elem.css('border-top-width')) + parseInt(elem.css('border-bottom-width'))
#		console.log padding
#		console.log margin
#		console.log border
		return padding + margin + border

	_getOutlineWidth: (elem) ->
		padding = parseInt(elem.css('padding-left')) + parseInt(elem.css('padding-right'))
		margin = parseInt(elem.css('margin-left')) + parseInt(elem.css('margin-right'))
		border = parseInt(elem.css('border-left-width')) + parseInt(elem.css('border-right-width'))
#		console.log padding
#		console.log margin
#		console.log border
		return padding + margin + border

	# Linear partition
	# Partitions a sequence of non-negative integers into k ranges
	# Based on Óscar López implementation in Python (http://stackoverflow.com/a/7942946)
	# Also see http://www8.cs.umu.se/kurser/TDBAfl/VT06/algorithms/BOOK/BOOK2/NODE45.HTM
	# Dependencies: UnderscoreJS (http://www.underscorejs.org)
	# Example: linear_partition([9,2,6,3,8,5,8,1,7,3,4], 3) => [[9,2,6,3],[8,5,8],[1,7,3,4]]
	linearPartition: (seq, k) ->
		n = seq.length

		return [] if k <= 0
		return seq.map((x) -> [x]) if k > n

		table = (0 for x in [0...k] for y in [0...n])
		solution = (0 for x in [0...k-1] for y in [0...n-1])
		table[i][0] = seq[i] + (if i then table[i-1][0] else 0) for i in [0...n]
		table[0][j] = seq[0] for j in [0...k]
		for i in [1...n]
			for j in [1...k]
				m = _.min(([_.max([table[x][j-1], table[i][0]-table[x][0]]), x] for x in [0...i]), (o) -> o[0])
				table[i][j] = m[0]
				solution[i-1][j-1] = m[1]

		n = n-1
		k = k-2
		ans = []
		while k >= 0
			ans = [seq[i] for i in [(solution[n-1][k]+1)...n+1]].concat ans
			n = solution[n-1][k]
			k = k-1

		[seq[i] for i in [0...n+1]].concat ans
