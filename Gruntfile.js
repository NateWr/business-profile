'use strict';

module.exports = function(grunt) {

	// Project configuration.
	grunt.initConfig({

		// Load grunt project configuration
		pkg: grunt.file.readJSON('package.json'),

		// Configure JSHint
		jshint: {
			test: {
				src: 'assets/js/**/*.js'
			}
		},

		// Watch for changes on some files and auto-compile them
		watch: {
			js: {
				files: ['assets/js/**/*.js'],
				tasks: ['jshint']
			},
		},

		// Create a .pot file
		makepot: {
			target: {
				options: {
					cwd: 'business-profile/',                          // Directory of files to internationalize.
					domainPath: 'languages',                   // Where to save the POT file.
					exclude: [],                      // List of files or directories to ignore.
					include: [],                      // List of files or directories to include.
					i18nToolsPath: '/media/Storage/projects/wordpress/trunk/tools/i18n',                // Path to the i18n tools directory.
					mainFile: 'business-profile.php',                     // Main project file.
					potComments: '',                  // The copyright at the beginning of the POT file.
					potFilename: '',                  // Name of the POT file.
					potHeaders: {
						poedit: true,                 // Includes common Poedit headers.
						'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
					},                                // Headers to add to the generated POT file.
					processPot: null,                 // A callback function for manipulating the POT file.
					type: 'wp-plugin',                // Type of project (wp-plugin or wp-theme).
					updateTimestamp: true             // Whether the POT-Creation-Date should be updated without other changes.
				}
			}
		}

	});

	// Load tasks
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-wp-i18n');

	// Default task(s).
	grunt.registerTask('default', ['watch']);

};
