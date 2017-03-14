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
					domainPath: 'languages',
					processPot: function( pot, options ) {
						pot.headers['report-msgid-bugs-to'] = 'https://themeofthecrop.com';
						return pot;
					},
					type: 'wp-plugin',
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
