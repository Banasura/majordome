var ALL_TASKS;

ALL_TASKS = ['concat:all'];

module.exports = function(grunt) {
    var exec, path;
    path = require('path');
    exec = require('child_process').exec;

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-compress');

    grunt.initConfig({
        pkg: '<json:package.json>',
        srcFolder: 'src',
        distFolder: 'dist',

        concat: {
            all: {
                files: {
                    'js/vendor.js': [
                        'bower_components/ie8-node-enum/index.js',
                        'bower_components/jquery-ui/ui/jquery.ui.core.js',
                        'bower_components/jquery-ui/ui/jquery.ui.widget.js',
                        'bower_components/jquery-ui/ui/jquery.ui.mouse.js',
                        'bower_components/jquery-ui/ui/jquery.ui.draggable.js',
                        'bower_components/jquery-ui/ui/jquery.ui.droppable.js',
                        'bower_components/jquery-ui/ui/jquery.ui.sortable.js',
                        'bower_components/jquery.scrollWindowTo/index.js',
                        'bower_components/underscore/underscore-min.js',
                        'bower_components/underscore.mixin.deepExtend/index.js',
                        'bower_components/rivets/dist/rivets.js',
                        'bower_components/backbone/backbone.js',
                        'bower_components/backbone-deep-model/src/deep-model.js'
                    ]
                }
            },
            mobile_friendly: {
                files: {
                    'js/vendor_mobile_friendly.js': [
                        'bower_components/ie8-node-enum/index.js',
                        'bower_components/jquery.scrollWindowTo/index.js',
                        'bower_components/underscore.mixin.deepExtend/index.js',
                        'bower_components/rivets/dist/rivets.js',
                        'bower_components/backbone-deep-model/src/deep-model.js'
                    ]
                }
            }
        },

        cssmin: {
            dist: {
                files: {
                    '<%= distFolder %>/css/admin.css': 'css/admin.css'
                }
            }
        },

        uglify: {
            dist: {
                files: {
                    '<%= distFolder %>/js/vendor.js': 'js/vendor.js',
                    '<%= distFolder %>/js/majordome.newform.js': 'js/majordome.newform.js'
                }
            }
        },

        compress: {
            dist: {
                options: {
                    archive: '<%= distFolder %>/majordome.zip',
                    mode: 'zip'
                },
                files: [{
                    src: [
                        '_admin.php',
                        '_define.php',
                        'index.php',
                        '_install.php',
                        '_prepend.php',
                        '_public.php',
                        'LICENSE',
                        'inc/**',
                        '<%= distFolder %>/js/*.js',
                        'js/formbuilder/dist/formbuilder-min.css',
                        'js/formbuilder/dist/formbuilder-min.js',
                        '<%= distFolder %>/css/admin.css',
                        'default-templates/**',
                        'img/**',
                        'locales/**.mo'
                    ]
                }]
            }
        }
    });

    grunt.registerTask('default', ALL_TASKS);
    grunt.registerTask('mobile_friendly', ['concat:mobile_friendly']);
    grunt.registerTask('dist', ['cssmin:dist', 'uglify:dist', 'compress:dist']);
};
