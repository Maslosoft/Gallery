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

copy = {
	gallery:
		expand:true
		cwd: 'css'
		src: '*.png'
		dest: 'dist/css/maslosoft-gallery'
}

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
					'dist/css/gallery.css' : less
				options:
					sourceMap: true
		cssmin:
			target:
				files:
					'dist/css/gallery.min.css' : ['dist/css/gallery.css']
		copy: copy

	# These plugins provide necessary tasks.
	grunt.loadNpmTasks 'grunt-contrib-coffee'
	grunt.loadNpmTasks 'grunt-contrib-watch'
	grunt.loadNpmTasks 'grunt-contrib-uglify'
	grunt.loadNpmTasks 'grunt-contrib-less'
	grunt.loadNpmTasks 'grunt-contrib-cssmin'
	grunt.loadNpmTasks 'grunt-contrib-copy'

	# Default task.
	grunt.registerTask 'default', ['coffee', 'less', 'uglify', 'cssmin', 'copy']
