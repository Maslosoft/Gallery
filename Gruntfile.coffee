coffees = [
	'_ns'
	'Presenter'
	'Actions'
	'Activities'
	'DataManager'
	'Grid'
	'GalleryWidget'
]

less = [
	'css/gallery.less'
]

module.exports = (grunt) ->
	c = new Array
	for name in coffees
		c.push "js/#{name}.coffee"

	# Project configuration.
	grunt.initConfig
		coffee:
			compile:
				options:
					sourceMap: true
					join: true
					expand: true
				files: [
					'dist/js/GalleryWidget.js': c
				]
		uglify:
			compile:
				files:
					'dist/js/GalleryWidget.min.js' : ['dist/js/GalleryWidget.js']
		watch:
			compile:
				files: c
				tasks: ['coffee:compile']
			less:
				files: less
				tasks: ['less:compile']
		less:
			compile:
				files:
					'css/gallery.css' : less
				options:
					sourceMap: true
		cssmin:
			target:
				files:
					'css/gallery.min.css' : ['css/gallery.css']

	# These plugins provide necessary tasks.
	grunt.loadNpmTasks 'grunt-contrib-coffee'
	grunt.loadNpmTasks 'grunt-contrib-watch'
	grunt.loadNpmTasks 'grunt-contrib-uglify'
	grunt.loadNpmTasks 'grunt-contrib-less'
	grunt.loadNpmTasks 'grunt-contrib-cssmin'

	# Default task.
	grunt.registerTask 'default', ['coffee', 'less', 'uglify', 'cssmin']
