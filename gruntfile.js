module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            dist1: {
                src: [
                    'js/source/scripts.js'
                ],
                dest: 'js/scripts.js',
            },

        },

        uglify: {
            build: {
                src: 'js/scripts.js',
                dest: 'js/scripts.min.js'
            },
        },

        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'css/style.min.css': 'css/sass/main.scss',
                }
            }
        },

        postcss: {
            options: {
                map: true,
                processors: [
                  require('autoprefixer')({browsers: ['last 2 versions']})
                ]
            },
            dist1: {
                src: 'css/extra.css'
            },
            dist2: {
                src: 'css/style.min.css'
            }
        },

        rsync: {
            options: {
                exclude: [".git*", ".sass-cache", "helpers", "gruntfile.js",
                          "Readme.md", "package.json", "node_modules", "*.scss",
                          "tests", 'css/sass', 'css/extra.css'],
                recursive: true
            },
            dist: {
                options: {
                    src: './',
                    dest: 'TODO',
                    host: "TODO",
                    delete: true
                }
            }
        },

        watch: {
            css: {
               files: [],
               tasks: ['sass', 'postcss', 'rsync', 'watch'],
                options: {
                    livereload: true,
                    spawn: false,
                }
            }, 
            css2: {
               files: [],
               tasks: ['rsync'],
                options: {
                    livereload: true,
                    spawn: false,
                }
            }, 
            scripts: {
                files: ['js/source/*.js'],
                tasks: ['concat', 'uglify', 'rsync'],
                options: {
                    livereload: true,
                    spawn: false,
                },
            },
            php: {
                files: ['*.php', 'templates/*.php'],
                tasks: ['rsync'],
                options: {
                    livereload: true,
                    spawn: false,
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-rsync');

    grunt.registerTask('default', ['watch']);

};  