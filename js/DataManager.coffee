class @Maslosoft.Ilmatar.Widgets.Gallery.DataManager

	#
	# @var @Maslosoft.Ilmatar.Widgets.Gallery.GalleryVm
	#
	vm: null

	#
	# @param Maslosoft.Ilmatar.Widgets.Gallery.GalleryWidget gallery
	#
	constructor: (gallery) ->
		@vm = gallery.vm
		@vm.firstGroup = @vm.dp.data[0]
		@vm.lastGroup = @vm.dp.data[@vm.dp.data.length - 1]

	# Find group by id
	# @param AssetGroup|string
	#
	findGroup: (id) ->
		# Assume group object was passed, only check if exists
		if id.id
			id = id.id

		# Loop thru groups
		for group in @vm.dp.data
			if group.id is id
				return group
		return false

	# Find group index
	# @param AssetGroup
	#
	groupIndex: (group) ->
		@vm.dp.data.indexOf(group)

	# Find next group by id
	# @param AssetGroup
	#
	nextGroup: (group) ->
		index = @vm.dp.data.indexOf(group)
		console.log
		if index >= 0 and index < @vm.dp.data.length - 1
			return @vm.dp.data[index + 1]
		return false

	# Find prev group by id
	# @param AssetGroup
	#
	prevGroup: (group) ->
		index = @vm.dp.data.indexOf(group)
		if index > 0 and index - 1 <= @vm.dp.data.length
			return @vm.dp.data[index - 1]
		return false


	# Find image by id
	# @param PageAsset|string
	#
	findImage: (id) ->
		# Assume item object was passed, only check if exists
		if id.id
			id = id.id

		# Loop thru groups and theirs items
		for group in @vm.dp.data
			for item in group.items
				if item.id is id
					return item
		return false
