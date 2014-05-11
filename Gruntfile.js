'use strict';

module.exports = function(grunt) {

	var export_dir = '../wp/wp-content/plugins';

	// Project configuration.
	grunt.initConfig({

		// Load grunt project configuration
		pkg: grunt.file.readJSON('package.json'),

		// Configure less CSS compiler
		less: {
			build: {
				options: {
					compress: true,
					cleancss: true,
					ieCompat: true
				},
				files: {
					'business-profile/assets/css/style.css': [
						'business-profile/assets/src/less/style.less',
						'business-profile/assets/src/less/style-*.less'
					]
				}
			}
		},

		// Configure JSHint
		jshint: {
			test: {
				src: 'business-profile/assets/src/js/*.js'
			}
		},

		// Concatenate scripts
		concat: {
			build: {
				files: {
					'business-profile/assets/js/frontend.js': [
						'business-profile/assets/src/js/frontend.js',
						'business-profile/assets/src/js/frontend-*.js'
					]
				}
			}
		},

		// Minimize scripts
		uglify: {
			options: {
				banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
			},
			build: {
				files: {
					'business-profile/assets/js/frontend.js' : 'business-profile/assets/js/frontend.js',
					'business-profile/assets/js/admin.js' : 'business-profile/assets/js/admin.js'
				}
			}
		},

		sync: {
			main: {
				files: [
					{
						cwd: 'business-profile/',
						src: '**',
						dest: export_dir + '/<%= pkg.name %>'
					}
				]
			}
		},

		// Watch for changes on some files and auto-compile them
		watch: {
			less: {
				files: ['business-profile/assets/src/less/*.less'],
				tasks: ['less', 'sync']
			},
			js: {
				files: ['business-profile/assets/src/js/*.js'],
				tasks: ['jshint', 'concat', 'uglify', 'sync']
			},
			sync: {
				files: ['!business-profile/**/*.less', '!business-profile/**/*.css', '!business-profile/**/*.js', 'business-profile/**/*'],
				tasks: ['sync']
			}
		}

	});

	// Load tasks
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-nodeunit');
	grunt.loadNpmTasks('grunt-sync');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');

	// Default task(s).
	grunt.registerTask('default', ['watch']);

};
