module.exports = function (grunt) {
    require('load-grunt-tasks')(grunt);
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        /* on adapte le code à une distribution ou a du developpement local */
        replace: {
            dist: {
                src: ['application/**/*.php', 'application/config/config.php', 'assets/js/cx.js', '!application/logs/*.*'],
                overwrite: true,
                replacements: [{
                        from: "http://192.168.0.1/CIN/cx/",
                        to: "https://cx.carreauximportnegoce.fr/"
                    }, {
                        from: '<!-- reload grunt --><script src="//localhost:35729/livereload.js"></script>',
                        to: '<!-- reload grunt -->'
                    }, {
                        from: "'/CIN/cx/index.php/'",
                        to: "'/index.php/'"
                    }
                ]
            },
            dev: {
                src: ['application/**/*.php', 'application/config/config.php', 'assets/js/cx.js', '!application/logs/*.*'],
                overwrite: true,
                replacements: [{
                        to: "http://192.168.0.1/CIN/cx/",
                        from: "https://cx.carreauximportnegoce.fr/"
                    }, {
                        from: '<!-- reload grunt -->',
                        to: '<!-- reload grunt --><script src="//localhost:35729/livereload.js"></script>'
                    }, {
                        to: "'/CIN/cx/index.php/'",
                        from: "'/index.php/'"
                    }
                ]
            },
        },

        /* Verifie les fichiers JS (formatage de code) */
        jshint: {
            all: ['assets/js/cx.js', 'assets/js/ventes.js']
        },
        /* minifier les fichiers JS */
        uglify: {
            options: {
                mangle: true /* demande à ne pas changer les noms de variable lors de la minification */
            },
            dist: {
                files: {
                    'assets/js/min.js': ['assets/js/cx.js']
                }
            }
        },
        /* Minifier les fichiers CSS */
        cssmin: {
            dist: {
                files: {
                    'assets/css/min.css': ['assets/css/cx.css']                    
                }
            }
        },
        imagemin: {
            dist: {
                files: [{
                        expand: true,
                        cwd: 'assets/img/',
                        src: ['**/*.{png,jpg,gif}'],
                        dest: 'assets/img/min/'
                    },{
                        expand: true,
                        cwd: 'assets/pictures/',
                        src: ['**/*.{png,jpg,gif}'],
                        dest: 'assets/pictures/min/'
                    }]
            }
        },

        /* fonction de livereload */
        watch: {
            js: {
                files: ['assets/**/*.js', '!assets/js/min.js'],
                tasks: ['jshint', 'uglify'],
                options: {
                    spawn: false,
                    livereload: true
                }
            },
            css: {
                files: ['assets/css/*.css', '!assets/css/min.css'],
                tasks: ['cssmin'],
                options: {
                    spawn: false,
                    livereload: true
                }
            },
            htmlphp: {
                files: ['application/**/*.html', 'application/**/*.php', '!application/logs/*.*'],
                tasks: [],
                options: {
                    spawn: false,
                    livereload: true
                }
            }
        }

    });

    grunt.registerTask('dev', ['replace:dev', 'jshint', 'uglify', 'cssmin', ]);
    grunt.registerTask('dist', ['replace:dist', 'jshint', 'uglify', 'cssmin', 'imagemin', ]);

};
